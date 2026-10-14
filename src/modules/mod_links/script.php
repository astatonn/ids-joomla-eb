<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  mod_links
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerScriptInterface;
use Joomla\CMS\Log\Log;
use Joomla\Database\DatabaseInterface;
use Joomla\Database\ParameterType;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Filesystem\File;

return new class () implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        $container->set(
            InstallerScriptInterface::class,
            new class ($container->get(DatabaseInterface::class)) implements InstallerScriptInterface {
                /** Ícones oferecidos na lista do formulário (os demais viram "Outro ícone"). */
                private const ICONES_DA_LISTA = [
                    'fas fa-link', 'fas fa-globe', 'fas fa-home', 'fas fa-star', 'fas fa-info-circle', 'fas fa-search',
                    'fas fa-cog', 'fas fa-lock', 'fas fa-envelope', 'fas fa-phone', 'fas fa-comments', 'fas fa-bullhorn',
                    'fas fa-newspaper', 'fas fa-video', 'fas fa-camera', 'fas fa-file-alt', 'fas fa-file-pdf',
                    'fas fa-download', 'fas fa-book', 'fas fa-clipboard-list', 'fas fa-calendar-alt',
                    'fas fa-balance-scale', 'fas fa-chart-bar', 'fas fa-user', 'fas fa-users', 'fas fa-id-card',
                    'fas fa-graduation-cap', 'fas fa-medal', 'fas fa-heartbeat', 'fas fa-hands-helping',
                    'fas fa-shield-alt', 'fas fa-flag', 'fas fa-map-marker-alt', 'fas fa-building',
                    'fas fa-money-bill-wave', 'fas fa-tools', 'fas fa-desktop', 'fas fa-external-link-alt',
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
                    return true;
                }

                public function postflight(string $type, InstallerAdapter $adapter): bool
                {
                    if ($type !== 'uninstall') {
                        $this->migrarLinksNumerados();
                        $this->removerArquivoAntigo();
                    }

                    return true;
                }

                /**
                 * Nas versões 1.x/2.x o módulo inteiro ficava em mod_links.php. O Joomla não apaga esse
                 * arquivo ao atualizar, e ele não tem mais função (nem proteção contra acesso direto).
                 */
                private function removerArquivoAntigo(): void
                {
                    $antigo = JPATH_ROOT . '/modules/mod_links/mod_links.php';

                    if (is_file($antigo)) {
                        try {
                            File::delete($antigo);
                        } catch (\Throwable $e) {
                            Log::add('mod_links: não foi possível remover o arquivo antigo: ' . $e->getMessage(), Log::WARNING, 'jerror');
                        }
                    }
                }

                /**
                 * Converte a configuração das versões 1.x/2.x ("Quantidade de links" + campos
                 * Link 1..12) para o campo repetível "links" da versão 3.1, sem perder nenhum link.
                 */
                private function migrarLinksNumerados(): void
                {
                    try {
                        $db    = $this->db;
                        $query = $db->getQuery(true)
                            ->select($db->quoteName(['id', 'params']))
                            ->from($db->quoteName('#__modules'))
                            ->where($db->quoteName('module') . ' = ' . $db->quote('mod_links'));

                        foreach ($db->setQuery($query)->loadObjectList() as $modulo) {
                            $params = json_decode((string) $modulo->params, true);

                            if (!\is_array($params) || \array_key_exists('links', $params) || !\array_key_exists('link-amount', $params)) {
                                continue;
                            }

                            $quantidade = min(12, max(0, (int) $params['link-amount']));
                            $links      = [];

                            for ($i = 1; $i <= $quantidade; $i++) {
                                $nome  = trim((string) ($params['name-link-' . $i] ?? ''));
                                $url   = trim((string) ($params['url-link-' . $i] ?? ''));
                                $icone = trim((string) ($params['icon-link-' . $i] ?? ''));

                                if ($nome === '' && $url === '') {
                                    continue;
                                }

                                $naLista = \in_array($icone, self::ICONES_DA_LISTA, true);

                                $links['links' . \count($links)] = [
                                    'nome'         => $nome,
                                    'url'          => $url,
                                    // sem ícone → "none"; ícone da lista → o próprio; qualquer outro → "custom"
                                    'icone'        => $icone === '' ? 'none' : ($naLista ? $icone : 'custom'),
                                    'icone_custom' => $icone === '' || $naLista ? '' : $icone,
                                    'nova_aba'     => 1,
                                ];
                            }

                            for ($i = 1; $i <= 12; $i++) {
                                unset($params['name-link-' . $i], $params['url-link-' . $i], $params['icon-link-' . $i]);
                            }

                            unset($params['link-amount']);
                            $params['links'] = $links;

                            $json = json_encode($params, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                            $id   = (int) $modulo->id;

                            $db->setQuery(
                                $db->getQuery(true)
                                    ->update($db->quoteName('#__modules'))
                                    ->set($db->quoteName('params') . ' = :params')
                                    ->where($db->quoteName('id') . ' = :id')
                                    ->bind(':params', $json)
                                    ->bind(':id', $id, ParameterType::INTEGER)
                            )->execute();
                        }
                    } catch (\Throwable $e) {
                        // O módulo continua lendo os campos antigos se a conversão não for possível.
                        Log::add('mod_links: não foi possível converter os links antigos: ' . $e->getMessage(), Log::WARNING, 'jerror');
                    }
                }
            }
        );
    }
};
