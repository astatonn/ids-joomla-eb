<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_pagtesouro
 * @copyright   (C) 2025-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

namespace Astatonn\Component\Pagtesouro\Administrator\View\Servico;

use Astatonn\Component\Pagtesouro\Administrator\Model\ServicoModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Formulário (servico).
 *
 * @since  3.1.0
 */
class HtmlView extends BaseHtmlView
{
    /**
     * @var    Form
     * @since  3.1.0
     */
    protected $form;

    /**
     * @var    object
     * @since  3.1.0
     */
    protected $item;

    /**
     * @param   string  $tpl  Sub-layout
     *
     * @return  void
     *
     * @since   3.1.0
     */
    public function display($tpl = null)
    {
        /** @var ServicoModel $model */
        $model = $this->getModel();

        $this->form = $model->getForm();
        $this->item = $model->getItem();

        $this->addToolbar();

        parent::display($tpl);
    }

    /**
     * @return  void
     *
     * @since   3.1.0
     */
    protected function addToolbar(): void
    {
        Factory::getApplication()->getInput()->set('hidemainmenu', true);

        $isNew   = empty($this->item->id);
        $canDo   = ContentHelper::getActions('com_pagtesouro');
        $toolbar = $this->getDocument()->getToolbar();

        ToolbarHelper::title(Text::_($isNew ? 'COM_PAGTESOURO_NEW_SERVICO_TITLE' : 'COM_PAGTESOURO_EDIT_SERVICO_TITLE'), 'list');

        $podeSalvar = $isNew ? $canDo->get('core.create') : $canDo->get('core.edit');

        if ($podeSalvar) {
            $toolbar->apply('servico.apply');
            $toolbar->save('servico.save');
        }

        if ($podeSalvar && $canDo->get('core.create')) {
            $toolbar->save2new('servico.save2new');
        }

        $toolbar->cancel('servico.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
    }
}
