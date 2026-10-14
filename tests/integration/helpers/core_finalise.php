<?php

/**
 * IDS Joomla EB - auxiliar dos testes de integração.
 *
 * Executa a etapa final de uma atualização do núcleo do Joomla depois que os arquivos novos já
 * foram copiados: migração do banco, script de pós-atualização e cache dos manifestos. É o mesmo
 * que o componente Joomla Update faz; o arranque abaixo espelha o de cli/joomla.php.
 *
 * Uso (dentro do contêiner, como o usuário do servidor web): php core_finalise.php
 *
 * @copyright  (C) 2024-2026 Lucas Lima e colaboradores
 * @license    GNU General Public License version 2 or later
 */

const _JEXEC = 1;

// O console não tem requisição HTTP: dá às classes de URI um host válido (cli/joomla.php faz o mesmo).
$_SERVER['HTTP_HOST']   = 'localhost';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF']    = '/index.php';
$_SERVER['REQUEST_URI'] = '/';

define('JPATH_BASE', '/var/www/html');
require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_BASE . '/includes/framework.php';

$container = \Joomla\CMS\Factory::getContainer();
$container->alias('session', 'session.cli')
    ->alias('JSession', 'session.cli')
    ->alias(\Joomla\CMS\Session\Session::class, 'session.cli')
    ->alias(\Joomla\Session\Session::class, 'session.cli')
    ->alias(\Joomla\Session\SessionInterface::class, 'session.cli');

$app = $container->get(\Joomla\Console\Application::class);
\Joomla\CMS\Factory::$application = $app;
$app->getLanguage()->load('lib_joomla', JPATH_ADMINISTRATOR);

// Normalmente feito por ConsoleApplication::execute(): registra os namespaces PSR-4 das extensões.
$app->createExtensionNamespaceMap();

// Igual a Joomla\CMS\Console\UpdateCoreCommand::setUpdateModel()
\defined('JPATH_COMPONENT_ADMINISTRATOR') || \define('JPATH_COMPONENT_ADMINISTRATOR', JPATH_ADMINISTRATOR . '/components/com_joomlaupdate');
$model = $app->bootComponent('com_joomlaupdate')->getMVCFactory($app)->createModel('Update', 'Administrator');

if (!$model) {
    fwrite(STDERR, 'Could not create the update model' . PHP_EOL);
    exit(2);
}

$ok = $model->finaliseUpgrade();

echo 'finaliseUpgrade: ' . ($ok ? 'OK' : 'FAILED') . PHP_EOL;
echo 'Joomla version now: ' . (new \Joomla\CMS\Version())->getShortVersion() . PHP_EOL;

exit($ok ? 0 : 1);
