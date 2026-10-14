<?php

/**
 * IDS Joomla EB - infraestrutura dos testes de integração (Docker + HTTP).
 *
 * @copyright  (C) 2024-2026 Lucas Lima e colaboradores
 * @license    GNU General Public License version 2 or later
 */

declare(strict_types=1);

/**
 * Resultado acumulado das verificações.
 */
final class Report
{
    private static int $passed = 0;

    /** @var string[] */
    private static array $failures = [];

    private static string $context = '';

    public static function context(string $context): void
    {
        self::$context = $context;
        fwrite(STDOUT, "\n── $context\n");
    }

    public static function check(bool $condition, string $label, string $detail = ''): bool
    {
        if ($condition) {
            self::$passed++;
            fwrite(STDOUT, "   ✔ $label\n");
        } else {
            self::$failures[] = '[' . self::$context . '] ' . $label . ($detail !== '' ? "\n       $detail" : '');
            fwrite(STDOUT, "   ✘ $label" . ($detail !== '' ? "\n       " . $detail : '') . "\n");
        }

        return $condition;
    }

    public static function note(string $text): void
    {
        fwrite(STDOUT, "   · $text\n");
    }

    public static function finish(): int
    {
        fwrite(STDOUT, "\n" . str_repeat('═', 78) . "\n");

        if (self::$failures) {
            fwrite(STDOUT, count(self::$failures) . ' falha(s), ' . self::$passed . " verificação(ões) aprovada(s):\n");

            foreach (self::$failures as $failure) {
                fwrite(STDOUT, "  ✘ $failure\n");
            }

            return 1;
        }

        fwrite(STDOUT, '✔ ' . self::$passed . " verificações de integração aprovadas.\n");

        return 0;
    }
}

/**
 * Chamadas ao Docker. Não usa "bind mounts": os arquivos entram nos contêineres pela entrada padrão,
 * o que funciona com Docker local, remoto ou Docker Desktop.
 */
final class Docker
{
    public function __construct(private string $binary)
    {
    }

    /**
     * @return array{0: int, 1: string}  código de saída e saída (stdout + stderr)
     */
    public function run(array $args, ?string $stdinFile = null): array
    {
        $descriptors = [
            0 => $stdinFile !== null ? ['file', $stdinFile, 'r'] : ['file', '/dev/null', 'r'],
            1 => ['pipe', 'w'],
            2 => ['redirect', 1],
        ];

        $process = proc_open(array_merge([$this->binary], $args), $descriptors, $pipes);

        if (!is_resource($process)) {
            throw new RuntimeException('não foi possível executar o Docker (' . $this->binary . ')');
        }

        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        return [proc_close($process), (string) $output];
    }

    public function mustRun(array $args, ?string $stdinFile = null): string
    {
        [$code, $output] = $this->run($args, $stdinFile);

        if ($code !== 0) {
            throw new RuntimeException('docker ' . implode(' ', array_slice($args, 0, 4)) . "… falhou ($code):\n" . trim($output));
        }

        return $output;
    }

    /**
     * Log de erros do contêiner (stderr: Apache e PHP). A saída padrão, com o log de acessos, fica
     * de fora: misturar os dois fluxos embaralha a ordem das linhas.
     *
     * @return string[]
     */
    public function errorLog(string $container): array
    {
        $process = proc_open([$this->binary, 'logs', $container], [0 => ['file', '/dev/null', 'r'], 1 => ['file', '/dev/null', 'w'], 2 => ['pipe', 'w']], $pipes);

        if (!is_resource($process)) {
            throw new RuntimeException('não foi possível executar o Docker (' . $this->binary . ')');
        }

        $output = (string) stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        proc_close($process);

        return $output === '' ? [] : (preg_split('/\R/', rtrim($output)) ?: []);
    }

    public function exists(string $container): bool
    {
        [$code] = $this->run(['inspect', '--type', 'container', $container]);

        return $code === 0;
    }

