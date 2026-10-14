<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_pagtesouro
 * @copyright   (C) 2025-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

namespace Astatonn\Component\Pagtesouro\Administrator\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Model\AdminModel;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Cadastro (uasg).
 *
 * @since  3.1.0
 */
class UasgModel extends AdminModel
{
    /**
     * Prefixo das mensagens exibidas pelos controllers.
     *
     * @var    string
     * @since  3.1.0
     */
    protected $text_prefix = 'COM_PAGTESOURO_UASGS';

    /**
     * @param   array    $data      Dados para o formulário
     * @param   boolean  $loadData  Carregar os dados gravados?
     *
     * @return  Form|boolean
     *
     * @since   3.1.0
     */
    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_pagtesouro.uasg', 'uasg', ['control' => 'jform', 'load_data' => $loadData]);

        return $form ?: false;
    }

    /**
     * @return  mixed  Dados digitados (após erro de validação) ou o registro gravado
     *
     * @since   3.1.0
     */
    protected function loadFormData()
    {
        $data = Factory::getApplication()->getUserState('com_pagtesouro.edit.uasg.data', []);

        if (empty($data)) {
            $data = $this->getItem();
        }

        $this->preprocessData('com_pagtesouro.uasg', $data);

        return $data;
    }
}
