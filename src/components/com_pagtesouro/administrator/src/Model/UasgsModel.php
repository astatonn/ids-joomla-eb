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
 * Lista de UASGs (administrador).
 *
 * @since  3.1.0
 */
class UasgsModel extends ListModel
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
                'orgao', 'a.orgao',
                'ug', 'a.ug',
                'descricao', 'a.descricao',
                'state', 'a.state',
                'servicos',
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

        return parent::getStoreId($id);
    }

    /**
     * @return  QueryInterface
     *
     * @since   3.1.0
     */
    protected function getListQuery()
    {
        $db = $this->getDatabase();

        // Quantidade de serviços de cada UASG
        $servicos = $db->getQuery(true)
            ->select('COUNT(*)')
            ->from($db->quoteName('#__pagtesouro_servicos', 's'))
            ->where($db->quoteName('s.uasg_id') . ' = ' . $db->quoteName('a.id'));

        $query = $db->getQuery(true)
            ->select($db->quoteName(['a.id', 'a.orgao', 'a.ug', 'a.descricao', 'a.state']))
            ->select('(' . $servicos . ') AS ' . $db->quoteName('servicos'))
            ->from($db->quoteName('#__pagtesouro_uasgs', 'a'));

        // Situação: publicada (1) / despublicada (0)
        $state = (string) $this->getState('filter.state');

        if (is_numeric($state)) {
            $state = (int) $state;
            $query->where($db->quoteName('a.state') . ' = :state')
                ->bind(':state', $state, ParameterType::INTEGER);
        }

        // Busca por descrição, órgão, UG ou "id:123"
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
                    . ' OR ' . $db->quoteName('a.orgao') . ' LIKE :s2'
                    . ' OR ' . $db->quoteName('a.ug') . ' LIKE :s3)'
                )->bind([':s1', ':s2', ':s3'], $like);
            }
        }

        $orderCol  = $this->getState('list.ordering', 'a.descricao');
        $orderDirn = strtoupper((string) $this->getState('list.direction', 'ASC')) === 'DESC' ? 'DESC' : 'ASC';

        $query->order($db->escape($orderCol) . ' ' . $orderDirn);

        return $query;
    }
}