    public function remove(string ...$containers): void
    {
        foreach ($containers as $container) {
            $this->run(['rm', '-f', '-v', $container]);
        }
    }
}

/**
 * Um site Joomla de teste em contêiner.
 */
final class Site
{
    public const ADMIN_USER = 'admin';
    public const ADMIN_PASS = 'Teste@IDS-EB-2026';
    public const DB_ROOT    = 'idsebroot';

    private string $cookies;
    private int $logMark = 0;

    public function __construct(
        private Docker $docker,
        public readonly string $name,
        public readonly string $image,
        public readonly int $port,
        public readonly string $database,
        private string $dbContainer,
        private string $network
    ) {
        $this->cookies = sys_get_temp_dir() . '/idseb-it-' . $name . '-' . getmypid() . '.cookies';
        @unlink($this->cookies);
    }

    // ------------------------------------------------------------------ ciclo de vida

    public function start(): void
    {
        $this->docker->remove($this->name);
        $this->docker->mustRun(['exec', $this->dbContainer, 'mysql', '-uroot', '-p' . self::DB_ROOT, '-e', "DROP DATABASE IF EXISTS `{$this->database}`; CREATE DATABASE `{$this->database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"]);

        $this->docker->mustRun([
            'run', '-d', '--name', $this->name, '--network', $this->network,
            '-p', '127.0.0.1:' . $this->port . ':80',
            '-e', 'JOOMLA_DB_HOST=' . $this->dbContainer,
            '-e', 'JOOMLA_DB_USER=root',
            '-e', 'JOOMLA_DB_PASSWORD=' . self::DB_ROOT,
            '-e', 'JOOMLA_DB_NAME=' . $this->database,
            '-e', 'JOOMLA_DB_PREFIX=jos_',
            '-e', 'JOOMLA_SITE_NAME=IDS EB Teste',
            '-e', 'JOOMLA_ADMIN_USER=Admin Teste',
            '-e', 'JOOMLA_ADMIN_USERNAME=' . self::ADMIN_USER,
            '-e', 'JOOMLA_ADMIN_PASSWORD=' . self::ADMIN_PASS,
            '-e', 'JOOMLA_ADMIN_EMAIL=admin@example.com',
            $this->image,
        ]);

        // A imagem oficial instala o Joomla sozinha; espera o painel responder.
        $deadline = time() + 240;

        while (time() < $deadline) {
            [$code] = $this->docker->run(['exec', $this->name, 'test', '-s', '/var/www/html/configuration.php']);

            if ($code === 0 && $this->http('GET', '/administrator/')['status'] === 200) {
                $this->mark();

                return;
            }

            sleep(3);
        }

        throw new RuntimeException("o site {$this->name} não ficou pronto a tempo:\n" . $this->docker->run(['logs', '--tail', '30', $this->name])[1]);
    }

    public function destroy(): void
    {
        $this->docker->remove($this->name);
        @unlink($this->cookies);
    }

    public function baseUrl(): string
    {
        return 'http://127.0.0.1:' . $this->port;
    }

    public function version(): string
    {
        return trim($this->exec('php -r \'define("_JEXEC",1); require "/var/www/html/libraries/src/Version.php"; echo (new Joomla\CMS\Version())->getShortVersion();\''));
    }

    // ------------------------------------------------------------------ comandos no contêiner

    public function exec(string $command, string $user = 'www-data', ?string $stdinFile = null): string
    {
        $args = ['exec'];

        if ($stdinFile !== null) {
            $args[] = '-i';
        }

        return $this->docker->mustRun(array_merge($args, ['-u', $user, '-w', '/var/www/html', $this->name, 'sh', '-c', $command]), $stdinFile);
    }

    /** @return array{0: int, 1: string} */
    public function tryExec(string $command, string $user = 'www-data'): array
    {
        return $this->docker->run(['exec', '-u', $user, '-w', '/var/www/html', $this->name, 'sh', '-c', $command]);
    }

