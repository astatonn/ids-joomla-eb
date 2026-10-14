#!/usr/bin/env php
<?php

/**
 * IDS Joomla EB - script de build.
 *
 * Gera, a partir do código-fonte em src/:
 *   - packages/*.zip            uma extensão por arquivo (versionados no git: a raiz do repositório
 *                               continua sendo um pacote instalável pelo "Download ZIP" do GitHub);
 *   - dist/pkg_ids_joomla_eb.zip  o pacote completo, anexado a cada release;
 *   - updates/*.xml             servidores de atualização do Joomla, com o checksum de cada arquivo;
 *   - updates/changelog.xml     novidades de cada versão (lidas do CHANGELOG.md).
 *
 * Uso:
 *   php build/build.php                         gera tudo
 *   php build/build.php --check                 só confere (usado na integração contínua)
 *   php build/build.php --set-version=3.2.0     troca a versão em todos os manifestos e gera tudo
 *                       [--date=2026-12-01]     data da versão (padrão: hoje)
 *
 * Só precisa do PHP 8.1+ com a extensão zlib. Não usa Composer nem a extensão zip.
 *
 * @copyright  (C) 2024-2026 Lucas Lima e colaboradores
 * @license    GNU General Public License version 2 or later
 */

declare(strict_types=1);

const ROOT          = __DIR__ . '/..';
const REPO          = 'astatonn/ids-joomla-eb';
const DOWNLOAD_BASE = 'https://github.com/' . REPO . '/releases/download';
const INFO_URL      = 'https://github.com/' . REPO;
const CHANGELOG_URL = 'https://raw.githubusercontent.com/' . REPO . '/master/updates/changelog.xml';

// Versões do Joomla em que as atualizações são oferecidas (expressão regular testada no início de JVERSION).
const TARGET_PLATFORM = '(5\.[4-9]|6\.[0-9]+)';
const PHP_MINIMUM     = '8.1.0';

/**
 * Extensões do pacote.
 *   dir      pasta do código-fonte
 *   zip      arquivo gerado em packages/
 *   manifest manifesto XML dentro de "dir"
 *   type     tipo no Joomla
 *   element  identificador no Joomla (coluna "element" de #__extensions)
 *   client   site | administrator (como o Joomla registra a extensão)
 *   updates  arquivo do servidor de atualização em updates/
 *   title    nome exibido na lista de atualizações
 */
const EXTENSIONS = [
    [
        'dir' => 'src/template', 'zip' => 'novpadraoegov.zip', 'manifest' => 'templateDetails.xml',
        'type' => 'template', 'element' => 'idsgov-exrcitobrasileiro', 'client' => 'site',
        'updates' => 'template-govbr-updates.xml', 'title' => 'IDS Gov - Exército Brasileiro (template)',
    ],
    [
        'dir' => 'src/components/com_aniversariantes', 'zip' => 'com_aniversariantes.zip', 'manifest' => 'aniversariantes.xml',
        'type' => 'component', 'element' => 'com_aniversariantes', 'client' => 'administrator',
        'updates' => 'com_aniversariantes-updates.xml', 'title' => 'IDS Gov - Aniversariantes (componente)',
    ],
    [
        'dir' => 'src/components/com_pagtesouro', 'zip' => 'com_pagtesouro.zip', 'manifest' => 'pagtesouro.xml',
        'type' => 'component', 'element' => 'com_pagtesouro', 'client' => 'administrator',
        'updates' => 'com_pagtesouro-updates.xml', 'title' => 'IDS Gov - PagTesouro (componente)',
    ],
    [
        'dir' => 'src/modules/mod_aniver', 'zip' => 'mod_aniver.zip', 'manifest' => 'mod_aniver.xml',
        'type' => 'module', 'element' => 'mod_aniver', 'client' => 'site',
        'updates' => 'mod_aniver-updates.xml', 'title' => 'IDS Gov - Aniversariantes (módulo)',
    ],
    [
        'dir' => 'src/modules/mod_imagempopup', 'zip' => 'mod_imagempopup.zip', 'manifest' => 'mod_imagempopup.xml',
        'type' => 'module', 'element' => 'mod_imagempopup', 'client' => 'site',
        'updates' => 'mod_imagempopup-updates.xml', 'title' => 'IDS Gov - Popup (módulo)',
    ],
    [
        'dir' => 'src/modules/mod_instafeed', 'zip' => 'mod_instafeed.zip', 'manifest' => 'mod_instafeed.xml',
        'type' => 'module', 'element' => 'mod_instafeed', 'client' => 'site',
        'updates' => 'mod_instafeed-updates.xml', 'title' => 'IDS Gov - Instagram (módulo)',
    ],
    [
        'dir' => 'src/modules/mod_links', 'zip' => 'mod_links.zip', 'manifest' => 'mod_links.xml',
        'type' => 'module', 'element' => 'mod_links', 'client' => 'site',
        'updates' => 'mod_links-updates.xml', 'title' => 'IDS Gov - Links (módulo)',
    ],
    [
        'dir' => 'src/modules/mod_readmorenews', 'zip' => 'mod_readmorenews.zip', 'manifest' => 'mod_readmorenews.xml',
        'type' => 'module', 'element' => 'mod_readmorenews', 'client' => 'site',
        'updates' => 'mod_readmorenews-updates.xml', 'title' => 'IDS Gov - Botão Leia Mais (módulo)',
    ],
    [
        'dir' => 'src/modules/mod_videosiframe', 'zip' => 'mod_videosiframe.zip', 'manifest' => 'mod_videosiframe.xml',
        'type' => 'module', 'element' => 'mod_videosiframe', 'client' => 'site',
        'updates' => 'mod_videosiframe-updates.xml', 'title' => 'IDS Gov - Vídeos (módulo)',
    ],
];

