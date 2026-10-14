<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_pagtesouro
 * @copyright   (C) 2025-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

namespace Astatonn\Component\Pagtesouro\Administrator\Model;

use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\ParameterType;
use Joomla\Database\QueryInterface;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Lista de serviços (administrador).
 *
 * @since  3.1.0
 */
class ServicosModel extends ListModel
{
    /**
     * @param   array                 $config   Configuração opcional
     * @param   ?MVCFactoryInterface  $factory  Fábrica MVC
     *
     * @since   3.1.0
     */
    public function __construct($config = [], ?MVCFactoryInterface $factory = null)
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'id', 'a.id',
                'uasg_id', 'a.uasg_id',
                'codigo', 'a.codigo',
                'descricao', 'a.descricao',
                'state', 'a.state',
                'uasg_descricao',
            ];
        }

        parent::__construct($config, $factory);
    }

    /**
     * @param   string  $ordering   Coluna padrão de ordenação
     * @param   string  $direction  Direção padrão
     *
     * @return  void
     *
     * @since   3.1.0
     */
    protected function populateState($ordering = 'a.descricao', $direction = 'ASC')
    {
        parent::populateState($ordering, $direction);
    }

    /**
     * @param   string  $id  Prefixo do identificador de cache
     *
     * @return  string
     *
     * @since   3.1.0
     */
    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.state');
        $id .= ':' . $this->getState('filter.uasg_id');

        return parent::getStoreId($id);
    }

    /**
     * @return  QueryInterface
     *
     * @since   3.1.0
     */
    protected function getListQuery()
    {
        $db    = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select($db->quoteName(['a.id', 'a.uasg_id', 'a.codigo', 'a.descricao', 'a.state']))
            ->select($db->quoteName('u.descricao', 'uasg_descricao'))
            ->select($db->quoteName('u.orgao', 'uasg_orgao'))
            ->select($db->quoteName('u.ug', 'uasg_ug'))
            ->from($db->quoteName('#__pagtesouro_servicos', 'a'))
            ->join('LEFT', $db->quoteName('#__pagtesouro_uasgs', 'u'), $db->quoteName('u.id') . ' = ' . $db->quoteName('a.uasg_id'));

        // Situação: publicado (1) / despublicado (0)
        $state = (string) $this->getState('filter.state');

        if (is_numeric($state)) {
            $state = (int) $state;
            $query->where($db->quoteName('a.state') . ' = :state')
                ->bind(':state', $state, ParameterType::INTEGER);
        }

        // Filtro por UASG
        $uasgId = (int) $this->getState('filter.uasg_id');

        if ($uasgId > 0) {
            $query->where($db->quoteName('a.uasg_id') . ' = :uasg')
                ->bind(':uasg', $uasgId, ParameterType::INTEGER);
        }

        // Busca por descrição, código ou "id:123"
        $search = trim((string) $this->getState('filter.search'));

        if ($search !== '') {
            if (stripos($search, 'id:') === 0) {
                $id = (int) substr($search, 3);
                $query->where($db->quoteName('a.id') . ' = :id')
                    ->bind(':id', $id, ParameterType::INTEGER);
            } else {
                $like = '%' . str_replace(' ', '%', $search) . '%';
                $query->where(
                    '(' . $db->quoteName('a.descricao') . ' LIKE :s1'
                    . ' OR ' . $db->quoteName('a.codigo') . ' LIKE :s2)'
                )->bind([':s1', ':s2'], $like);
            }
        }

        $orderCol  = $this->getState('list.ordering', 'a.descricao');
        $orderDirn = strtoupper((string) $this->getState('list.direction', 'ASC')) === 'DESC' ? 'DESC' : 'ASC';

        if ($orderCol === 'uasg_descricao') {
            $query->order($db->quoteName('u.descricao') . ' ' . $orderDirn)
                ->order($db->quoteName('a.descricao') . ' ASC');
        } else {
            $query->order($db->escape($orderCol) . ' ' . $orderDirn);
        }

        return $query;
    }
}
