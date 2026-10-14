<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  mod_instafeed
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

namespace Astatonn\Module\Instafeed\Site\Dispatcher;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\Registry\Registry;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Módulo "Instagram": incorpora o perfil do Instagram da OM usando o embed oficial.
 *
 * @since  3.1.0
 */
class Dispatcher extends AbstractModuleDispatcher
{
    /**
     * @return  array|false  Dados do layout, ou false para não exibir o módulo
     *
     * @since   3.1.0
     */
    protected function getLayoutData()
    {
        $data = parent::getLayoutData();

        /** @var Registry $params */
        $params = $data['params'];

        $perfil = self::normalizarPerfil((string) $params->get('profile', ''));

        if ($perfil === null) {
            return false;
        }

        $data['perfil']  = $perfil;
        $data['url']     = 'https://www.instagram.com/' . $perfil . '/';
        $data['largura'] = min(1080, max(326, (int) $params->get('largura', 540)));

        return $data;
    }

    /**
     * Extrai o nome de usuário do Instagram do que foi digitado.
     *
     * Aceita "perfil", "@perfil" ou o endereço completo (https://www.instagram.com/perfil/).
     *
     * @param   string  $valor  Texto informado no módulo
     *
     * @return  string|null  Nome de usuário, ou null quando inválido
     *
     * @since   3.1.0
     */
    public static function normalizarPerfil(string $valor): ?string
    {
        $valor = trim($valor);

        if (preg_match('~^(?:https?:)?(?://)?(?:www\.)?instagram\.com/([^/?#]+)~i', $valor, $m)) {
            $valor = $m[1];
        }

        $valor = ltrim($valor, '@');

        // Regras do Instagram: até 30 caracteres entre letras, números, ponto e sublinhado
        return preg_match('/^[A-Za-z0-9._]{1,30}$/', $valor) ? $valor : null;
    }
}