const PACKAGE = [
    'manifest' => 'pkg_ids_joomla_eb.xml', 'zip' => 'pkg_ids_joomla_eb.zip',
    'type' => 'package', 'element' => 'pkg_ids_joomla_eb', 'client' => 'site',
    'updates' => 'ids-joomla-eb-updates.xml', 'title' => 'IDS Joomla EB (pacote completo)',
    // Arquivos da raiz do repositório que entram no pacote, além de packages/*.zip
    'files' => ['pkg_ids_joomla_eb.xml', 'script.php', 'language'],
];

// ---------------------------------------------------------------------------------------------
// Utilidades
// ---------------------------------------------------------------------------------------------

/**
 * Escritor de ZIP em PHP puro, com saída reprodutível: mesma entrada, mesmos bytes, em qualquer
 * máquina (quando $deflate é false; com compressão o resultado depende da versão da zlib).
 */
final class ZipWriter
{
    private string $body = '';
    private string $directory = '';
    private int $count = 0;
    private int $dosTime;
    private int $dosDate;

    public function __construct(\DateTimeImmutable $date)
    {
        // Datas anteriores a 1980 não existem no formato ZIP
        $year          = max(1980, (int) $date->format('Y'));
        $this->dosDate = (($year - 1980) << 9) | ((int) $date->format('n') << 5) | (int) $date->format('j');
        $this->dosTime = 0;
    }

    public function add(string $name, string $data, bool $deflate): void
    {
        $crc    = (int) hexdec(hash('crc32b', $data));
        $size   = strlen($data);
        $method = 0;
        $packed = $data;

        if ($deflate && $size > 0) {
            $compressed = gzdeflate($data, 9);

            if ($compressed !== false && strlen($compressed) < $size) {
                $packed = $compressed;
                $method = 8;
            }
        }

        // Bit 11: nome do arquivo em UTF-8
        $flags  = preg_match('/[^\x20-\x7e]/', $name) ? 0x0800 : 0;
        $needed = $method === 8 ? 20 : 10;
        $offset = strlen($this->body);

        $this->body .= pack('VvvvvvVVVvv', 0x04034b50, $needed, $flags, $method, $this->dosTime, $this->dosDate, $crc, strlen($packed), $size, strlen($name), 0)
            . $name . $packed;

        // "Versão criada por": Unix (3), especificação 2.0; atributos externos: arquivo comum 0644
        $this->directory .= pack('VvvvvvvVVVvvvvvVV', 0x02014b50, (3 << 8) | 20, $needed, $flags, $method, $this->dosTime, $this->dosDate, $crc, strlen($packed), $size, strlen($name), 0, 0, 0, 0, (0100644 << 16), $offset)
            . $name;

        $this->count++;
    }

    public function bytes(): string
    {
        return $this->body . $this->directory
            . pack('VvvvvVVv', 0x06054b50, 0, 0, $this->count, $this->count, strlen($this->directory), strlen($this->body), 0);
    }
}

