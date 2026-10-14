<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  mod_imagempopup
 * @copyright   (C) 2024-2026 Henrique Michels Voicolesco, Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerScriptInterface;
use Joomla\CMS\Log\Log;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Filesystem\File;

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
                 * Nas versões 1.x/2.x o módulo inteiro ficava em mod_imagempopup.php. O Joomla não apaga esse
                 * arquivo ao atualizar, e ele não tem mais função (nem proteção contra acesso direto).
                 */
                public function postflight(string $type, InstallerAdapter $adapter): bool
                {
                    $antigo = JPATH_ROOT . '/modules/mod_imagempopup/mod_imagempopup.php';

                    if ($type !== 'uninstall' && is_file($antigo)) {
                        try {
                            File::delete($antigo);
                        } catch (\Throwable $e) {
                            Log::add('mod_imagempopup: não foi possível remover o arquivo antigo: ' . $e->getMessage(), Log::WARNING, 'jerror');
                        }
                    }

                    return true;
                }
            }
        );
    }
};
