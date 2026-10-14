<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_pagtesouro
 * @copyright   (C) 2025-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

namespace Astatonn\Component\Pagtesouro\Administrator\Table;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseInterface;
use Joomla\Database\ParameterType;
use Joomla\Event\DispatcherInterface;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Tabela #__pagtesouro_uasgs.
 *
 * @property  int     $id
 * @property  string  $orgao      Código do órgão no PagTesouro
 * @property  string  $ug         Código da Unidade Gestora
 * @property  string  $descricao  Nome exibido ao contribuinte
 * @property  int     $state      1 = publicada, 0 = despublicada
 *
 * @since  3.1.0
 */
class UasgTable extends Table
{
    public function __construct(DatabaseInterface $db, ?DispatcherInterface $dispatcher = null)
    {
        parent::__construct('#__pagtesouro_uasgs', 'id', $db, $dispatcher);

        // A coluna de publicação desta tabela chama-se "state"
        $this->setColumnAlias('published', 'state');
    }

    /**
     * @return  boolean
     *
     * @throws  \UnexpectedValueException  Quando algum dado é inválido
     *
     * @since   3.1.0
     */
    public function check()
    {
        parent::check();

        $this->orgao     = trim((string) $this->orgao);
        $this->ug        = trim((string) $this->ug);
        $this->descricao = trim((string) $this->descricao);

        if ($this->orgao === '' || $this->ug === '') {
            throw new \UnexpectedValueException(Text::_('COM_PAGTESOURO_ERROR_UASG_CODIGOS_OBRIGATORIOS'));
        }

        if ($this->descricao === '') {
            throw new \UnexpectedValueException(Text::_('COM_PAGTESOURO_ERROR_DESCRICAO_OBRIGATORIA'));
        }

        // Não permite duas UASGs com o mesmo par órgão/UG
        $db    = $this->getDatabase();
        $id    = (int) $this->id;
        $query = $db->getQuery(true)
            ->select('COUNT(*)')
            ->from($db->quoteName('#__pagtesouro_uasgs'))
            ->where($db->quoteName('orgao') . ' = :orgao')
            ->where($db->quoteName('ug') . ' = :ug')
            ->where($db->quoteName('id') . ' <> :id')
            ->bind(':orgao', $this->orgao)
            ->bind(':ug', $this->ug)
            ->bind(':id', $id, ParameterType::INTEGER);

        if ((int) $db->setQuery($query)->loadResult() > 0) {
            throw new \UnexpectedValueException(Text::_('COM_PAGTESOURO_ERROR_UASG_DUPLICADA'));
        }

        return true;
    }
}