/**
 * Lê o diretório central de um ZIP: nome => [crc32, tamanho descompactado].
 *
 * @return array<string, array{0: int, 1: int}>|null  null se o arquivo não for um ZIP legível
 */
function readZipIndex(string $bytes): ?array
{
    $eocd = strrpos($bytes, "PK\x05\x06");

    if ($eocd === false) {
        return null;
    }

    $info   = unpack('vdisk/vcdDisk/vdiskEntries/ventries/VcdSize/VcdOffset', substr($bytes, $eocd + 4, 16));
    $pos    = $info['cdOffset'];
    $result = [];

    for ($i = 0; $i < $info['entries']; $i++) {
        if (substr($bytes, $pos, 4) !== "PK\x01\x02") {
            return null;
        }

        $h    = unpack('Vcrc/Vcsize/Vusize/vnameLen/vextraLen/vcommentLen', substr($bytes, $pos + 16, 18));
        $name = substr($bytes, $pos + 46, $h['nameLen']);

        // Entradas de diretório (terminadas em "/") não contam
        if (!str_ends_with($name, '/')) {
            $result[$name] = [$h['crc'], $h['usize']];
        }

        $pos += 46 + $h['nameLen'] + $h['extraLen'] + $h['commentLen'];
    }

    ksort($result, SORT_STRING);

    return $result;
}

/**
 * Arquivos de uma pasta (recursivo), em ordem estável: caminho relativo => caminho completo.
 *
 * Usa scandir() de propósito: lê cada pasta de uma vez. Iteradores (RecursiveDirectoryIterator)
 * chegam a pular arquivos em pastas grandes de alguns sistemas de arquivos, como o disco do
 * Windows montado no WSL.
 *
 * @return array<string, string>
 */
function listFiles(string $dir, string $prefix = ''): array
{
    $entries = scandir($dir);

    if ($entries === false) {
        fail("não foi possível listar $dir");
    }

    $files = [];

    foreach ($entries as $name) {
        // Pastas especiais, lixo de sistema operacional e de editores
        if ($name === '.' || $name === '..' || $name === '.DS_Store' || $name === 'Thumbs.db' || $name === 'desktop.ini' || str_ends_with($name, '~') || str_ends_with($name, '.swp')) {
            continue;
        }

        $path = $dir . '/' . $name;

        if (is_dir($path)) {
            $files += listFiles($path, $prefix . $name . '/');
        } elseif (is_file($path)) {
            $files[$prefix . $name] = $path;
        }
    }

    if ($prefix === '') {
        ksort($files, SORT_STRING);
    }

    return $files;
}

function fail(string $message): never
{
    fwrite(STDERR, "ERRO: $message\n");
    exit(1);
}

function info(string $message): void
{
    fwrite(STDOUT, $message . "\n");
}

function readFileOrFail(string $path): string
{
    $data = @file_get_contents($path);

    if ($data === false) {
        fail("não foi possível ler $path");
    }

    return $data;
}

/** Grava o arquivo somente se o conteúdo mudou. Devolve true quando gravou. */
function writeIfChanged(string $path, string $data): bool
{
    if (is_file($path) && file_get_contents($path) === $data) {
        return false;
    }

    if (!is_dir(dirname($path))) {
        mkdir(dirname($path), 0777, true);
    }

    if (file_put_contents($path, $data) === false) {
        fail("não foi possível gravar $path");
    }

    return true;
}

function manifestValue(string $xml, string $tag): string
{
    return preg_match('~<' . $tag . '>([^<]*)</' . $tag . '>~', $xml, $m) ? trim($m[1]) : '';
}

function xml(string $text): string
{
    return htmlspecialchars($text, ENT_XML1 | ENT_QUOTES, 'UTF-8');
}

// ---------------------------------------------------------------------------------------------
// Etapas
// ---------------------------------------------------------------------------------------------

/** Caminhos dos manifestos de todas as extensões e do pacote. */
function allManifests(): array
{
    $paths = [ROOT . '/' . PACKAGE['manifest']];

    foreach (EXTENSIONS as $ext) {
        $paths[] = ROOT . '/' . $ext['dir'] . '/' . $ext['manifest'];
    }

    return $paths;
}

