<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_aniversariantes
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

namespace Astatonn\Component\Aniversariantes\Administrator\Extension;

use Joomla\CMS\Component\Router\RouterServiceInterface;
use Joomla\CMS\Component\Router\RouterServiceTrait;
use Joomla\CMS\Extension\MVCComponent;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Classe do componente Aniversariantes.
 *
 * Mantida propositalmente simples: o "boot" do componente acontece em qualquer página que exiba
 * o módulo de aniversariantes, então nada aqui pode depender de classes legadas do Joomla.
 *
 * @since  3.1.0
 */
class AniversariantesComponent extends MVCComponent implements RouterServiceInterface
{
    use RouterServiceTrait;
}