    public function put(string $localFile, string $remotePath): void
    {
        $this->exec('cat > ' . escapeshellarg($remotePath) . ' && chmod 644 ' . escapeshellarg($remotePath), 'root', $localFile);
    }

    public function putString(string $content, string $remotePath): void
    {
        $tmp = tempnam(sys_get_temp_dir(), 'idseb');
        file_put_contents($tmp, $content);

        try {
            $this->put($tmp, $remotePath);
        } finally {
            unlink($tmp);
        }
    }

    /** Console do Joomla (php cli/joomla.php ...). @return array{0: int, 1: string} */
    public function cli(string $arguments): array
    {
        return $this->tryExec('php cli/joomla.php ' . $arguments . ' --no-interaction 2>&1');
    }

    /** Instala (ou atualiza) uma extensão a partir de um ZIP local. */
    public function install(string $localZip): string
    {
        $remote = '/tmp/' . basename($localZip);
        $this->put($localZip, $remote);

        [$code, $output] = $this->cli('extension:install --path=' . escapeshellarg($remote));

        if ($code !== 0 || !str_contains($output, 'installed successfully')) {
            throw new RuntimeException('a instalação de ' . basename($localZip) . " falhou:\n" . trim($output));
        }

        return $output;
    }

    // ------------------------------------------------------------------ banco de dados

    /** Executa SQL e devolve as linhas (colunas separadas por TAB). @return string[][] */
    public function sql(string $query): array
    {
        $tmp = tempnam(sys_get_temp_dir(), 'idseb');
        file_put_contents($tmp, $query);

        try {
            $output = $this->docker->mustRun(
                ['exec', '-i', $this->dbContainer, 'mysql', '-uroot', '-p' . self::DB_ROOT, '--default-character-set=utf8mb4', '-N', '-B', '--raw', $this->database],
                $tmp
            );
        } finally {
            unlink($tmp);
        }

        $rows = [];

        foreach (preg_split('/\R/', trim($output)) as $line) {
            if ($line === '' || str_contains($line, 'Using a password on the command line')) {
                continue;
            }

            $rows[] = explode("\t", $line);
        }

        return $rows;
    }

    public function value(string $query): ?string
    {
        return $this->sql($query)[0][0] ?? null;
    }

    public function snapshot(string $label): void
    {
        $this->exec('mkdir -p /snap && tar -C /var/www/html -cf /snap/' . $label . '.tar .', 'root');
        $this->docker->mustRun(['exec', $this->dbContainer, 'sh', '-c', 'mkdir -p /snap && mysqldump -uroot -p' . self::DB_ROOT . ' --single-transaction ' . $this->database . ' > /snap/' . $this->database . '-' . $label . '.sql 2>/dev/null']);
    }

    public function restore(string $label): void
    {
        $this->exec('find /var/www/html -mindepth 1 -delete && tar -C /var/www/html -xf /snap/' . $label . '.tar', 'root');
        $this->docker->mustRun(['exec', $this->dbContainer, 'sh', '-c', 'mysql -uroot -p' . self::DB_ROOT . ' -e "DROP DATABASE IF EXISTS \`' . $this->database . '\`; CREATE DATABASE \`' . $this->database . '\`" 2>/dev/null && mysql -uroot -p' . self::DB_ROOT . ' ' . $this->database . ' < /snap/' . $this->database . '-' . $label . '.sql 2>/dev/null']);
        @unlink($this->cookies);
        $this->mark();
    }

    // ------------------------------------------------------------------ HTTP

