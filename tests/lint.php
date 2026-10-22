#!/usr/bin/env php
<?php

/**
 * IDS Joomla EB - verificações estáticas.
 *
 * Roda em qualquer máquina com PHP 8.1+ (sem Docker, sem Composer):
 *
 *   php tests/lint.php
 *
 * Confere, para todo o código em src/ e para os arquivos do pacote:
 *   1. sintaxe de todos os arquivos PHP;
 *   2. uso de APIs removidas no Joomla 6 (a causa do erro "Trait ... DatabaseAwareTrait not found");
 *   3. XML bem formado e manifestos coerentes (method="upgrade", versão única, pastas declaradas existem);
 *   4. arquivos de idioma: sintaxe, mesmas chaves em en-GB e pt-BR, chaves usadas nos XML existem;
 *   5. servidores de atualização: identificadores, cliente, checksum e changelog;
 *   6. JSON dos assets.
 *
 * @copyright  (C) 2024-2026 Lucas Lima e colaboradores
 * @license    GNU General Public License version 2 or later
 */

declare(strict_types=1);

const ROOT = __DIR__ . '/..';

$errors = [];
$checks = 0;

function fail(string $message): void
{
    global $errors;
    $errors[] = $message;
}

function ok(): void
{
    global $checks;
    $checks++;
}

/** @return string[] caminhos relativos à raiz, em ordem */
function files(string $dir, ?string $pattern = null): array
{
    $result = [];
    $base   = ROOT . '/' . $dir;

    if (!is_dir($base)) {
        return [];
    }

    // scandir() de propósito: iteradores pulam arquivos em alguns sistemas de arquivos (WSL/drvfs).
    $walk = static function (string $path) use (&$walk, &$result, $pattern): void {
        foreach (scandir(ROOT . '/' . $path) ?: [] as $name) {
            if ($name === '.' || $name === '..') {
                continue;
            }

            $child = $path . '/' . $name;

            // Bibliotecas de terceiros distribuídas com o template: não são código nosso
            if ($child === 'src/template/media') {
                continue;
            }

            if (is_dir(ROOT . '/' . $child)) {
                $walk($child);
            } elseif ($pattern === null || preg_match($pattern, $name)) {
                $result[] = $child;
            }
        }
    };

    $walk($dir);
    sort($result, SORT_STRING);

    return $result;
}

function read(string $relative): string
{
    return (string) file_get_contents(ROOT . '/' . $relative);
}

// Código nosso (exclui bibliotecas de terceiros distribuídas com o template)
$isOurs = static fn (string $path): bool => !str_contains($path, 'src/template/media/');

// ---------------------------------------------------------------------------------------------
// 1. Sintaxe PHP
// ---------------------------------------------------------------------------------------------
$phpFiles = array_merge(files('src', '/\.php$/'), files('build', '/\.php$/'), files('tests', '/\.php$/'), ['script.php']);

foreach ($phpFiles as $file) {
    try {
        // TOKEN_PARSE faz o analisador do PHP validar o arquivo inteiro, como "php -l"
        token_get_all(read($file), TOKEN_PARSE);
    } catch (ParseError $e) {
        fail("$file: erro de sintaxe na linha " . $e->getLine() . ': ' . $e->getMessage());
    }

    ok();
}

// ---------------------------------------------------------------------------------------------
// 2. APIs removidas (ou que só existem com o plugin de compatibilidade) no Joomla 6
// ---------------------------------------------------------------------------------------------
$forbidden = [
    '/Joomla\\\\CMS\\\\MVC\\\\Model\\\\DatabaseAwareTrait/' => 'trait removido no Joomla 6: use Joomla\Database\DatabaseAwareTrait',
    '/\bJPATH_PLATFORM\b/'                                  => 'constante inexistente no Joomla 6 sem o plugin compat6: use _JEXEC',
    '/Joomla\\\\CMS\\\\Filesystem\\\\/'                     => 'namespace movido para o plugin compat6: use Joomla\Filesystem',
    '/Joomla\\\\CMS\\\\Input\\\\/'                          => 'namespace movido para o plugin compat6: use Joomla\Input',
    '/\bJ(Factory|Html|HTML|Text|Route|Uri|Session|Error|Table|ControllerLegacy|ModelLegacy|ViewLegacy|LayoutHelper|HtmlSidebar|ToolbarHelper|Registry|Date|Form|Loader)\b/'
                                                             => 'classe legada sem namespace: indisponível no Joomla 6 por padrão',
    '/\bFactory::get(Dbo|User|Document|Language|Session|Config|Mailer|Cache)\s*\(/' => 'método depreciado da Factory: use o container, a aplicação ou a injeção de dependência',
    '/->getDbo\s*\(/'                                        => 'método depreciado: use getDatabase()',
    '/->setDbo\s*\(/'                                        => 'método depreciado: use setDatabase()',
    '/->triggerEvent\s*\(/'                                  => 'método depreciado: use o dispatcher de eventos',
    '/\$this->_db\b/'                                        => 'propriedade legada: use getDatabase()',
    '/->input->/'                                            => 'propriedade depreciada da aplicação: use getInput() (em controllers, $this->input continua válido)',
    '/\bjimport\s*\(/'                                       => 'função legada',
    '/CMSObject/'                                            => 'classe depreciada',
    '/formbehavior\.chosen/'                                 => 'helper removido',
    '/\bstrftime\s*\(/'                                      => 'função removida no PHP 9',
];

