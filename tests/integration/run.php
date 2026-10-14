#!/usr/bin/env php
<?php

/**
 * IDS Joomla EB - testes de integração.
 *
 * Sobe sites Joomla reais em contêineres Docker, instala o pacote gerado em dist/ e confere
 * instalação, atualização, telas do administrador, páginas do site e avisos de atualização.
 *
 *   php build/build.php                      # gera dist/pkg_ids_joomla_eb.zip
 *   php tests/integration/run.php            # roda todos os cenários
 *   php tests/integration/run.php --only=limpa-6.0.1,recuperacao
 *   php tests/integration/run.php --keep     # mantém os contêineres para inspeção
 *
 * Opções:
 *   --only=a,b        cenários a rodar (veja a lista com --list)
 *   --list            lista os cenários
 *   --keep            não remove os contêineres ao final
 *   --docker=CAMINHO  executável do Docker (ou variável de ambiente DOCKER; padrão: docker)
 *   --port=18700      primeira porta local usada pelos sites de teste
 *   --joomla6=6.2.0   versão do Joomla 6 para a qual os sites são atualizados nos cenários "atual"
 *   --joomla5=5.4.10  idem para o Joomla 5.4
 *
 * Requisitos: Docker, PHP 8.1+ com cURL e acesso à internet (imagens do Docker Hub e pacotes do
 * Joomla no GitHub, guardados em cache na pasta temporária do sistema).
 *
 * @copyright  (C) 2024-2026 Lucas Lima e colaboradores
 * @license    GNU General Public License version 2 or later
 */

declare(strict_types=1);

require __DIR__ . '/lib.php';
require __DIR__ . '/scenarios.php';

const ROOT = __DIR__ . '/../..';

$options = getopt('', ['only:', 'list', 'keep', 'docker:', 'port:', 'joomla6:', 'joomla5:', 'help']);

if (isset($options['help'])) {
    fwrite(STDOUT, implode("\n", array_slice(file(__FILE__, FILE_IGNORE_NEW_LINES), 3, 23)) . "\n");
    exit(0);
}

$joomla6 = (string) ($options['joomla6'] ?? '6.2.0');
$joomla5 = (string) ($options['joomla5'] ?? '5.4.10');

/**
 * Cenários: nome => [descrição, imagem Docker, função].
 * As imagens oficiais fixam a versão inicial; as versões "atuais" são alcançadas atualizando o
 * núcleo dentro do contêiner, exatamente como um site real faria.
 */
$scenarios = [
    'limpa-5.4.1' => ['Instalação limpa no Joomla 5.4.1 em inglês (idioma de fallback)', 'joomla:5.4.1-php8.3-apache', static fn (Context $c) => scenarioFresh($c, null)],
    'limpa-5.4.1-php8.2' => ['Instalação limpa no Joomla 5.4.1 com PHP 8.2', 'joomla:5.4.1-php8.2-apache', static fn (Context $c) => scenarioFresh($c, null)],
    'limpa-6.0.1' => ['Instalação limpa no Joomla 6.0.1 em pt-BR (versão do relato do erro)', 'joomla:6.0.1-php8.3-apache', static fn (Context $c) => scenarioFresh($c, null, true)],
    'limpa-5-atual' => ["Instalação limpa no Joomla $joomla5 em pt-BR", 'joomla:5.4.1-php8.3-apache', static fn (Context $c) => scenarioFresh($c, $joomla5, true)],
    'limpa-6-atual' => ["Instalação limpa no Joomla $joomla6 em pt-BR", 'joomla:6.0.1-php8.3-apache', static fn (Context $c) => scenarioFresh($c, $joomla6, true)],
    'atualizacao' => ["Pacote 1.0.0 no Joomla 5.4.1 → 3.x → Joomla 6.0.1 → Joomla $joomla6", 'joomla:5.4.1-php8.3-apache', static fn (Context $c) => scenarioUpgrade($c, '6.0.1', $joomla6)],
    'recuperacao' => ['Site quebrado (Joomla 6.0.1 + pacote 1.0.0) recuperado pelo painel', 'joomla:5.4.1-php8.3-apache', static fn (Context $c) => scenarioRecovery($c, '6.0.1')],
    'avisos' => ['Avisos de atualização dentro do Joomla (pacote e extensão avulsa)', 'joomla:5.4.1-php8.3-apache', static fn (Context $c) => scenarioUpdateNotices($c, '6.0.1')],
];

