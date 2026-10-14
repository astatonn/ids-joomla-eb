<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_pagtesouro
 * @copyright   (C) 2025-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

namespace Astatonn\Component\Pagtesouro\Administrator\View\Uasgs;

use Astatonn\Component\Pagtesouro\Administrator\Model\UasgsModel;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Pagination\Pagination;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\Registry\Registry;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Lista (uasgs).
 *
 * @since  3.1.0
 */
class HtmlView extends BaseHtmlView
{
    /**
     * @var    object[]
     * @since  3.1.0
     */
    protected $items = [];

    /**
     * @var    Pagination
     * @since  3.1.0
     */
    protected $pagination;

    /**
     * @var    Registry
     * @since  3.1.0
     */
    protected $state;

    /**
     * @var    Form|null
     * @since  3.1.0
     */
    public $filterForm;

    /**
     * @var    array
     * @since  3.1.0
     */
    public $activeFilters = [];

    /**
     * @param   string  $tpl  Sub-layout
     *
     * @return  void
     *
     * @since   3.1.0
     */
    public function display($tpl = null)
    {
        /** @var UasgsModel $model */
        $model = $this->getModel();

        $this->items         = $model->getItems() ?: [];
        $this->pagination    = $model->getPagination();
        $this->state         = $model->getState();
        $this->filterForm    = $model->getFilterForm();
        $this->activeFilters = $model->getActiveFilters();

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
        $canDo   = ContentHelper::getActions('com_pagtesouro');
        $toolbar = $this->getDocument()->getToolbar();

        ToolbarHelper::title(Text::_('COM_PAGTESOURO_MANAGER_UASGS'), 'building');

        if ($canDo->get('core.create')) {
            $toolbar->addNew('uasg.add');
        }

        if ($canDo->get('core.edit.state')) {
            $toolbar->publish('uasgs.publish')->listCheck(true);
            $toolbar->unpublish('uasgs.unpublish')->listCheck(true);
        }

        if ($canDo->get('core.delete')) {
            $toolbar->delete('uasgs.delete')
                ->message('COM_PAGTESOURO_UASGS_CONFIRM_DELETE')
                ->listCheck(true);
        }

        if ($canDo->get('core.admin') || $canDo->get('core.options')) {
            $toolbar->preferences('com_pagtesouro');
        }
    }
}
