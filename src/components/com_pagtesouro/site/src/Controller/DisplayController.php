<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_pagtesouro
 * @copyright   (C) 2025-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

namespace Astatonn\Component\Pagtesouro\Site\Controller;

use Joomla\CMS\MVC\Controller\BaseController;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Controller do site: exibe apenas o formulário de geração da GRU.
 *
 * @since  3.1.0
 */
class DisplayController extends BaseController
{
    /**
     * @var    string
     * @since  3.1.0
     */
    protected $default_view = 'formulario';

    /**
     * @param   boolean  $cachable   Pode usar cache?
     * @param   array    $urlparams  Parâmetros seguros da URL
     *
     * @return  static
     *
     * @since   3.1.0
     */
    public function display($cachable = false, $urlparams = [])
    {
        // Só existe uma view pública; qualquer outro valor (inclusive o antigo view=raw) cai nela.
        $this->input->set('view', 'formulario');
        $this->input->set('format', 'html');

        return parent::display($cachable, $urlparams);
    }
}