foreach (array_filter(files('src', '/\.php$/'), $isOurs) as $file) {
    $code = read($file);

    // Comentários explicam as APIs antigas; não contam como uso.
    $stripped = '';

    foreach (token_get_all($code) as $token) {
        if (is_array($token) && in_array($token[0], [T_COMMENT, T_DOC_COMMENT, T_INLINE_HTML], true)) {
            continue;
        }

        $stripped .= is_array($token) ? $token[1] : $token;
    }

    foreach ($forbidden as $regex => $why) {
        // Em controllers, $this->input é a propriedade oficial do BaseController
        $subject = $regex === '/->input->/' ? str_replace('$this->input->', '', $stripped) : $stripped;

        if (preg_match($regex, $subject, $m)) {
            fail("$file: usa \"{$m[0]}\" ($why)");
        }
    }

    if (!preg_match('/defined\s*\(\s*\'_JEXEC\'\s*\)\s*or\s*die/', $code)) {
        fail("$file: falta a proteção \"defined('_JEXEC') or die\"");
    }

    ok();
}

// ---------------------------------------------------------------------------------------------
// 3. XML e manifestos
// ---------------------------------------------------------------------------------------------
libxml_use_internal_errors(true);

$manifests = [];   // caminho => SimpleXMLElement

foreach (array_merge(array_filter(files('src', '/\.xml$/'), $isOurs), files('updates', '/\.xml$/'), ['pkg_ids_joomla_eb.xml']) as $file) {
    $xml = simplexml_load_string(read($file));

    if ($xml === false) {
        fail("$file: XML mal formado: " . trim((string) (libxml_get_last_error()->message ?? '')));
        libxml_clear_errors();
        continue;
    }

    if ($xml->getName() === 'extension') {
        $manifests[$file] = $xml;
    }

    ok();
}

$version = (string) ($manifests['pkg_ids_joomla_eb.xml']->version ?? '');

if (!preg_match('/^\d+\.\d+\.\d+$/', $version)) {
    fail('pkg_ids_joomla_eb.xml: <version> inválida');
}

$elements = [];   // zip => [type, element, client]

foreach ($manifests as $file => $xml) {
    $dir  = dirname($file);
    $type = (string) $xml['type'];

    if ((string) $xml['method'] !== 'upgrade') {
        fail("$file: falta method=\"upgrade\" (sem isso o Joomla recusa atualizar a extensão)");
    }

    if ((string) $xml->version !== $version) {
        fail("$file: versão {$xml->version} diferente da do pacote ($version)");
    }

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $xml->creationDate)) {
        fail("$file: <creationDate> deve estar no formato AAAA-MM-DD");
    }

    // Tudo o que o manifesto declara precisa existir (a versão 3.0.0 do PagTesouro não instalava por isso)
    $declared = [];

    foreach ($xml->xpath('//files | //media | //languages') as $group) {
        // <files> de um pacote lista os ZIPs gerados pelo build
        if ($type === 'package' && $group->getName() === 'files') {
            foreach ($group->file as $child) {
                $elements[(string) $child] = [(string) $child['type'], (string) $child['id'], (string) ($child['client'] ?: 'administrator')];
            }

            continue;
        }

        $folder = trim((string) $group['folder'], '/');
        $prefix = $dir === '.' ? '' : $dir . '/';
        $prefix .= $folder !== '' ? $folder . '/' : '';

        foreach ($group->children() as $child) {
            $declared[] = [$prefix . trim((string) $child), $child->getName()];
        }
    }

    if ((string) $xml->scriptfile !== '') {
        $declared[] = [($dir === '.' ? '' : $dir . '/') . (string) $xml->scriptfile, 'filename'];
    }

    foreach ($declared as [$path, $kind]) {
        $exists = $kind === 'folder' ? is_dir(ROOT . '/' . $path) : is_file(ROOT . '/' . $path);

        if (!$exists) {
            fail("$file: declara \"$path\", que não existe");
        }
    }

    // O contrário também: arquivo no código-fonte que o manifesto não instala é arquivo esquecido
    if ($type !== 'package') {
        $topLevel = [];

        foreach ($declared as [$path]) {
            $topLevel[] = $path;
        }

        foreach (files($dir) as $source) {
            if ($source === $file) {
                continue;
            }

            $covered = false;

            foreach ($topLevel as $path) {
                if ($source === $path || str_starts_with($source, $path . '/')) {
                    $covered = true;
                    break;
                }
            }

            if (!$covered) {
                fail("$file: \"$source\" existe em src/ mas não é instalado por nenhuma entrada do manifesto");
            }
        }
    }

    if ($type !== 'package' && !str_contains(read($file), '<updateservers>')) {
        fail("$file: falta <updateservers>");
    }

    if (isset($xml->namespace) && !is_dir(ROOT . '/' . $dir . '/' . (is_dir(ROOT . '/' . $dir . '/administrator') ? 'administrator/' : '') . (string) $xml->namespace['path'])) {
        fail("$file: <namespace path=\"{$xml->namespace['path']}\"> aponta para uma pasta que não existe");
    }

    ok();
}

