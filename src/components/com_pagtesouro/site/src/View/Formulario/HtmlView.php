<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_pagtesouro
 * @copyright   (C) 2025-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

namespace Astatonn\Component\Pagtesouro\Site\View\Formulario;

use Astatonn\Component\Pagtesouro\Site\Model\FormularioModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\Registry\Registry;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Formulário público que monta o link da GRU no PagTesouro.
 *
 * @since  3.1.0
 */
class HtmlView extends BaseHtmlView
{
    /**
     * Endereço do portal PagTesouro que recebe os dados da GRU.
     *
     * @var    string
     * @since  3.1.0
     */
    public const PORTAL_GRU = 'https://pagtesouro.tesouro.gov.br/portal-gru/#/pagamento-gru';

    /**
     * UASGs publicadas com seus serviços.
     *
     * @var    array
     * @since  3.1.0
     */
    protected $uasgs = [];

    /**
     * @var    Registry
     * @since  3.1.0
     */
    protected $params;

    /**
     * @param   string  $tpl  Sub-layout
     *
     * @return  void
     *
     * @since   3.1.0
     */
    public function display($tpl = null)
    {
        /** @var \Joomla\CMS\Application\SiteApplication $app */
        $app = Factory::getApplication();

        /** @var FormularioModel $model */
        $model = $this->getModel();

        $this->params = $app->getParams();
        $this->uasgs  = $model->getUasgs();

        $this->prepararDocumento();

        parent::display($tpl);
    }

    /**
     * Título da página, descrição, robots e dados para o JavaScript.
     *
     * @return  void
     *
     * @since   3.1.0
     */
    protected function prepararDocumento(): void
    {
        $menu = Factory::getApplication()->getMenu()->getActive();

        $this->params->def('page_heading', $menu ? $this->params->get('page_title', $menu->title) : Text::_('COM_PAGTESOURO_FORM_TITLE'));

        $this->setDocumentTitle((string) $this->params->get('page_title', Text::_('COM_PAGTESOURO_FORM_TITLE')));

        $documento = $this->getDocument();

        if ($this->params->get('menu-meta_description')) {
            $documento->setDescription($this->params->get('menu-meta_description'));
        }

        if ($this->params->get('robots')) {
            $documento->setMetaData('robots', $this->params->get('robots'));
        }

        $wa = $documento->getWebAssetManager();
        $wa->getRegistry()->addExtensionRegistryFile('com_pagtesouro');
        $wa->useStyle('com_pagtesouro.formulario')
            ->useScript('com_pagtesouro.formulario');

        $documento->addScriptOptions(
            'com_pagtesouro.formulario',
            [
                'portal' => self::PORTAL_GRU,
                'uasgs'  => $this->uasgs,
            ]
        );

        Text::script('COM_PAGTESOURO_SELECT_SERVICE');
        Text::script('COM_PAGTESOURO_SEM_SERVICOS');
        Text::script('COM_PAGTESOURO_ERROR_FILL_ALL_FIELDS');
        Text::script('COM_PAGTESOURO_ERROR_CPF_CNPJ');
        Text::script('COM_PAGTESOURO_ERROR_VALOR');
    }
}
