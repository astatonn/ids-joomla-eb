<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_aniversariantes
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

namespace Astatonn\Component\Aniversariantes\Administrator\Controller;

use Joomla\CMS\MVC\Controller\FormController;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Formulário de cadastro/edição de um aniversariante.
 *
 * @since  3.1.0
 */
class AniversarianteController extends FormController
{
    /**
     * @var    string
     * @since  3.1.0
     */
    protected $text_prefix = 'COM_ANIVERSARIANTES';

    /**
     * @var    string
     * @since  3.1.0
     */
    protected $view_list = 'aniversariantes';
}