    /**
     * @return array{status: int, body: string, url: string}
     */
    public function http(string $method, string $path, array|string|null $data = null, array $headers = [], bool $follow = true): array
    {
        $curl = curl_init(str_starts_with($path, 'http') ? $path : $this->baseUrl() . $path);

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => $follow,
            CURLOPT_MAXREDIRS      => 8,
            CURLOPT_TIMEOUT        => 120,
            CURLOPT_COOKIEJAR      => $this->cookies,
            CURLOPT_COOKIEFILE     => $this->cookies,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_ENCODING       => '',
        ]);

        if ($data !== null) {
            $hasFile = is_array($data) && array_filter($data, static fn ($value): bool => $value instanceof CURLFile);

            // Com arquivo, o cURL monta o multipart/form-data a partir do array
            curl_setopt($curl, CURLOPT_POSTFIELDS, is_array($data) && !$hasFile ? http_build_query($data) : $data);
        }

        $body   = curl_exec($curl);
        $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $url    = (string) curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
        curl_close($curl);

        return ['status' => $status, 'body' => is_string($body) ? $body : '', 'url' => $url];
    }

    public function get(string $path): array
    {
        return $this->http('GET', $path);
    }

    public function adminLogin(): void
    {
        @unlink($this->cookies);
        $page = $this->get('/administrator/index.php');

        if (!preg_match('/name="([a-f0-9]{32})" value="1"/', $page['body'], $m)) {
            throw new RuntimeException("{$this->name}: não encontrei o token do formulário de login (HTTP {$page['status']})");
        }

        $result = $this->http('POST', '/administrator/index.php', [
            'username' => self::ADMIN_USER,
            'passwd'   => self::ADMIN_PASS,
            'option'   => 'com_login',
            'task'     => 'login',
            'return'   => base64_encode('index.php'),
            $m[1]      => '1',
        ]);

        if (str_contains($result['body'], 'name="passwd"')) {
            throw new RuntimeException("{$this->name}: login no administrador falhou");
        }
    }

    /** Token anti-CSRF da sessão de administrador. */
    public function token(): string
    {
        $page = $this->get('/administrator/index.php?option=com_cpanel');

        if (!preg_match('/"csrf\.token":"([a-f0-9]{32})"/', $page['body'], $m)) {
            throw new RuntimeException("{$this->name}: token CSRF não encontrado (sessão expirada?)");
        }

        return $m[1];
    }

    /** Envia um formulário do administrador (campos jform[...] + task). */
    public function adminPost(string $path, array $fields): array
    {
        $fields[$this->token()] = '1';

        return $this->http('POST', '/administrator/' . ltrim($path, '/'), $fields);
    }

    /** API REST do Joomla (autenticação Basic). @return array{status: int, json: mixed, body: string} */
    public function api(string $method, string $path, ?array $payload = null): array
    {
        $headers = [
            'Authorization: Basic ' . base64_encode(self::ADMIN_USER . ':' . self::ADMIN_PASS),
            'Accept: application/vnd.api+json',
        ];

        if ($payload !== null) {
            $headers[] = 'Content-Type: application/json';
        }

        $response = $this->http($method, '/api/index.php/v1/' . ltrim($path, '/'), $payload !== null ? json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null, $headers);

        return ['status' => $response['status'], 'json' => json_decode($response['body'], true), 'body' => $response['body']];
    }

    // ------------------------------------------------------------------ erros de PHP

    /** Liga a exibição e o registro de TODOS os erros (inclusive depreciações). */
    public function strictErrors(): void
    {
        $this->exec("sed -i \"s/public \\\$error_reporting = '[^']*'/public \\\$error_reporting = 'maximum'/\" configuration.php", 'root');
        $this->putString("error_reporting = E_ALL\ndisplay_errors = On\nlog_errors = On\nerror_log = /dev/stderr\n", '/usr/local/etc/php/conf.d/zz-idseb-tests.ini');
        $this->docker->mustRun(['exec', $this->name, 'apachectl', '-k', 'graceful']);
        sleep(2);
        $this->mark();
    }

    /**
     * Marca o ponto do log a partir do qual phpErrors() passa a olhar.
     * Conta linhas em vez de usar horários: o relógio do Docker pode diferir do da máquina.
     */
    public function mark(): void
    {
        $this->logMark = count($this->logLines());
    }

    /** @return string[] */
    private function logLines(): array
    {
        return $this->docker->errorLog($this->name);
    }

    /**
     * Erros/avisos de PHP registrados desde a última marca.
     *
     * @return string[]
     */
    public function phpErrors(): array
    {
        $errors = [];

        foreach (array_slice($this->logLines(), $this->logMark) as $line) {
            if (preg_match('/PHP (Fatal error|Parse error|Warning|Notice|Deprecated|Recoverable)/', $line)) {
                $errors[] = trim(preg_replace('/^\[[^\]]+\] \[php:[a-z]+\] \[pid [^\]]+\] (\[client [^\]]+\] )?/', '', $line));
            }
        }

        return array_values(array_unique($errors));
    }
}

