<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  mod_readmorenews
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * @var string  $link     Endereço de destino
 * @var string  $texto    Texto do botão
 * @var bool    $novaAba  Abrir em nova aba?
 */

$esc = static fn ($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
?>
<div class="news-readmore">
    <a href="<?php echo $esc($link); ?>"<?php echo $novaAba ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
        <div class="br-button br-button-readmore">
            <?php echo $esc($texto); ?>
        </div>
    </a>
</div>
