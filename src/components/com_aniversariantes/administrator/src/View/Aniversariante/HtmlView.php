<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_aniversariantes
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

namespace Astatonn\Component\Aniversariantes\Administrator\View\Aniversariante;

use Astatonn\Component\Aniversariantes\Administrator\Model\AniversarianteModel;
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
 * Formulário de um aniversariante (administrador).
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
        /** @var AniversarianteModel $model */
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
        $canDo   = ContentHelper::getActions('com_aniversariantes');
        $toolbar = $this->getDocument()->getToolbar();

        ToolbarHelper::title(
            Text::_($isNew ? 'COM_ANIVERSARIANTES_NEW_ANIVERSARIANTE' : 'COM_ANIVERSARIANTES_EDIT_ANIVERSARIANTE'),
            'calendar'
        );

        $podeSalvar = $isNew ? $canDo->get('core.create') : $canDo->get('core.edit');

        if ($podeSalvar) {
            $toolbar->apply('aniversariante.apply');
            $toolbar->save('aniversariante.save');
        }

        if ($podeSalvar && $canDo->get('core.create')) {
            $toolbar->save2new('aniversariante.save2new');
        }

        $toolbar->cancel('aniversariante.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
    }
}