// ---------------------------------------------------------------------------------------------
// 4. Idiomas
// ---------------------------------------------------------------------------------------------
$languageKeys = [];   // pasta da extensão => chaves disponíveis (todas as .ini pt-BR)

foreach (array_merge(array_filter(files('src', '/\.ini$/'), $isOurs), files('language', '/\.ini$/')) as $file) {
    $content = read($file);

    if (str_starts_with($content, "\xEF\xBB\xBF")) {
        fail("$file: salvo com BOM (use UTF-8 sem BOM)");
    }

    if (!mb_check_encoding($content, 'UTF-8')) {
        fail("$file: não está em UTF-8");
    }

    $parsed = @parse_ini_string($content, false, INI_SCANNER_RAW);

    if ($parsed === false) {
        fail("$file: erro de sintaxe no arquivo de idioma");
        continue;
    }

    // Cada linha de texto deve ser CHAVE="valor"
    foreach (preg_split('/\R/', $content) as $number => $line) {
        if ($line === '' || $line[0] === ';') {
            continue;
        }

        if (!preg_match('/^[A-Z][A-Z0-9_.-]*="(?:[^"\\\\]|\\\\.)*"$/', $line)) {
            fail("$file:" . ($number + 1) . ': linha fora do formato CHAVE="valor" (aspas internas devem ser \\")');
        }
    }

    // Paridade en-GB / pt-BR
    if (str_contains($file, '/en-GB/')) {
        $twin = str_replace('/en-GB/', '/pt-BR/', $file);

        if (!is_file(ROOT . '/' . $twin)) {
            fail("$file: falta o arquivo correspondente $twin");
        } else {
            $other = @parse_ini_string(read($twin), false, INI_SCANNER_RAW) ?: [];
            $diff  = array_merge(array_diff_key($parsed, $other), array_diff_key($other, $parsed));

            if ($diff) {
                fail("$file e $twin têm chaves diferentes: " . implode(', ', array_slice(array_keys($diff), 0, 5)));
            }
        }
    }

    if (str_contains($file, '/pt-BR/') && preg_match('~^(src/(?:template|components/[^/]+|modules/[^/]+))/~', $file, $m)) {
        $languageKeys[$m[1]] = array_merge($languageKeys[$m[1]] ?? [], array_keys($parsed));
    }

    ok();
}

// Campos dos formulários que não podem depender das traduções do núcleo do Joomla
foreach (array_filter(files('src', '/\.xml$/'), $isOurs) as $file) {
    $xml = @simplexml_load_string(read($file));

    if ($xml === false) {
        continue;
    }

    foreach ($xml->xpath('//field[@type="calendar"]') as $field) {
        // O pacote pt-BR oficial traz DATE_FORMAT_CALENDAR_DATE e DATE_FORMAT_FILTER_DATE incompatíveis
        if ((string) $field['translateformat'] === 'true' || (string) $field['format'] === '' || (string) $field['filterformat'] === '') {
            fail("$file: o campo de data \"{$field['name']}\" deve declarar format e filterformat (sem translateformat)");
        }
    }

    foreach ($xml->xpath('//option') as $option) {
        // O pacote pt-BR oficial traduz JNO como "Nunca"
        if (in_array(trim((string) $option), ['JNO', 'JYES'], true)) {
            fail("$file: use as chaves _SIM/_NAO da própria extensão em vez de " . trim((string) $option));
        }
    }

    ok();
}

