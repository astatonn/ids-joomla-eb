<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_aniversariantes
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

namespace Astatonn\Component\Aniversariantes\Site\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Database\DatabaseInterface;
use Joomla\Database\Exception\DatabaseNotFoundException;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Consulta de aniversariantes para o site e para o módulo mod_aniver.
 *
 * @since  3.1.0
 */
class AniversariantesModel extends BaseDatabaseModel
{
    /** Aniversariantes do mês corrente. */
    public const PERIODO_MES = 'mes';

    /** Aniversariantes de hoje. */
    public const PERIODO_HOJE = 'hoje';

    /** Aniversariantes de hoje e dos próximos dias. */
    public const PERIODO_PROXIMOS = 'proximos';

    /** Todos os aniversariantes, na ordem do calendário. */
    public const PERIODO_ANO = 'ano';

    /**
     * Lista os aniversariantes de um período.
     *
     * Cada item devolvido tem: id, nome, pg, aniversario (AAAA-MM-DD), dia, mes,
     * data ("dd/mm") e hoje (true quando o aniversário é na data de referência).
     *
     * @param   string                   $periodo     Uma das constantes PERIODO_*
     * @param   integer                  $dias        Quantidade de dias à frente (apenas PERIODO_PROXIMOS)
     * @param   \DateTimeInterface|null  $referencia  Data de referência; padrão: agora, no fuso do site
     *
     * @return  \stdClass[]
     *
     * @since   3.1.0
     */
    public function getAniversariantes(string $periodo = self::PERIODO_MES, int $dias = 30, ?\DateTimeInterface $referencia = null): array
    {
        $hoje      = $this->normalizarReferencia($referencia);
        $mesAtual  = (int) $hoje->format('n');
        $diaAtual  = (int) $hoje->format('j');
        $bissexto  = (bool) $hoje->format('L');
        $distancia = [];

        switch ($periodo) {
            case self::PERIODO_HOJE:
                $distancia = $this->diasDoPeriodo($hoje, 1);
                break;

            case self::PERIODO_PROXIMOS:
                $distancia = $this->diasDoPeriodo($hoje, max(1, min(366, $dias)));
                break;

            case self::PERIODO_ANO:
                break;

            default:
                $periodo = self::PERIODO_MES;
                break;
        }

        $db    = $this->obterBanco();
        $query = $db->getQuery(true)
            ->select($db->quoteName(['id', 'nome', 'aniversario', 'pg']))
            ->from($db->quoteName('#__aniversariantes_'));

        if ($periodo === self::PERIODO_MES) {
            $query->where($query->month($db->quoteName('aniversario')) . ' = ' . $mesAtual);
        } elseif ($distancia) {
            $meses = array_unique(array_map(static fn (string $chave): int => (int) substr($chave, 0, 2), array_keys($distancia)));
            $query->whereIn($query->month($db->quoteName('aniversario')), $meses);
        }

        $itens = [];

        foreach ($db->setQuery($query)->loadObjectList() as $linha) {
            $nascimento = \DateTimeImmutable::createFromFormat('!Y-m-d', substr((string) $linha->aniversario, 0, 10));

            if ($nascimento === false) {
                continue;
            }

            $chave = $nascimento->format('m-d');

            // Quem nasceu em 29/02 comemora em 28/02 nos anos não bissextos
            if ($chave === '02-29' && !$bissexto) {
                $chave = '02-28';
            }

            if ($distancia && !isset($distancia[$chave])) {
                continue;
            }

            $item              = new \stdClass();
            $item->id          = (int) $linha->id;
            $item->nome        = (string) $linha->nome;
            $item->pg          = (string) $linha->pg;
            $item->aniversario = $nascimento->format('Y-m-d');
            $item->mes         = (int) substr($chave, 0, 2);
            $item->dia         = (int) substr($chave, 3, 2);
            $item->data        = sprintf('%02d/%02d', $item->dia, $item->mes);
            $item->hoje        = $item->mes === $mesAtual && $item->dia === $diaAtual;
            $item->ordem       = $distancia ? $distancia[$chave] : ($item->mes * 100 + $item->dia);

            $itens[] = $item;
        }

        usort(
            $itens,
            static fn (\stdClass $a, \stdClass $b): int => [$a->ordem, $a->nome] <=> [$b->ordem, $b->nome]
        );

        return $itens;
    }

    /**
     * Compatibilidade com o módulo mod_aniver das versões 1.x/2.x, que chamava este método.
     *
     * @return  \stdClass[]  Aniversariantes do mês corrente
     *
     * @since       1.0.0
     * @deprecated  3.1.0  Use getAniversariantes()
     */
    public function getItemsWithNextBirthdays(): array
    {
        return $this->getAniversariantes(self::PERIODO_MES);
    }

    /**
     * Data de referência no fuso horário do site, sem a parte de horas.
     *
     * @param   \DateTimeInterface|null  $referencia  Data informada ou null para "agora"
     *
     * @return  \DateTimeImmutable
     *
     * @since   3.1.0
     */
    private function normalizarReferencia(?\DateTimeInterface $referencia): \DateTimeImmutable
    {
        if ($referencia !== null) {
            return \DateTimeImmutable::createFromInterface($referencia)->setTime(0, 0);
        }

        try {
            $fuso = new \DateTimeZone((string) Factory::getApplication()->get('offset', 'UTC'));
        } catch (\Exception $e) {
            $fuso = new \DateTimeZone('UTC');
        }

        return (new \DateTimeImmutable('now', $fuso))->setTime(0, 0);
    }

    /**
     * Mapa "mm-dd" => distância em dias a partir da data de referência.
     *
     * @param   \DateTimeImmutable  $inicio      Primeiro dia
     * @param   integer             $quantidade  Quantidade de dias (incluindo o primeiro)
     *
     * @return  array<string, int>
     *
     * @since   3.1.0
     */
    private function diasDoPeriodo(\DateTimeImmutable $inicio, int $quantidade): array
    {
        $dias = [];

        for ($i = 0; $i < $quantidade; $i++) {
            $chave = $inicio->modify('+' . $i . ' days')->format('m-d');

            if (!isset($dias[$chave])) {
                $dias[$chave] = $i;
            }
        }

        return $dias;
    }

    /**
     * Conexão com o banco, mesmo quando o model foi criado sem a fábrica MVC.
     *
     * @return  DatabaseInterface
     *
     * @since   3.1.0
     */
    private function obterBanco(): DatabaseInterface
    {
        try {
            return $this->getDatabase();
        } catch (DatabaseNotFoundException $e) {
            $db = Factory::getContainer()->get(DatabaseInterface::class);
            $this->setDatabase($db);

            return $db;
        }
    }
}