/** Troca <version> e <creationDate> em todos os manifestos e "version" nos joomla.asset.json. */
function setVersion(string $version, string $date): void
{
    if (!preg_match('/^\d+\.\d+\.\d+(-[0-9A-Za-z.]+)?$/', $version)) {
        fail("versão inválida: $version (use o formato 3.2.0)");
    }

    foreach (allManifests() as $path) {
        $xml = readFileOrFail($path);
        $xml = preg_replace('~<version>[^<]*</version>~', '<version>' . $version . '</version>', $xml, 1);
        $xml = preg_replace('~<creationDate>[^<]*</creationDate>~', '<creationDate>' . $date . '</creationDate>', $xml, 1);
        writeIfChanged($path, $xml);
    }

    foreach (EXTENSIONS as $ext) {
        foreach (listFiles(ROOT . '/' . $ext['dir']) as $relative => $path) {
            if (basename($relative) === 'joomla.asset.json') {
                $json = readFileOrFail($path);
                $json = preg_replace('~("name":\s*"[^"]+",\s*"version":\s*")[^"]*(")~', '${1}' . $version . '${2}', $json, 1);
                writeIfChanged($path, $json);
            }
        }
    }

    info("Versão definida: $version ($date)");
}

/** Confere que todos os manifestos têm a mesma versão e data; devolve [versão, data]. */
function readVersion(): array
{
    $package = readFileOrFail(ROOT . '/' . PACKAGE['manifest']);
    $version = manifestValue($package, 'version');
    $date    = manifestValue($package, 'creationDate');

    if ($version === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        fail('o manifesto do pacote precisa de <version> e de <creationDate> no formato AAAA-MM-DD');
    }

    foreach (allManifests() as $path) {
        $xml = readFileOrFail($path);

        if (manifestValue($xml, 'version') !== $version || manifestValue($xml, 'creationDate') !== $date) {
            fail(sprintf('%s está com versão/data diferente do pacote (%s, %s). Rode: php build/build.php --set-version=%s', substr($path, strlen(ROOT) + 1), $version, $date, $version));
        }

        if (!str_contains($xml, 'method="upgrade"')) {
            fail(substr($path, strlen(ROOT) + 1) . ' precisa de method="upgrade" (sem isso o Joomla recusa reinstalar/atualizar a extensão)');
        }
    }

    return [$version, $date];
}

/**
 * Gera o ZIP de uma extensão. Só regrava packages/<zip> quando o CONTEÚDO mudou, para o binário
 * versionado não variar à toa entre máquinas com versões diferentes da zlib.
 */
function buildExtension(array $ext, \DateTimeImmutable $date, bool $check): string
{
    $zip    = new ZipWriter($date);
    $wanted = [];

    foreach (listFiles(ROOT . '/' . $ext['dir']) as $relative => $path) {
        $data = readFileOrFail($path);
        $zip->add($relative, $data, true);
        $wanted[$relative] = [(int) hexdec(hash('crc32b', $data)), strlen($data)];
    }

    ksort($wanted, SORT_STRING);

    $target  = ROOT . '/packages/' . $ext['zip'];
    $current = is_file($target) ? readZipIndex(readFileOrFail($target)) : null;

    if ($current === $wanted) {
        return $target;
    }

    if ($check) {
        fail("packages/{$ext['zip']} está desatualizado em relação a {$ext['dir']}. Rode: php build/build.php");
    }

    writeIfChanged($target, $zip->bytes());
    info(sprintf('  packages/%-26s %4d arquivos', $ext['zip'], count($wanted)));

    return $target;
}

/** Gera dist/pkg_ids_joomla_eb.zip (sem compressão: os ZIPs internos já são comprimidos). */
function buildPackage(\DateTimeImmutable $date): string
{
    $zip = new ZipWriter($date);

    foreach (PACKAGE['files'] as $entry) {
        $path = ROOT . '/' . $entry;

        if (is_dir($path)) {
            foreach (listFiles($path) as $relative => $file) {
                $zip->add($entry . '/' . $relative, readFileOrFail($file), false);
            }
        } else {
            $zip->add($entry, readFileOrFail($path), false);
        }
    }

    $zips = array_column(EXTENSIONS, 'zip');
    sort($zips, SORT_STRING);

    foreach ($zips as $name) {
        $zip->add('packages/' . $name, readFileOrFail(ROOT . '/packages/' . $name), false);
    }

    $target = ROOT . '/dist/' . PACKAGE['zip'];
    writeIfChanged($target, $zip->bytes());

    return $target;
}

