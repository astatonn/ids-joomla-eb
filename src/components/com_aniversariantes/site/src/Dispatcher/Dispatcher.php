<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_aniversariantes
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

namespace Astatonn\Component\Aniversariantes\Site\Dispatcher;

use Joomla\CMS\Dispatcher\ComponentDispatcher;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Dispatcher do site.
 *
 * Não acrescenta comportamento ao dispatcher padrão do Joomla. O arquivo existe para SOBRESCREVER
 * o Dispatcher das versões 1.x/2.x, que dependia da constante JPATH_PLATFORM (inexistente no
 * Joomla 6 sem o plugin de compatibilidade) e deixava o componente em tela branca.
 *
 * @since  3.1.0
 */
class Dispatcher extends ComponentDispatcher
{
}