// Chaves de idioma usadas nos XML (rótulos, descrições, opções) precisam existir na extensão
foreach (array_filter(files('src', '/\.xml$/'), $isOurs) as $file) {
    if (!preg_match('~^(src/(?:template|components/[^/]+|modules/[^/]+))/~', $file, $m)) {
        continue;
    }

    $own = array_flip($languageKeys[$m[1]] ?? []);

    preg_match_all('/(?:label|description|hint|title|option)="([A-Z][A-Z0-9_]*_[A-Z0-9_.-]+)"|>([A-Z][A-Z0-9_]*_[A-Z0-9_.-]+)<|\[CDATA\[([A-Z][A-Z0-9_]+)\]\]/', read($file), $found);

    foreach (array_unique(array_filter(array_merge($found[1], $found[2], $found[3]))) as $key) {
        // Chaves do núcleo do Joomla (J..., COM_MODULES_..., nomes de meses) não são nossas
        if (preg_match('/^(J[A-Z]|COM_MODULES_|COM_CONTENT_|JLIB_)/', $key) || isset($own[$key])) {
            continue;
        }

        if (preg_match('/^(TPL_|COM_ANIVERSARIANTES|COM_PAGTESOURO|MOD_(ANIVER|IMAGEMPOPUP|INSTAFEED|LINKS|READMORENEWS|VIDEOSIFRAME))/', $key)) {
            fail("$file: usa a chave de idioma $key, que não existe nos arquivos .ini da extensão");
        }
    }

    ok();
}

// ---------------------------------------------------------------------------------------------
// 5. Servidores de atualização
// ---------------------------------------------------------------------------------------------
$updateFiles = [
    'pkg_ids_joomla_eb.zip'   => 'ids-joomla-eb-updates.xml',
    'novpadraoegov.zip'       => 'template-govbr-updates.xml',
    'com_aniversariantes.zip' => 'com_aniversariantes-updates.xml',
    'com_pagtesouro.zip'      => 'com_pagtesouro-updates.xml',
    'mod_aniver.zip'          => 'mod_aniver-updates.xml',
    'mod_imagempopup.zip'     => 'mod_imagempopup-updates.xml',
    'mod_instafeed.zip'       => 'mod_instafeed-updates.xml',
    'mod_links.zip'           => 'mod_links-updates.xml',
    'mod_readmorenews.zip'    => 'mod_readmorenews-updates.xml',
    'mod_videosiframe.zip'    => 'mod_videosiframe-updates.xml',
];

$elements['pkg_ids_joomla_eb.zip'] = ['package', 'pkg_ids_joomla_eb', 'site'];

foreach ($updateFiles as $zip => $updateFile) {
    $path = "updates/$updateFile";

    if (!is_file(ROOT . '/' . $path)) {
        fail("$path: não existe. Rode: php build/build.php");
        continue;
    }

    $xml    = simplexml_load_string(read($path));
    $update = $xml->update[0] ?? null;

    if ($update === null || !isset($elements[$zip])) {
        fail("$path: sem <update> ou sem entrada correspondente no manifesto do pacote");
        continue;
    }

    [$type, $element, $client] = $elements[$zip];

    if ((string) $update->element !== $element || (string) $update->type !== $type) {
        fail("$path: anuncia {$update->type}/{$update->element}, mas a extensão instalada é $type/$element (o Joomla não encontraria a atualização)");
    }

    // Sem <client>, o Joomla assume "administrator"
    $declaredClient = (string) $update->client !== '' ? (string) $update->client : 'administrator';

    if ($declaredClient !== $client) {
        fail("$path: <client> é $declaredClient, mas a extensão é do cliente $client");
    }

    if ((string) $update->version !== $version) {
        fail("$path: versão {$update->version} diferente da do pacote ($version)");
    }

    $url = trim((string) $update->downloads->downloadurl);

    if ($url !== "https://github.com/astatonn/ids-joomla-eb/releases/download/v$version/$zip") {
        fail("$path: endereço de download inesperado: $url");
    }

    $local = $zip === 'pkg_ids_joomla_eb.zip' ? "dist/$zip" : "packages/$zip";

    if (is_file(ROOT . '/' . $local)) {
        if (hash_file('sha256', ROOT . '/' . $local) !== (string) $update->sha256 || hash_file('sha512', ROOT . '/' . $local) !== (string) $update->sha512) {
            fail("$path: checksum diferente do arquivo $local. Rode: php build/build.php");
        }
    } elseif ($zip !== 'pkg_ids_joomla_eb.zip') {
        fail("$local: não existe. Rode: php build/build.php");
    }

    $platform = (string) $update->targetplatform['version'];

    foreach (['5.4.0' => true, '5.4.10' => true, '6.0.1' => true, '6.2.0' => true, '5.3.4' => false, '4.4.14' => false, '7.0.0' => false] as $joomla => $expected) {
        if ((bool) preg_match('/^' . $platform . '/', $joomla) !== $expected) {
            fail("$path: targetplatform \"$platform\" " . ($expected ? 'deveria' : 'não deveria') . " aceitar o Joomla $joomla");
        }
    }

    ok();
}