/**
 * Procura, no HTML de uma página, sinais de erro de PHP exibidos na tela.
 *
 * @return string[]
 */
function errorsInBody(string $body): array
{
    $found = [];

    if (preg_match_all('~<b>(Fatal error|Warning|Notice|Deprecated|Parse error)</b>:\s*(.{0,240})~s', $body, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $m) {
            $found[] = $m[1] . ': ' . trim(strip_tags($m[2]));
        }
    }

    // Página de erro do Joomla (exceção não tratada)
    if (preg_match('~(Call to undefined|not found in |Uncaught |Whoops, looks like something went wrong|An error has occurred\.)~', $body, $m)) {
        $found[] = 'página de erro: ' . $m[1];
    }

    return $found;
}

/**
 * O texto aparece na página? O administrador do Joomla grava acentos ora como UTF-8, ora como
 * entidades HTML (&iacute;), então a busca considera as duas formas.
 */
function has(string $html, string $needle): bool
{
    return str_contains($html, $needle) || str_contains(html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8'), $needle);
}

/** Baixa um arquivo para o cache local (uma vez só). */
function cached(string $url, string $cacheDir): string
{
    $target = $cacheDir . '/' . basename(parse_url($url, PHP_URL_PATH));

    if (is_file($target) && filesize($target) > 1000) {
        return $target;
    }

    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0777, true);
    }

    $handle = fopen($target . '.part', 'w');
    $curl   = curl_init($url);
    curl_setopt_array($curl, [CURLOPT_FILE => $handle, CURLOPT_FOLLOWLOCATION => true, CURLOPT_TIMEOUT => 600, CURLOPT_FAILONERROR => true]);
    $ok     = curl_exec($curl);
    $error  = curl_error($curl);
    curl_close($curl);
    fclose($handle);

    if (!$ok) {
        @unlink($target . '.part');

        throw new RuntimeException("não foi possível baixar $url: $error");
    }

    rename($target . '.part', $target);

    return $target;
}

/**
 * Formulário HTML lido de uma página, para reenviá-lo como um navegador faria.
 */
final class HtmlForm
{
    /** @var array<int, array{0: string, 1: string}> pares [nome, valor], na ordem do documento */
    public array $fields = [];

    public string $action = '';

    /**
     * Localiza o formulário pelo atributo id e coleta os campos que um navegador enviaria.
     */
    public static function fromHtml(string $html, string $formId): ?self
    {
        $document = new DOMDocument();
        @$document->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_NOERROR | LIBXML_NOWARNING);

        $xpath = new DOMXPath($document);
        $node  = $xpath->query('//form[@id="' . $formId . '"]')->item(0);

        if (!$node instanceof DOMElement) {
            return null;
        }

