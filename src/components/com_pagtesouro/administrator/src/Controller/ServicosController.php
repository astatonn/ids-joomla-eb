<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_pagtesouro
 * @copyright   (C) 2025-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

namespace Astatonn\Component\Pagtesouro\Administrator\Controller;

use Joomla\CMS\MVC\Controller\AdminController;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Ações em lote da lista de serviços: publicar, despublicar e excluir.
 *
 * @since  3.1.0
 */
class ServicosController extends AdminController
{
    /**
     * @var    string
     * @since  3.1.0
     */
    protected $text_prefix = 'COM_PAGTESOURO_SERVICOS';

    /**
     * @param   string  $name    Nome do model
     * @param   string  $prefix  Prefixo (cliente)
     * @param   array   $config  Configuração
     *
     * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel|boolean
     *
     * @since   3.1.0
     */
    public function getModel($name = 'Servico', $prefix = 'Administrator', $config = ['ignore_request' => true])
    {
        return parent::getModel($name, $prefix, $config);
    }
}
