<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_aniversariantes
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

namespace Astatonn\Component\Aniversariantes\Administrator\Model;

use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\ParameterType;
use Joomla\Database\QueryInterface;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Lista de aniversariantes (administrador).
 *
 * @since  3.1.0
 */
class AniversariantesModel extends ListModel
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
                'nome', 'a.nome',
                'aniversario', 'a.aniversario',
                'pg', 'a.pg',
                // Ordenação "do calendário": mês e dia, ignorando o ano de nascimento
                'diames',
                // Filtro por mês de aniversário
                'mes',
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
    protected function populateState($ordering = 'a.nome', $direction = 'ASC')
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
        $id .= ':' . $this->getState('filter.pg');
        $id .= ':' . $this->getState('filter.mes');

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
            ->select($db->quoteName(['a.id', 'a.nome', 'a.aniversario', 'a.pg']))
            ->from($db->quoteName('#__aniversariantes_', 'a'));

        // Busca por nome ou "id:123"
        $search = trim((string) $this->getState('filter.search'));

        if ($search !== '') {
            if (stripos($search, 'id:') === 0) {
                $id = (int) substr($search, 3);
                $query->where($db->quoteName('a.id') . ' = :id')
                    ->bind(':id', $id, ParameterType::INTEGER);
            } else {
                $like = '%' . str_replace(' ', '%', $search) . '%';
                $query->where($db->quoteName('a.nome') . ' LIKE :search')
                    ->bind(':search', $like);
            }
        }

        // Filtro por posto/graduação
        $pg = (string) $this->getState('filter.pg');

        if ($pg !== '') {
            $query->where($db->quoteName('a.pg') . ' = :pg')
                ->bind(':pg', $pg);
        }

        // Filtro por mês de aniversário (1 a 12)
        $mes = (int) $this->getState('filter.mes');

        if ($mes >= 1 && $mes <= 12) {
            $query->where($query->month($db->quoteName('a.aniversario')) . ' = :mes')
                ->bind(':mes', $mes, ParameterType::INTEGER);
        }

        // Ordenação (as colunas aceitas são as de $config['filter_fields'])
        $orderCol  = $this->getState('list.ordering', 'a.nome');
        $orderDirn = strtoupper((string) $this->getState('list.direction', 'ASC')) === 'DESC' ? 'DESC' : 'ASC';

        if ($orderCol === 'diames') {
            $query->order($query->month($db->quoteName('a.aniversario')) . ' ' . $orderDirn)
                ->order($query->day($db->quoteName('a.aniversario')) . ' ' . $orderDirn)
                ->order($db->quoteName('a.nome') . ' ASC');
        } else {
            $query->order($db->escape($orderCol) . ' ' . $orderDirn);
        }

        return $query;
    }
}