// Os endereços dos servidores de atualização já estão registrados nos sites em produção: não podem mudar.
foreach ($manifests as $file => $xml) {
    foreach ($xml->updateservers->server ?? [] as $server) {
        $url = trim((string) $server);

        if (!preg_match('~^https://raw\.githubusercontent\.com/astatonn/ids-joomla-eb/master/updates/([a-z_-]+-updates\.xml)$~', $url, $m) || !in_array($m[1], $updateFiles, true)) {
            fail("$file: servidor de atualização inesperado: $url");
        }
    }
}

$changelog = is_file(ROOT . '/updates/changelog.xml') ? simplexml_load_string(read('updates/changelog.xml')) : false;

if ($changelog === false || !$changelog->xpath("//changelog[version='$version']/*/item")) {
    fail("updates/changelog.xml: sem itens para a versão $version (confira a seção no CHANGELOG.md e rode o build)");
}

ok();

// ---------------------------------------------------------------------------------------------
// 6. Ambiente Docker seguro
// ---------------------------------------------------------------------------------------------
$php2shellConfig = 'docker/php/conf.d/ids-joomla-eb.ini';

if (!is_file(ROOT . '/' . $php2shellConfig)) {
    fail("$php2shellConfig: configuração de defesa em profundidade para PHP2Shell ausente");
} else {
    $config = read($php2shellConfig);

    if (!preg_match('/^session\.upload_progress\.enabled\s*=\s*Off\s*$/mi', $config)) {
        fail("$php2shellConfig: session.upload_progress.enabled precisa estar desativado");
    }
}

$dockerfile = read('dockerfile');

foreach (['8.2.35', '8.3.36', '8.4.27', '8.5.12', 'CVE-2026-109618', 'CVE-2026-109619'] as $required) {
    if (!str_contains($dockerfile, $required)) {
        fail("dockerfile: falta a validação PHP2Shell para $required");
    }
}

if (!str_contains($dockerfile, "COPY $php2shellConfig ")) {
    fail("dockerfile: não copia $php2shellConfig para a imagem");
}

ok();

// ---------------------------------------------------------------------------------------------
// 7. JSON
// ---------------------------------------------------------------------------------------------
foreach (array_filter(files('src', '/\.json$/'), $isOurs) as $file) {
    $json = json_decode(read($file), true);

    if (!is_array($json)) {
        fail("$file: JSON inválido");
        continue;
    }

    if (basename($file) === 'joomla.asset.json') {
        if (($json['version'] ?? '') !== $version) {
            fail("$file: \"version\" diferente da do pacote ($version)");
        }

        // Cada asset declarado precisa existir em media/
        foreach ($json['assets'] ?? [] as $asset) {
            $uri = (string) ($asset['uri'] ?? '');

            if ($uri === '' || preg_match('~^https?://~', $uri)) {
                continue;
            }

            [, $name]  = explode('/', $uri, 2);
            $subfolder = str_ends_with($name, '.css') ? 'css' : 'js';

            if (!is_file(ROOT . '/' . dirname($file) . "/$subfolder/$name")) {
                fail("$file: o asset \"$uri\" não existe em " . dirname($file) . "/$subfolder/");
            }
        }
    }

    ok();
}

// ---------------------------------------------------------------------------------------------
// Resultado
// ---------------------------------------------------------------------------------------------
if ($errors) {
    fwrite(STDERR, implode("\n", array_map(static fn (string $e): string => "✘ $e", $errors)) . "\n\n" . count($errors) . " problema(s) encontrado(s).\n");
    exit(1);
}

fwrite(STDOUT, "✔ $checks verificações estáticas passaram (IDS Joomla EB $version).\n");
