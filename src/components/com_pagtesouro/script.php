<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_pagtesouro
 * @copyright   (C) 2025-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
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
                private const MINIMUM_JOOMLA = '5.4.0';
                private const MINIMUM_PHP    = '8.1.0';

                /**
                 * Arquivos da versão 1.x (estrutura do Joomla 3, sem namespace), que dependiam de
                 * classes como JHtml/JFactory e não funcionam no Joomla 6.
                 */
                private const OBSOLETE_FILES = [
                    '/administrator/components/com_pagtesouro/controller.php',
                    '/administrator/components/com_pagtesouro/pagtesouro.php',
                    '/components/com_pagtesouro/controller.php',
                    '/components/com_pagtesouro/pagtesouro.php',
                    '/administrator/components/com_pagtesouro/sql/updates/mysql/index.html',
                    // Idiomas instalados com o prefixo do idioma no nome do arquivo
                    '/administrator/language/en-GB/en-GB.com_pagtesouro.ini',
                    '/administrator/language/en-GB/en-GB.com_pagtesouro.sys.ini',
                    '/administrator/language/pt-BR/pt-BR.com_pagtesouro.ini',
                    '/administrator/language/pt-BR/pt-BR.com_pagtesouro.sys.ini',
                    '/language/en-GB/en-GB.com_pagtesouro.ini',
                    '/language/pt-BR/pt-BR.com_pagtesouro.ini',
                ];

                private const OBSOLETE_FOLDERS = [
                    '/administrator/components/com_pagtesouro/controllers',
                    '/administrator/components/com_pagtesouro/helpers',
                    '/administrator/components/com_pagtesouro/language',
                    '/administrator/components/com_pagtesouro/models',
                    '/administrator/components/com_pagtesouro/tables',
                    '/administrator/components/com_pagtesouro/views',
                    '/components/com_pagtesouro/controllers',
                    '/components/com_pagtesouro/language',
                    '/components/com_pagtesouro/models',
                    '/components/com_pagtesouro/views',
                ];

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
                    if ($type === 'uninstall') {
                        return true;
                    }

                    if (version_compare(PHP_VERSION, self::MINIMUM_PHP, '<')) {
                        $this->avisar(sprintf('O componente PagTesouro requer PHP %s ou superior (este servidor usa %s).', self::MINIMUM_PHP, PHP_VERSION));

                        return false;
                    }

                    if (version_compare(JVERSION, self::MINIMUM_JOOMLA, '<')) {
                        $this->avisar(sprintf('O componente PagTesouro requer Joomla %s ou superior (este site usa %s). Atualize o Joomla para a versão 5.4 antes de instalar.', self::MINIMUM_JOOMLA, JVERSION));

                        return false;
                    }

                    return true;
                }

                public function postflight(string $type, InstallerAdapter $adapter): bool
                {
                    if ($type === 'uninstall') {
                        return true;
                    }

                    foreach (self::OBSOLETE_FILES as $arquivo) {
                        if (is_file(JPATH_ROOT . $arquivo)) {
                            try {
                                File::delete(JPATH_ROOT . $arquivo);
                            } catch (\Throwable $e) {
                                $this->registrar('Não foi possível remover ' . $arquivo . ': ' . $e->getMessage());
                            }
                        }
                    }

                    foreach (self::OBSOLETE_FOLDERS as $pasta) {
                        if (is_dir(JPATH_ROOT . $pasta)) {
                            try {
                                Folder::delete(JPATH_ROOT . $pasta);
                            } catch (\Throwable $e) {
                                $this->registrar('Não foi possível remover ' . $pasta . ': ' . $e->getMessage());
                            }
                        }
                    }

                    return true;
                }

                private function avisar(string $mensagem): void
                {
                    Factory::getApplication()->enqueueMessage($mensagem, 'error');
                }

                private function registrar(string $mensagem): void
                {
                    Log::add('com_pagtesouro: ' . $mensagem, Log::WARNING, 'jerror');
                }
            }
        );
    }
};
