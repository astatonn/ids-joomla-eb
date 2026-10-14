<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_aniversariantes
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

namespace Astatonn\Component\Aniversariantes\Administrator\Controller;

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Router\Route;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Controller padrão do componente (administrador).
 *
 * @since  3.1.0
 */
class DisplayController extends BaseController
{
    /**
     * @var    string
     * @since  3.1.0
     */
    protected $default_view = 'aniversariantes';

    /**
     * @param   boolean  $cachable   Pode usar cache?
     * @param   array    $urlparams  Parâmetros seguros da URL
     *
     * @return  static|boolean
     *
     * @since   3.1.0
     */
    public function display($cachable = false, $urlparams = [])
    {
        $view   = $this->input->get('view', $this->default_view);
        $layout = $this->input->get('layout', 'default');
        $id     = $this->input->getInt('id');

        // O formulário de edição só pode ser aberto pelo botão Novo/Editar (task=aniversariante.edit)
        if ($view === 'aniversariante' && $layout === 'edit' && !$this->checkEditId('com_aniversariantes.edit.aniversariante', $id)) {
            if (!\count($this->app->getMessageQueue())) {
                $this->setMessage(Text::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id), 'error');
            }

            $this->setRedirect(Route::_('index.php?option=com_aniversariantes&view=aniversariantes', false));

            return false;
        }

        return parent::display($cachable, $urlparams);
    }
}
