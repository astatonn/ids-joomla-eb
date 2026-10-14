<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_aniversariantes
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerScriptInterface;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\Database\DatabaseInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;

return new class () implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        $container->set(
            InstallerScriptInterface::class,
            new class ($container->get(DatabaseInterface::class)) implements InstallerScriptInterface {
                private const MINIMUM_JOOMLA = '5.4.0';
                private const MINIMUM_PHP    = '8.1.0';

                /**
                 * Arquivos das versões 1.x/2.x (geradas pelo Component Creator) que não existem mais.
                 * Alguns usavam classes removidas no Joomla 6 e precisam sair do servidor.
                 */
                private const OBSOLETE_FILES = [
                    '/administrator/components/com_aniversariantes/sql/updates/1.0.0.sql',
                    '/administrator/components/com_aniversariantes/sql/index.html',
                    '/administrator/components/com_aniversariantes/tmpl/index.html',
                    '/administrator/components/com_aniversariantes/tmpl/aniversariante/default.php',
                    '/components/com_aniversariantes/tmpl/index.html',
                    '/components/com_aniversariantes/src/Controller/AniversarianteController.php',
                    '/components/com_aniversariantes/src/Controller/AniversariantesController.php',
                    '/components/com_aniversariantes/src/Model/AniversarianteModel.php',
                    '/components/com_aniversariantes/src/Service/Category.php',
                    '/media/com_aniversariantes/css/admin.css',
                    '/media/com_aniversariantes/css/list.css',
                    '/media/com_aniversariantes/css/index.html',
                ];

                private const OBSOLETE_FOLDERS = [
                    '/administrator/components/com_aniversariantes/presets',
                    '/administrator/components/com_aniversariantes/sql/xml',
                    '/administrator/components/com_aniversariantes/src/Field',
                    '/administrator/components/com_aniversariantes/src/Helper',
                    '/administrator/components/com_aniversariantes/src/Service',
                    '/components/com_aniversariantes/forms',
                    '/components/com_aniversariantes/src/Field',
                    '/components/com_aniversariantes/src/Helper',
                    '/media/com_aniversariantes/js',
                ];

                private DatabaseInterface $db;

                public function __construct(DatabaseInterface $db)
                {
                    $this->db = $db;
                }

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
                        $this->avisar(sprintf('O componente Aniversariantes requer PHP %s ou superior (este servidor usa %s).', self::MINIMUM_PHP, PHP_VERSION));

                        return false;
                    }

                    if (version_compare(JVERSION, self::MINIMUM_JOOMLA, '<')) {
                        $this->avisar(sprintf('O componente Aniversariantes requer Joomla %s ou superior (este site usa %s). Atualize o Joomla para a versão 5.4 antes de instalar.', self::MINIMUM_JOOMLA, JVERSION));

                        return false;
                    }

                    return true;
                }

                public function postflight(string $type, InstallerAdapter $adapter): bool
                {
                    if ($type === 'uninstall') {
                        return true;
                    }

                    $this->removerArquivosObsoletos();
                    $this->removerSiteDeAtualizacaoAntigo();

                    return true;
                }

                private function removerArquivosObsoletos(): void
                {
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
                }

                /**
                 * As versões 1.x registravam um servidor de atualização do Component Creator,
                 * que nunca ofereceu atualizações deste componente.
                 */
                private function removerSiteDeAtualizacaoAntigo(): void
                {
                    try {
                        $db    = $this->db;
                        $query = $db->getQuery(true)
                            ->select($db->quoteName('update_site_id'))
                            ->from($db->quoteName('#__update_sites'))
                            ->where($db->quoteName('location') . ' LIKE ' . $db->quote('%component-creator.com%'))
                            ->where($db->quoteName('name') . ' = ' . $db->quote('com_aniversariantes'));
                        $ids = array_map('intval', $db->setQuery($query)->loadColumn() ?: []);

                        if (!$ids) {
                            return;
                        }

                        foreach (['#__update_sites_extensions', '#__update_sites', '#__updates'] as $tabela) {
                            $db->setQuery(
                                $db->getQuery(true)
                                    ->delete($db->quoteName($tabela))
                                    ->whereIn($db->quoteName('update_site_id'), $ids)
                            )->execute();
                        }
                    } catch (\Throwable $e) {
                        $this->registrar('Não foi possível remover o site de atualização antigo: ' . $e->getMessage());
                    }
                }

                private function avisar(string $mensagem): void
                {
                    Factory::getApplication()->enqueueMessage($mensagem, 'error');
                }

                private function registrar(string $mensagem): void
                {
                    Log::add('com_aniversariantes: ' . $mensagem, Log::WARNING, 'jerror');
                }
            }
        );
    }
};
