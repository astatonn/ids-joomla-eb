<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  mod_instafeed
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerScriptInterface;
use Joomla\CMS\Log\Log;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;

return new class () implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        $container->set(
            InstallerScriptInterface::class,
            new class () implements InstallerScriptInterface {
                public function install(InstallerAdapter $adapter): bool
                {
                    return true;
                }

                public function update(InstallerAdapter $adapter): bool
                {
                    return true;
                }

                public function uninstall(InstallerAdapter $adapter): bool
                {
                    return true;
                }

                public function preflight(string $type, InstallerAdapter $adapter): bool
                {
                    return true;
                }

                /**
                 * As versões 1.x/2.x traziam, dentro da pasta do módulo, cópias de scripts de
                 * terceiros (inclusive um rastreador do serviço embedista.com) que não são mais usados.
                 */
                public function postflight(string $type, InstallerAdapter $adapter): bool
                {
                    if ($type === 'uninstall') {
                        return true;
                    }

                    $base = JPATH_ROOT . '/modules/mod_instafeed';

                    try {
                        if (is_dir($base . '/js')) {
                            Folder::delete($base . '/js');
                        }

                        if (is_file($base . '/mod_instafeed.php')) {
                            File::delete($base . '/mod_instafeed.php');
                        }
                    } catch (\Throwable $e) {
                        Log::add('mod_instafeed: não foi possível remover arquivos antigos: ' . $e->getMessage(), Log::WARNING, 'jerror');
                    }

                    return true;
                }
            }
        );
    }
};