/**
 * Lê o CHANGELOG.md (formato "Keep a Changelog") e devolve as seções de cada versão no formato do
 * changelog do Joomla: versão => [tipo => itens].
 */
function parseChangelog(): array
{
    $map = [
        'segurança' => 'security', 'corrigido' => 'fix', 'adicionado' => 'addition', 'alterado' => 'change',
        'removido' => 'remove', 'idiomas' => 'language', 'notas' => 'note', 'atenção' => 'note',
    ];

    $versions = [];
    $version  = null;
    $section  = null;

    foreach (preg_split('/\R/', readFileOrFail(ROOT . '/CHANGELOG.md')) as $line) {
        if (preg_match('/^## \[?(\d+\.\d+\.\d+)\]?/', $line, $m)) {
            $version            = $m[1];
            $section            = null;
            $versions[$version] = [];
            continue;
        }

        if (preg_match('/^## /', $line)) {
            $version = null;
            continue;
        }

        if ($version === null) {
            continue;
        }

        if (preg_match('/^### (.+)$/u', $line, $m)) {
            // Tira emojis e espaços do título da seção
            $title   = mb_strtolower(trim(preg_replace('/[^\p{L} ]/u', '', $m[1])));
            $section = $map[$title] ?? null;
            continue;
        }

        if ($section === null) {
            continue;
        }

        if (preg_match('/^- (.+)$/', $line, $m)) {
            $versions[$version][$section][] = $m[1];
        } elseif (preg_match('/^\s{2,}(\S.*)$/', $line, $m) && !empty($versions[$version][$section])) {
            // Continuação do item anterior
            $last                                   = array_key_last($versions[$version][$section]);
            $versions[$version][$section][$last] .= ' ' . $m[1];
        }
    }

    // Texto simples: sem negrito, código e links do Markdown
    foreach ($versions as &$sections) {
        foreach ($sections as &$items) {
            foreach ($items as &$item) {
                $item = preg_replace('/\[([^\]]+)\]\([^)]+\)/', '$1', $item);
                $item = trim(str_replace(['**', '`'], '', $item));
            }
        }
    }

    return $versions;
}

function renderChangelogXml(array $versions): string
{
    $out = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n"
        . "<!-- Gerado por build/build.php a partir do CHANGELOG.md. Não edite à mão. -->\n"
        . "<changelogs>\n";

    foreach ($versions as $version => $sections) {
        if (!$sections) {
            continue;
        }

        $out .= "\t<changelog>\n"
            . "\t\t<element>" . PACKAGE['element'] . "</element>\n"
            . "\t\t<type>" . PACKAGE['type'] . "</type>\n"
            . "\t\t<version>" . xml((string) $version) . "</version>\n";

        foreach (['security', 'fix', 'addition', 'change', 'remove', 'language', 'note'] as $type) {
            if (empty($sections[$type])) {
                continue;
            }

            $out .= "\t\t<$type>\n";

            foreach ($sections[$type] as $item) {
                $out .= "\t\t\t<item>" . xml($item) . "</item>\n";
            }

            $out .= "\t\t</$type>\n";
        }

        $out .= "\t</changelog>\n";
    }

    return $out . "</changelogs>\n";
}