        $form         = new self();
        $form->action = html_entity_decode($node->getAttribute('action'), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Linhas-modelo de subformulários ficam dentro de <template> e não são enviadas pelo navegador
        $controls = $xpath->query('.//*[(self::input or self::select or self::textarea) and @name and not(ancestor::template)]', $node);

        foreach ($controls as $control) {
            /** @var DOMElement $control */
            if ($control->hasAttribute('disabled')) {
                continue;
            }

            $name = $control->getAttribute('name');

            switch ($control->tagName) {
                case 'input':
                    $type = strtolower($control->getAttribute('type') ?: 'text');

                    if (in_array($type, ['submit', 'button', 'image', 'file', 'reset'], true)) {
                        break;
                    }

                    if (in_array($type, ['checkbox', 'radio'], true) && !$control->hasAttribute('checked')) {
                        break;
                    }

                    $form->fields[] = [$name, $control->hasAttribute('value') ? $control->getAttribute('value') : ($type === 'checkbox' || $type === 'radio' ? 'on' : '')];
                    break;

                case 'textarea':
                    $form->fields[] = [$name, $control->textContent];
                    break;

                case 'select':
                    $options  = $xpath->query('.//option', $control);
                    $selected = [];

                    foreach ($options as $option) {
                        /** @var DOMElement $option */
                        if ($option->hasAttribute('selected')) {
                            $selected[] = $option->hasAttribute('value') ? $option->getAttribute('value') : $option->textContent;
                        }
                    }

                    if (!$selected && !$control->hasAttribute('multiple') && $options->length > 0) {
                        /** @var DOMElement $first */
                        $first      = $options->item(0);
                        $selected[] = $first->hasAttribute('value') ? $first->getAttribute('value') : $first->textContent;
                    }

                    foreach ($selected as $value) {
                        $form->fields[] = [$name, $value];
                    }

                    break;
            }
        }

        return $form;
    }

    public function has(string $name): bool
    {
        foreach ($this->fields as [$field]) {
            if ($field === $name) {
                return true;
            }
        }

        return false;
    }

    public function get(string $name): ?string
    {
        foreach ($this->fields as [$field, $value]) {
            if ($field === $name) {
                return $value;
            }
        }

        return null;
    }

    /** Define (substituindo) o valor de um campo. */
    public function set(string $name, string $value): self
    {
        $this->fields = array_values(array_filter($this->fields, static fn (array $pair): bool => $pair[0] !== $name));
        $this->fields[] = [$name, $value];

        return $this;
    }

    /** Nomes dos campos, sem repetição. @return string[] */
    public function names(): array
    {
        return array_values(array_unique(array_column($this->fields, 0)));
    }

    public function body(): string
    {
        return implode('&', array_map(static fn (array $pair): string => rawurlencode($pair[0]) . '=' . rawurlencode($pair[1]), $this->fields));
    }
}

/**
 * Mensagens do sistema exibidas pelo Joomla em uma página: tipo => textos.
 *
 * @return array<string, string[]>
 */
function joomlaMessages(string $html): array
{
    $messages = [];

    if (preg_match_all('~<script type="application/json" class="joomla-script-options[^"]*"[^>]*>(.*?)</script>~s', $html, $blocks)) {
        foreach ($blocks[1] as $block) {
            $options = json_decode($block, true);

            foreach ($options['joomla.messages'] ?? [] as $group) {
                foreach ($group as $type => $texts) {
                    foreach ((array) $texts as $text) {
                        $messages[$type][] = trim(preg_replace('/\s+/', ' ', strip_tags((string) $text)));
                    }
                }
            }
        }
    }

    return $messages;
}

/** Mensagens de erro (danger/error/warning) de uma página, em uma linha. */
function joomlaErrors(string $html): string
{
    $messages = joomlaMessages($html);
    $errors   = array_merge($messages['danger'] ?? [], $messages['error'] ?? [], $messages['warning'] ?? []);

    return implode(' | ', $errors);
}

/**
 * Somente as mensagens de ERRO de uma página (sem os avisos informativos que o Joomla sempre
 * mostra, como o lembrete de backup da tela de atualizações).
 */
function joomlaFailures(string $html): string
{
    $messages = joomlaMessages($html);

    return implode(' | ', array_merge($messages['danger'] ?? [], $messages['error'] ?? []));
}

/** Valor literal para SQL (MySQL). */
function q(string $value): string
{
    return "'" . str_replace(['\\', "'"], ['\\\\', "\\'"], $value) . "'";
}
