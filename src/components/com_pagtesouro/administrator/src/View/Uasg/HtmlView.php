<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_pagtesouro
 * @copyright   (C) 2025-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

namespace Astatonn\Component\Pagtesouro\Administrator\View\Uasg;

use Astatonn\Component\Pagtesouro\Administrator\Model\UasgModel;
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
 * Formulário (uasg).
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
        /** @var UasgModel $model */
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

        ToolbarHelper::title(Text::_($isNew ? 'COM_PAGTESOURO_NEW_UASG_TITLE' : 'COM_PAGTESOURO_EDIT_UASG_TITLE'), 'building');

        $podeSalvar = $isNew ? $canDo->get('core.create') : $canDo->get('core.edit');

        if ($podeSalvar) {
            $toolbar->apply('uasg.apply');
            $toolbar->save('uasg.save');
        }

        if ($podeSalvar && $canDo->get('core.create')) {
            $toolbar->save2new('uasg.save2new');
        }

        $toolbar->cancel('uasg.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
    }
}
