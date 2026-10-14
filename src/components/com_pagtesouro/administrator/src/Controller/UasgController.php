<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_pagtesouro
 * @copyright   (C) 2025-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

namespace Astatonn\Component\Pagtesouro\Administrator\Controller;

use Joomla\CMS\MVC\Controller\FormController;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Formulário de cadastro/edição (uasg).
 *
 * @since  3.1.0
 */
class UasgController extends FormController
{
    /**
     * @var    string
     * @since  3.1.0
     */
    protected $text_prefix = 'COM_PAGTESOURO_UASGS';

    /**
     * @var    string
     * @since  3.1.0
     */
    protected $view_list = 'uasgs';
}
