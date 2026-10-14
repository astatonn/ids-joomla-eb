<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  mod_aniver
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

namespace Astatonn\Module\Aniver\Site\Dispatcher;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\Log\Log;
use Joomla\Registry\Registry;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Módulo "Aniversariantes": lista os aniversariantes cadastrados no componente com_aniversariantes.
 *
 * @since  3.1.0
 */
class Dispatcher extends AbstractModuleDispatcher
{
    /**
     * @return  array|false  Dados do layout, ou false para não exibir o módulo
     *
     * @since   3.1.0
     */
    protected function getLayoutData()
    {
        $data = parent::getLayoutData();

        /** @var Registry $params */
        $params = $data['params'];

        $itens = $this->obterAniversariantes($params);

        // Sem o componente (ou com erro na consulta) o módulo simplesmente não aparece.
        if ($itens === null) {
            return false;
        }

        if (!$itens && $params->get('sem_aniversariantes', 'mensagem') === 'ocultar') {
            return false;
        }

        $data['titulo']        = trim((string) $params->get('titulo', 'Aniversariantes'));
        $data['itens']         = $itens;
        $data['mostrarData']   = (int) $params->get('mostrar_data', 1) === 1;
        $data['mensagemVazio'] = trim((string) $params->get('mensagem_vazio', 'Nenhum aniversariante no período.'));

        return $data;
    }

    /**
     * Consulta o model público do componente Aniversariantes.
     *
     * @param   Registry  $params  Parâmetros do módulo
     *
     * @return  \stdClass[]|null  Aniversariantes, ou null se o componente não estiver disponível
     *
     * @since   3.1.0
     */
    private function obterAniversariantes(Registry $params): ?array
    {
        if (!ComponentHelper::isEnabled('com_aniversariantes')) {
            return null;
        }

        try {
            $componente = $this->app->bootComponent('com_aniversariantes');

            if (!$componente instanceof MVCComponent) {
                return null;
            }

            $model = $componente->getMVCFactory()->createModel('Aniversariantes', 'Site', ['ignore_request' => true]);

            if (!$model) {
                return null;
            }

            if (method_exists($model, 'getAniversariantes')) {
                return $model->getAniversariantes(
                    (string) $params->get('periodo', 'mes'),
                    (int) $params->get('dias', 7)
                );
            }

            // Componente ainda na versão 1.x/2.x: só sabe listar os aniversariantes do mês.
            if (method_exists($model, 'getItemsWithNextBirthdays')) {
                return $this->adaptarItensAntigos((array) $model->getItemsWithNextBirthdays());
            }

            return null;
        } catch (\Throwable $e) {
            // Nunca derruba a página por causa do módulo: registra e segue sem exibir.
            Log::add('mod_aniver: ' . $e->getMessage(), Log::WARNING, 'jerror');

            return null;
        }
    }

    /**
     * Converte as linhas devolvidas pelo componente 1.x/2.x para o formato usado no layout.
     *
     * @param   array  $linhas  Linhas com nome, pg e aniversario (AAAA-MM-DD)
     *
     * @return  \stdClass[]
     *
     * @since   3.1.0
     */
    private function adaptarItensAntigos(array $linhas): array
    {
        $hoje  = date('m-d');
        $itens = [];

        foreach ($linhas as $linha) {
            $nascimento = \DateTimeImmutable::createFromFormat('!Y-m-d', substr((string) ($linha->aniversario ?? ''), 0, 10));

            if ($nascimento === false) {
                continue;
            }

            $item       = new \stdClass();
            $item->nome = (string) ($linha->nome ?? '');
            $item->pg   = (string) ($linha->pg ?? '');
            $item->data = $nascimento->format('d/m');
            $item->hoje = $nascimento->format('m-d') === $hoje;

            $itens[] = $item;
        }

        usort($itens, static fn (\stdClass $a, \stdClass $b): int => [substr($a->data, 0, 2), $a->nome] <=> [substr($b->data, 0, 2), $b->nome]);

        return $itens;
    }
}
