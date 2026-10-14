<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_pagtesouro
 * @copyright   (C) 2025-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

namespace Astatonn\Component\Pagtesouro\Site\Model;

use Joomla\CMS\MVC\Model\BaseDatabaseModel;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Dados do formulário público: UASGs publicadas e seus serviços publicados.
 *
 * @since  3.1.0
 */
class FormularioModel extends BaseDatabaseModel
{
    /**
     * UASGs publicadas, cada uma com a lista de serviços publicados.
     *
     * @return  array<int, array{id: int, orgao: string, ug: string, descricao: string, servicos: array<int, array{id: int, codigo: string, descricao: string}>}>
     *
     * @since   3.1.0
     */
    public function getUasgs(): array
    {
        $db = $this->getDatabase();

        $query = $db->getQuery(true)
            ->select($db->quoteName(['id', 'orgao', 'ug', 'descricao']))
            ->from($db->quoteName('#__pagtesouro_uasgs'))
            ->where($db->quoteName('state') . ' = 1')
            ->order($db->quoteName('descricao') . ' ASC');

        $uasgs = [];

        foreach ($db->setQuery($query)->loadObjectList() as $linha) {
            $uasgs[(int) $linha->id] = [
                'id'        => (int) $linha->id,
                'orgao'     => (string) $linha->orgao,
                'ug'        => (string) $linha->ug,
                'descricao' => (string) $linha->descricao,
                'servicos'  => [],
            ];
        }

        if (!$uasgs) {
            return [];
        }

        $query = $db->getQuery(true)
            ->select($db->quoteName(['id', 'uasg_id', 'codigo', 'descricao']))
            ->from($db->quoteName('#__pagtesouro_servicos'))
            ->where($db->quoteName('state') . ' = 1')
            ->whereIn($db->quoteName('uasg_id'), array_keys($uasgs))
            ->order($db->quoteName('descricao') . ' ASC');

        foreach ($db->setQuery($query)->loadObjectList() as $linha) {
            $uasgs[(int) $linha->uasg_id]['servicos'][] = [
                'id'        => (int) $linha->id,
                'codigo'    => (string) $linha->codigo,
                'descricao' => (string) $linha->descricao,
            ];
        }

        return array_values($uasgs);
    }
}
