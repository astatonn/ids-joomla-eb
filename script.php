<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  pkg_ids_joomla_eb
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerScriptInterface;
use Joomla\CMS\Log\Log;
use Joomla\Database\DatabaseInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Filesystem\File;

return new class () implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        $container->set(
            InstallerScriptInterface::class,
            new class ($container->get(DatabaseInterface::class)) implements InstallerScriptInterface {
                private const MINIMUM_JOOMLA = '5.4.0';
                private const MINIMUM_PHP    = '8.1.0';

                /** Extensões instaladas pelo pacote: [tipo, elemento]. */
                private const FILHOS = [
                    ['template', 'idsgov-exrcitobrasileiro'],
                    ['component', 'com_aniversariantes'],
                    ['component', 'com_pagtesouro'],
                    ['module', 'mod_aniver'],
                    ['module', 'mod_imagempopup'],
                    ['module', 'mod_instafeed'],
                    ['module', 'mod_links'],
                    ['module', 'mod_readmorenews'],
                    ['module', 'mod_videosiframe'],
                ];

                /** Arquivos de idioma do pacote instalados pelas versões 1.x/2.x. */
                private const OBSOLETE_FILES = [
                    '/language/en-GB/en-GB.pkg_ids_joomla_eb.ini',
                    '/language/en-GB/en-GB.pkg_ids_joomla_eb.sys.ini',
                    '/language/pt-BR/pt-BR.pkg_ids_joomla_eb.ini',
                    '/language/pt-BR/pt-BR.pkg_ids_joomla_eb.sys.ini',
                    '/administrator/language/en-GB/en-GB.pkg_ids_joomla_eb.ini',
                    '/administrator/language/en-GB/en-GB.pkg_ids_joomla_eb.sys.ini',
                    '/administrator/language/pt-BR/pt-BR.pkg_ids_joomla_eb.ini',
                    '/administrator/language/pt-BR/pt-BR.pkg_ids_joomla_eb.sys.ini',
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

                /**
                 * Confere os requisitos ANTES de qualquer extensão do pacote ser instalada.
                 */
                public function preflight(string $type, InstallerAdapter $adapter): bool
                {
                    if ($type === 'uninstall') {
                        return true;
                    }

                    if (version_compare(PHP_VERSION, self::MINIMUM_PHP, '<')) {
                        $this->mensagem(
                            sprintf('O pacote IDS Joomla EB requer PHP %s ou superior. Este servidor usa PHP %s.', self::MINIMUM_PHP, PHP_VERSION),
                            'error'
                        );

                        return false;
                    }

                    if (version_compare(JVERSION, self::MINIMUM_JOOMLA, '<')) {
                        $this->mensagem(
                            sprintf(
                                'O pacote IDS Joomla EB requer Joomla %s ou superior. Este site usa Joomla %s: '
                                . 'atualize primeiro o Joomla para a versão 5.4 e depois instale o pacote.',
                                self::MINIMUM_JOOMLA,
                                JVERSION
                            ),
                            'error'
                        );

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
                    $this->unificarSitesDeAtualizacao();

                    $versao = (string) $adapter->getManifest()->version;

                    $this->mensagem(
                        '<h4>IDS Joomla EB ' . htmlspecialchars($versao, ENT_QUOTES, 'UTF-8') . ' '
                        . ($type === 'update' ? 'atualizado' : 'instalado') . ' com sucesso</h4>'
                        . '<ul>'
                        . '<li><strong>Template:</strong> em <em>Sistema → Estilos de templates do site</em>, defina '
                        . '<em>IDS Gov - Exército Brasileiro</em> como padrão. As opções estão organizadas em abas.</li>'
                        . '<li><strong>Componentes:</strong> Aniversariantes e PagTesouro, no menu <em>Componentes</em>.</li>'
                        . '<li><strong>Módulos:</strong> em <em>Conteúdo → Módulos do site → Novo</em>, todos começam com '
                        . '<em>IDS Gov</em>.</li>'
                        . '<li><strong>Atualizações:</strong> quando houver versão nova, o aviso aparece no painel inicial e em '
                        . '<em>Sistema → Atualizar → Extensões</em>.</li>'
                        . '</ul>',
                        'message'
                    );

                    return true;
                }

                private function removerArquivosObsoletos(): void
                {
                    foreach (self::OBSOLETE_FILES as $arquivo) {
                        if (is_file(JPATH_ROOT . $arquivo)) {
                            try {
                                File::delete(JPATH_ROOT . $arquivo);
                            } catch (\Throwable $e) {
                                Log::add('pkg_ids_joomla_eb: não foi possível remover ' . $arquivo . ': ' . $e->getMessage(), Log::WARNING, 'jerror');
                            }
                        }
                    }
                }

                /**
                 * Com o pacote instalado, basta UM aviso de atualização: o do próprio pacote, que
                 * atualiza tudo de uma vez. Cada extensão também declara um servidor de atualização
                 * próprio (útil para quem a instala avulsa); aqui eles são removidos para o
                 * administrador não ver dez avisos repetidos da mesma versão.
                 */
                private function unificarSitesDeAtualizacao(): void
                {
                    try {
                        $db  = $this->db;
                        $ids = [];

                        foreach (self::FILHOS as [$tipo, $elemento]) {
                            $query = $db->getQuery(true)
                                ->select($db->quoteName('s.update_site_id'))
                                ->from($db->quoteName('#__update_sites', 's'))
                                ->join('INNER', $db->quoteName('#__update_sites_extensions', 'x'), $db->quoteName('x.update_site_id') . ' = ' . $db->quoteName('s.update_site_id'))
                                ->join('INNER', $db->quoteName('#__extensions', 'e'), $db->quoteName('e.extension_id') . ' = ' . $db->quoteName('x.extension_id'))
                                ->where($db->quoteName('e.type') . ' = ' . $db->quote($tipo))
                                ->where($db->quoteName('e.element') . ' = ' . $db->quote($elemento))
                                ->where($db->quoteName('s.location') . ' LIKE ' . $db->quote('%/astatonn/ids-joomla-eb/%'));

                            $ids = array_merge($ids, $db->setQuery($query)->loadColumn() ?: []);
                        }

                        $ids = array_values(array_unique(array_map('intval', $ids)));

                        if (!$ids) {
                            return;
                        }

                        foreach (['#__updates', '#__update_sites_extensions', '#__update_sites'] as $tabela) {
                            $db->setQuery(
                                $db->getQuery(true)
                                    ->delete($db->quoteName($tabela))
                                    ->whereIn($db->quoteName('update_site_id'), $ids)
                            )->execute();
                        }
                    } catch (\Throwable $e) {
                        // Avisos duplicados são apenas um incômodo: nunca interrompem a instalação.
                        Log::add('pkg_ids_joomla_eb: não foi possível unificar os sites de atualização: ' . $e->getMessage(), Log::WARNING, 'jerror');
                    }
                }

                private function mensagem(string $texto, string $tipo): void
                {
                    Factory::getApplication()->enqueueMessage($texto, $tipo);
                }
            }
        );
    }
};
