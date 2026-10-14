<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  mod_imagempopup
 * @copyright   (C) 2024-2026 Henrique Michels Voicolesco, Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

namespace Astatonn\Module\Imagempopup\Site\Dispatcher;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Módulo "Popup": janela com imagem e/ou texto exibida ao abrir a página.
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

        $imagem = $this->resolverImagem((string) $params->get('imagem', ''));
        $texto  = trim((string) $params->get('popuptext', ''));

        // Sem imagem e sem texto não há o que exibir.
        if ($imagem === '' && $texto === '') {
            return false;
        }

        $temporizador = (int) $params->get('temporizador', 0) === 1;
        $frequencia   = (string) $params->get('frequencia', 'sempre');

        $data['imagem']     = $imagem;
        $data['imagemAlt']  = trim((string) $params->get('imagem_alt', ''));
        $data['link']       = $this->resolverLink((string) $params->get('link', ''));
        $data['texto']      = $texto;
        $data['segundos']   = $temporizador ? max(1, (int) $params->get('segundos', 10)) : 0;
        $data['frequencia'] = \in_array($frequencia, ['sempre', 'sessao', 'dia'], true) ? $frequencia : 'sempre';
        $data['fecharFora'] = (int) $params->get('fechar_fora', 1) === 1;
        // Muda sempre que o conteúdo muda: um popup novo volta a aparecer para quem já fechou o anterior.
        $data['assinatura'] = substr(md5($imagem . '|' . $texto), 0, 12);

        return $data;
    }

    /**
     * Endereço da imagem escolhida no campo de mídia (sem o sufixo "#joomlaImage://...").
     *
     * @param   string  $valor  Valor gravado pelo campo
     *
     * @return  string
     *
     * @since   3.1.0
     */
    private function resolverImagem(string $valor): string
    {
        $valor = trim($valor);

        if ($valor === '') {
            return '';
        }

        $url = HTMLHelper::cleanImageURL($valor)->url;

        if (!preg_match('#^([a-z][a-z0-9+.-]*:)?//#i', $url) && strncmp($url, '/', 1) !== 0) {
            $url = Uri::root(true) . '/' . $url;
        }

        return $url;
    }

    /**
     * Endereço aberto ao clicar na imagem (opcional).
     *
     * @param   string  $valor  Endereço informado
     *
     * @return  string
     *
     * @since   3.1.0
     */
    private function resolverLink(string $valor): string
    {
        $valor = trim($valor);

        if ($valor === '' || preg_match('#^\s*(javascript|data|vbscript):#i', $valor)) {
            return '';
        }

        if (!preg_match('#^([a-z][a-z0-9+.-]*:|//|/|\#|\?)#i', $valor)) {
            $valor = Uri::root(true) . '/' . $valor;
        }

        return $valor;
    }
}
