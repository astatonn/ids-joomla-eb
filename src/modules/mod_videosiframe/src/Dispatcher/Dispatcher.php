<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  mod_videosiframe
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

namespace Astatonn\Module\Videosiframe\Site\Dispatcher;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\Registry\Registry;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Módulo "Vídeos": carrossel de vídeos do YouTube.
 *
 * @since  3.1.0
 */
class Dispatcher extends AbstractModuleDispatcher
{
    /**
     * @return  array
     *
     * @since   3.1.0
     */
    protected function getLayoutData(): array
    {
        $data = parent::getLayoutData();

        /** @var Registry $params */
        $params = $data['params'];

        $data['titulo']    = trim((string) $params->get('titulo', 'Vídeos'));
        $data['videos']    = $this->obterVideos($params);
        $data['largura']   = min(1280, max(200, (int) $params->get('video-width', 560)));
        $data['altura']    = min(720, max(120, (int) $params->get('video-height', 315)));
        $data['movimento'] = (int) $params->get('movimento', 1) === 1 && \count($data['videos']) > 1;
        $data['duracao']   = \count($data['videos']) * min(60, max(2, (int) $params->get('segundos_por_video', 5)));

        return $data;
    }

    /**
     * Vídeos configurados, já com o identificador do YouTube extraído.
     *
     * Lê o campo repetível "videos" (3.1+). Se ele ainda não existir, usa os campos numerados
     * das versões 1.x/2.x (video-link-1 ... video-link-12).
     *
     * @param   Registry  $params  Parâmetros do módulo
     *
     * @return  array<int, array{id: string, titulo: string}>
     *
     * @since   3.1.0
     */
    private function obterVideos(Registry $params): array
    {
        $entradas = [];
        $subform  = $params->get('videos');

        if (\is_object($subform) || \is_array($subform)) {
            foreach ((array) $subform as $entrada) {
                $entrada    = (array) $entrada;
                $entradas[] = ['url' => $entrada['url'] ?? '', 'titulo' => $entrada['titulo'] ?? ''];
            }
        } else {
            $quantidade = min(12, max(0, (int) $params->get('video-amount', 0)));

            for ($i = 1; $i <= $quantidade; $i++) {
                $entradas[] = ['url' => $params->get('video-link-' . $i, ''), 'titulo' => ''];
            }
        }

        $videos = [];

        foreach ($entradas as $entrada) {
            $id = self::extrairIdDoYoutube((string) $entrada['url']);

            if ($id !== null) {
                $videos[] = ['id' => $id, 'titulo' => trim((string) $entrada['titulo'])];
            }
        }

        return $videos;
    }

    /**
     * Extrai o identificador de um endereço do YouTube.
     *
     * Aceita youtube.com/watch?v=ID, youtu.be/ID, youtube.com/embed/ID, /shorts/ID e /live/ID
     * (com ou sem www/m, com ou sem https) e também o próprio identificador de 11 caracteres.
     *
     * @param   string  $url  Endereço informado
     *
     * @return  string|null  Identificador ou null quando o endereço não é do YouTube
     *
     * @since   3.1.0
     */
    public static function extrairIdDoYoutube(string $url): ?string
    {
        $url = trim($url);

        if (preg_match('/^[A-Za-z0-9_-]{11}$/', $url)) {
            return $url;
        }

        $padroes = [
            '~^(?:https?:)?(?://)?(?:www\.|m\.|music\.)?youtube(?:-nocookie)?\.com/watch\?(?:[^#]*&)?v=([A-Za-z0-9_-]{11})(?![A-Za-z0-9_-])~i',
            '~^(?:https?:)?(?://)?(?:www\.|m\.)?youtube(?:-nocookie)?\.com/(?:embed|shorts|live|v)/([A-Za-z0-9_-]{11})(?![A-Za-z0-9_-])~i',
            '~^(?:https?:)?(?://)?youtu\.be/([A-Za-z0-9_-]{11})(?![A-Za-z0-9_-])~i',
        ];

        foreach ($padroes as $padrao) {
            if (preg_match($padrao, $url, $m)) {
                return $m[1];
            }
        }

        return null;
    }
}
