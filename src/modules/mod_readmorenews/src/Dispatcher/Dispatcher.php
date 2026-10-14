<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  mod_readmorenews
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

namespace Astatonn\Module\Readmorenews\Site\Dispatcher;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Site\Helper\RouteHelper;
use Joomla\Registry\Registry;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Módulo "Botão Leia Mais": botão que leva a uma categoria de artigos, item de menu ou endereço.
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

        // Valor "yes"/"no" mantido das versões 1.x/2.x
        if ($params->get('showbuttonreademore', 'yes') === 'no') {
            return false;
        }

        $link = $this->obterLink($params);

        if ($link === null) {
            return false;
        }

        $texto = trim((string) $params->get('texto', ''));

        $data['link']    = $link;
        $data['texto']   = $texto !== '' ? $texto : 'Leia Mais';
        $data['novaAba'] = $params->get('destino', 'menu') === 'url' && (int) $params->get('nova_aba', 0) === 1;

        return $data;
    }

    /**
     * Endereço de destino do botão.
     *
     * @param   Registry  $params  Parâmetros do módulo
     *
     * @return  string|null  Endereço, ou null quando o destino não foi configurado
     *
     * @since   3.1.0
     */
    private function obterLink(Registry $params): ?string
    {
        switch ((string) $params->get('destino', 'menu')) {
            case 'categoria':
                $categoria = (int) $params->get('categoria', 0);

                return $categoria > 0 ? Route::_(RouteHelper::getCategoryRoute($categoria)) : null;

            case 'url':
                $url = trim((string) $params->get('url', ''));

                if ($url === '' || preg_match('#^\s*(javascript|data|vbscript):#i', $url)) {
                    return null;
                }

                // Caminho interno sem barra inicial parte da raiz do site
                if (!preg_match('#^([a-z][a-z0-9+.-]*:|//|/|\#|\?)#i', $url)) {
                    $url = Uri::root(true) . '/' . $url;
                }

                return $url;

            default:
                $itemId = (int) $params->get('menu-readmore', 0);

                // As versões antigas gravavam "mainmenu" quando nada era escolhido
                if ($itemId <= 0 || !$this->app->getMenu()->getItem($itemId)) {
                    return null;
                }

                return Route::_('index.php?Itemid=' . $itemId);
        }
    }
}
