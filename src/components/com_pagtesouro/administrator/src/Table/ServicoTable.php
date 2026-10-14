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
 * Tabela #__pagtesouro_servicos.
 *
 * @property  int     $id
 * @property  int     $uasg_id    UASG à qual o serviço pertence
 * @property  string  $codigo     Código do serviço no PagTesouro
 * @property  string  $descricao  Nome exibido ao contribuinte
 * @property  int     $state      1 = publicado, 0 = despublicado
 *
 * @since  3.1.0
 */
class ServicoTable extends Table
{
    public function __construct(DatabaseInterface $db, ?DispatcherInterface $dispatcher = null)
    {
        parent::__construct('#__pagtesouro_servicos', 'id', $db, $dispatcher);

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

        $this->uasg_id   = (int) $this->uasg_id;
        $this->codigo    = trim((string) $this->codigo);
        $this->descricao = trim((string) $this->descricao);

        if ($this->uasg_id <= 0) {
            throw new \UnexpectedValueException(Text::_('COM_PAGTESOURO_ERROR_SERVICO_UASG_OBRIGATORIA'));
        }

        if ($this->codigo === '') {
            throw new \UnexpectedValueException(Text::_('COM_PAGTESOURO_ERROR_SERVICO_CODIGO_OBRIGATORIO'));
        }

        if ($this->descricao === '') {
            throw new \UnexpectedValueException(Text::_('COM_PAGTESOURO_ERROR_DESCRICAO_OBRIGATORIA'));
        }

        $db     = $this->getDatabase();
        $id     = (int) $this->id;
        $uasgId = $this->uasg_id;

        // A UASG precisa existir
        $query = $db->getQuery(true)
            ->select('COUNT(*)')
            ->from($db->quoteName('#__pagtesouro_uasgs'))
            ->where($db->quoteName('id') . ' = :uasg')
            ->bind(':uasg', $uasgId, ParameterType::INTEGER);

        if ((int) $db->setQuery($query)->loadResult() === 0) {
            throw new \UnexpectedValueException(Text::_('COM_PAGTESOURO_ERROR_SERVICO_UASG_OBRIGATORIA'));
        }

        // Não permite repetir o código do serviço dentro da mesma UASG
        $query = $db->getQuery(true)
            ->select('COUNT(*)')
            ->from($db->quoteName('#__pagtesouro_servicos'))
            ->where($db->quoteName('uasg_id') . ' = :uasg')
            ->where($db->quoteName('codigo') . ' = :codigo')
            ->where($db->quoteName('id') . ' <> :id')
            ->bind(':uasg', $uasgId, ParameterType::INTEGER)
            ->bind(':codigo', $this->codigo)
            ->bind(':id', $id, ParameterType::INTEGER);

        if ((int) $db->setQuery($query)->loadResult() > 0) {
            throw new \UnexpectedValueException(Text::_('COM_PAGTESOURO_ERROR_SERVICO_DUPLICADO'));
        }

        return true;
    }
}
