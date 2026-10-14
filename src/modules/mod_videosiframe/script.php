<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  mod_videosiframe
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
use Joomla\Filesystem\Folder;

return new class () implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        $container->set(
            InstallerScriptInterface::class,
            new class ($container->get(DatabaseInterface::class)) implements InstallerScriptInterface {
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
                        $this->migrarVideosNumerados();
                        $this->removerArquivosAntigos();
                    }

                    return true;
                }

                /**
                 * Converte a configuração das versões 1.x/2.x ("Quantidade de vídeos" + campos
                 * Link 1..12) para o campo repetível "videos" da versão 3.1, sem perder nenhum vídeo.
                 */
                private function migrarVideosNumerados(): void
                {
                    try {
                        $db    = $this->db;
                        $query = $db->getQuery(true)
                            ->select($db->quoteName(['id', 'params']))
                            ->from($db->quoteName('#__modules'))
                            ->where($db->quoteName('module') . ' = ' . $db->quote('mod_videosiframe'));

                        foreach ($db->setQuery($query)->loadObjectList() as $modulo) {
                            $params = json_decode((string) $modulo->params, true);

                            if (!\is_array($params) || \array_key_exists('videos', $params) || !\array_key_exists('video-amount', $params)) {
                                continue;
                            }

                            $quantidade = min(12, max(0, (int) $params['video-amount']));
                            $videos     = [];

                            for ($i = 1; $i <= $quantidade; $i++) {
                                $url = trim((string) ($params['video-link-' . $i] ?? ''));

                                if ($url !== '') {
                                    $videos['videos' . \count($videos)] = ['url' => $url, 'titulo' => ''];
                                }
                            }

                            for ($i = 1; $i <= 12; $i++) {
                                unset($params['video-link-' . $i]);
                            }

                            unset($params['video-amount']);
                            $params['videos'] = $videos;

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
                        Log::add('mod_videosiframe: não foi possível converter os vídeos antigos: ' . $e->getMessage(), Log::WARNING, 'jerror');
                    }
                }

                /**
                 * As versões 1.x/2.x guardavam CSS e JS dentro da pasta do módulo.
                 */
                private function removerArquivosAntigos(): void
                {
                    $base = JPATH_ROOT . '/modules/mod_videosiframe';

                    try {
                        foreach (['/css', '/js'] as $pasta) {
                            if (is_dir($base . $pasta)) {
                                Folder::delete($base . $pasta);
                            }
                        }

                        if (is_file($base . '/mod_videosiframe.php')) {
                            File::delete($base . '/mod_videosiframe.php');
                        }
                    } catch (\Throwable $e) {
                        Log::add('mod_videosiframe: não foi possível remover arquivos antigos: ' . $e->getMessage(), Log::WARNING, 'jerror');
                    }
                }
            }
        );
    }
};