if (isset($options['list'])) {
    foreach ($scenarios as $name => [$description]) {
        fwrite(STDOUT, sprintf("%-16s %s\n", $name, $description));
    }

    exit(0);
}

$only = isset($options['only']) ? array_filter(array_map('trim', explode(',', (string) $options['only']))) : array_keys($scenarios);

foreach ($only as $name) {
    if (!isset($scenarios[$name])) {
        fwrite(STDERR, "Cenário desconhecido: $name (use --list)\n");
        exit(2);
    }
}

$package = ROOT . '/dist/pkg_ids_joomla_eb.zip';

if (!is_file($package)) {
    fwrite(STDERR, "dist/pkg_ids_joomla_eb.zip não existe. Rode antes: php build/build.php\n");
    exit(2);
}

$docker   = new Docker((string) ($options['docker'] ?? getenv('DOCKER') ?: 'docker'));
$network  = 'idseb-it';
$dbName   = 'idseb-it-db';
$port     = (int) ($options['port'] ?? 18700);
$cacheDir = sys_get_temp_dir() . '/idseb-it-cache';
$created  = [];
$exitCode = 1;

try {
    $docker->mustRun(['version', '--format', '{{.Server.Version}}']);
    $docker->run(['network', 'create', $network]);

    // Banco de dados compartilhado pelos sites de teste
    $docker->remove($dbName);
    $docker->mustRun(['run', '-d', '--name', $dbName, '--network', $network, '-e', 'MYSQL_ROOT_PASSWORD=' . Site::DB_ROOT, 'mysql:8.0', '--character-set-server=utf8mb4', '--collation-server=utf8mb4_unicode_ci']);
    $created[] = $dbName;

    $deadline = time() + 180;

    while (true) {
        [$code] = $docker->run(['exec', $dbName, 'mysql', '-uroot', '-p' . Site::DB_ROOT, '-e', 'SELECT 1']);

        if ($code === 0) {
            break;
        }

        if (time() > $deadline) {
            throw new RuntimeException('o MySQL de teste não ficou pronto');
        }

        sleep(3);
    }

    foreach ($only as $name) {
        [$description, $image, $callback] = $scenarios[$name];

        // Porta fixa por cenário: contêineres mantidos com --keep não colidem com rodadas seguintes
        $index = (int) array_search($name, array_keys($scenarios), true);

        fwrite(STDOUT, "\n" . str_repeat('═', 78) . "\nCENÁRIO $name: $description\n" . str_repeat('═', 78) . "\n");

        $container = 'idseb-it-' . preg_replace('/[^a-z0-9]+/', '-', $name);
        $site      = new Site($docker, $container, $image, $port + $index, 'it_' . preg_replace('/[^a-z0-9]+/', '_', $name), $dbName, $network);
        $created[] = $container;
        $context   = new Context($docker, $site, $network, $package, $cacheDir, ROOT);

        try {
            $site->start();
            $callback($context);
        } catch (Throwable $e) {
            Report::check(false, 'cenário interrompido por erro', get_class($e) . ': ' . $e->getMessage() . ' (' . basename($e->getFile()) . ':' . $e->getLine() . ')');
        }

        $created = array_merge($created, $context->extraContainers);

        if (!isset($options['keep'])) {
            $docker->remove($container, ...$context->extraContainers);
        }
    }

    $exitCode = Report::finish();
} catch (Throwable $e) {
    fwrite(STDERR, "\nERRO: " . $e->getMessage() . "\n");
    $exitCode = 2;
} finally {
    if (!isset($options['keep'])) {
        $docker->remove(...array_unique($created));
        $docker->run(['network', 'rm', $network]);
    } else {
        fwrite(STDOUT, "\nContêineres mantidos (--keep): " . implode(', ', array_unique($created)) . "\n");
    }
}

exit($exitCode);