/** XML do servidor de atualização de uma extensão (ou do pacote). */
function renderUpdateXml(array $ext, string $version, string $zipPath): string
{
    $bytes = readFileOrFail($zipPath);
    $url   = DOWNLOAD_BASE . '/v' . $version . '/' . $ext['zip'];

    // Componentes são registrados no cliente "administrator", que é o padrão do Joomla quando <client> é omitido
    $client = $ext['type'] === 'component' ? '' : "\t\t<client>" . $ext['client'] . "</client>\n";

    return "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n"
        . "<!-- Gerado por build/build.php. Não edite à mão: os checksums precisam bater com os arquivos da release. -->\n"
        . "<updates>\n"
        . "\t<update>\n"
        . "\t\t<name>" . xml($ext['title']) . "</name>\n"
        . "\t\t<description>" . xml($ext['title'] . ' ' . $version . ' - compatível com Joomla 5.4 e Joomla 6') . "</description>\n"
        . "\t\t<element>" . $ext['element'] . "</element>\n"
        . "\t\t<type>" . $ext['type'] . "</type>\n"
        . $client
        . "\t\t<version>" . xml($version) . "</version>\n"
        . "\t\t<infourl title=\"IDS Joomla EB " . xml($version) . "\">" . INFO_URL . '/releases/tag/v' . xml($version) . "</infourl>\n"
        . "\t\t<downloads>\n"
        . "\t\t\t<downloadurl type=\"full\" format=\"zip\">" . xml($url) . "</downloadurl>\n"
        . "\t\t</downloads>\n"
        . "\t\t<sha256>" . hash('sha256', $bytes) . "</sha256>\n"
        . "\t\t<sha512>" . hash('sha512', $bytes) . "</sha512>\n"
        . "\t\t<changelogurl>" . CHANGELOG_URL . "</changelogurl>\n"
        . "\t\t<tags>\n"
        . "\t\t\t<tag>stable</tag>\n"
        . "\t\t</tags>\n"
        . "\t\t<maintainer>3º Sgt Souza Lima</maintainer>\n"
        . "\t\t<maintainerurl>" . INFO_URL . "</maintainerurl>\n"
        . "\t\t<targetplatform name=\"joomla\" version=\"" . xml(TARGET_PLATFORM) . "\" />\n"
        . "\t\t<php_minimum>" . PHP_MINIMUM . "</php_minimum>\n"
        . "\t</update>\n"
        . "</updates>\n";
}

/** Grava (ou, em --check, confere) um arquivo gerado em updates/. */
function emit(string $relative, string $content, bool $check): void
{
    $path = ROOT . '/' . $relative;

    if ($check) {
        if (!is_file($path) || file_get_contents($path) !== $content) {
            fail("$relative está desatualizado. Rode: php build/build.php");
        }

        return;
    }

    if (writeIfChanged($path, $content)) {
        info("  $relative");
    }
}

// ---------------------------------------------------------------------------------------------
// Execução
// ---------------------------------------------------------------------------------------------

$options = getopt('', ['check', 'set-version:', 'date:', 'help']);

if (isset($options['help'])) {
    info(<<<'AJUDA'
    Uso:
      php build/build.php                       gera packages/*.zip, dist/ e updates/*.xml
      php build/build.php --check               só confere se está tudo atualizado (integração contínua)
      php build/build.php --set-version=3.2.0   troca a versão em todos os manifestos e gera tudo
                          [--date=AAAA-MM-DD]   data da versão (padrão: hoje)
    AJUDA);
    exit(0);
}

if (!function_exists('gzdeflate')) {
    fail('a extensão zlib do PHP é necessária');
}

$check = isset($options['check']);

if (isset($options['set-version'])) {
    if ($check) {
        fail('--check e --set-version não podem ser usados juntos');
    }

    setVersion((string) $options['set-version'], (string) ($options['date'] ?? date('Y-m-d')));
}

[$version, $dateText] = readVersion();
$date                 = new DateTimeImmutable($dateText . ' 00:00:00', new DateTimeZone('UTC'));

info(($check ? 'Conferindo' : 'Gerando') . " IDS Joomla EB $version ($dateText)");

$changelog = parseChangelog();

if (empty($changelog[$version])) {
    fail("o CHANGELOG.md não tem uma seção \"## [$version]\" com itens");
}

foreach (EXTENSIONS as $ext) {
    $zipPath = buildExtension($ext, $date, $check);
    emit('updates/' . $ext['updates'], renderUpdateXml($ext, $version, $zipPath), $check);
}

$packageZip = buildPackage($date);
emit('updates/' . PACKAGE['updates'], renderUpdateXml(PACKAGE, $version, $packageZip), $check);
emit('updates/changelog.xml', renderChangelogXml($changelog), $check);

// Cópias para anexar à release (dist/ não é versionado)
foreach (EXTENSIONS as $ext) {
    writeIfChanged(ROOT . '/dist/' . $ext['zip'], readFileOrFail(ROOT . '/packages/' . $ext['zip']));
}

info(sprintf('%s dist/%s (%s KB, sha256 %s…)', $check ? 'OK:' : 'Pronto:', PACKAGE['zip'], number_format(filesize($packageZip) / 1024, 0, ',', '.'), substr(hash_file('sha256', $packageZip), 0, 16)));
