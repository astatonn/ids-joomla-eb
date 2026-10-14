<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_aniversariantes
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

namespace Astatonn\Component\Aniversariantes\Administrator\Table;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseInterface;
use Joomla\Event\DispatcherInterface;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Tabela #__aniversariantes_ (o sublinhado final faz parte do nome desde a versão 1.0).
 *
 * @property  int     $id
 * @property  string  $nome
 * @property  string  $aniversario  Data de nascimento (AAAA-MM-DD)
 * @property  string  $pg           Posto ou graduação
 *
 * @since  3.1.0
 */
class AniversarianteTable extends Table
{
    public function __construct(DatabaseInterface $db, ?DispatcherInterface $dispatcher = null)
    {
        parent::__construct('#__aniversariantes_', 'id', $db, $dispatcher);
    }

    /**
     * Valida e normaliza os dados antes de gravar.
     *
     * @return  boolean
     *
     * @throws  \UnexpectedValueException  Quando algum dado é inválido
     *
     * @since   3.1.0
     */
    public function check()
    {
        parent::check();

        $this->nome = trim((string) $this->nome);
        $this->pg   = trim((string) $this->pg);

        if ($this->nome === '') {
            throw new \UnexpectedValueException(Text::_('COM_ANIVERSARIANTES_ERROR_NOME_OBRIGATORIO'));
        }

        if ($this->pg === '') {
            throw new \UnexpectedValueException(Text::_('COM_ANIVERSARIANTES_ERROR_PG_OBRIGATORIO'));
        }

        // O campo de calendário pode entregar "AAAA-MM-DD HH:MM:SS"; a coluna guarda só a data.
        $data  = substr(trim((string) $this->aniversario), 0, 10);
        $valid = \DateTimeImmutable::createFromFormat('!Y-m-d', $data);

        if ($valid === false || $valid->format('Y-m-d') !== $data) {
            throw new \UnexpectedValueException(Text::_('COM_ANIVERSARIANTES_ERROR_DATA_INVALIDA'));
        }

        $this->aniversario = $data;

        return true;
    }
}
