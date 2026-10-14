<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  mod_aniver
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * @var string       $titulo         Título da seção
 * @var \stdClass[]  $itens          Aniversariantes: nome, pg, data ("dd/mm"), hoje
 * @var bool         $mostrarData    Exibir o dia e o mês?
 * @var string       $mensagemVazio  Texto exibido quando não há aniversariantes
 */

$esc = static fn ($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
?>
<div class="container mod-aniver">
    <?php if ($titulo !== '') : ?>
        <div class="autofit-float autofit-row portlet-header">
            <div class="autofit-col autofit-col-expand">
                <div class="portlet-title-text">
                    <?php echo $esc($titulo); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="birthdays-container">
        <div class="birthdays-col">
            <div class="birthday-content">
                <?php foreach ($itens as $item) : ?>
                    <div class="birthday-data<?php echo $item->hoje ? ' birthday-today' : ''; ?>">
                        <?php echo $esc(trim($item->pg . ' ' . $item->nome)); ?><?php echo $mostrarData ? ' - ' . $esc($item->data) : ''; ?><?php echo $item->hoje ? ' 🎉' : ''; ?>
                    </div>
                <?php endforeach; ?>

                <?php if (!$itens && $mensagemVazio !== '') : ?>
                    <div class="birthday-data birthday-empty"><?php echo $esc($mensagemVazio); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
