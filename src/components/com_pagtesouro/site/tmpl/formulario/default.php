<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_pagtesouro
 * @copyright   (C) 2025-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/** @var \Astatonn\Component\Pagtesouro\Site\View\Formulario\HtmlView $this */

$introducao = trim((string) $this->params->get('introducao', ''));
$unicaUasg  = count($this->uasgs) === 1 ? $this->uasgs[0] : null;
?>
<div class="pagtesouro-form com-pagtesouro<?php echo $this->escape((string) $this->params->get('pageclass_sfx', '')); ?>">
    <?php if ($this->params->get('show_page_heading')) : ?>
        <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
    <?php else : ?>
        <h2><?php echo Text::_('COM_PAGTESOURO_FORM_TITLE'); ?></h2>
    <?php endif; ?>

    <?php if ($introducao !== '') : ?>
        <div class="pagtesouro-introducao"><?php echo $introducao; ?></div>
    <?php endif; ?>

    <?php if (!$this->uasgs) : ?>
        <div class="alert alert-info" role="status"><?php echo Text::_('COM_PAGTESOURO_NENHUMA_UASG'); ?></div>
    <?php else : ?>
        <div id="pagtesouro-erro" class="alert alert-danger" role="alert" aria-live="assertive" hidden></div>

        <form id="pagtesouroForm" action="#" method="get" novalidate>
            <div class="control-group">
                <label for="pagtesouro-uasg"><?php echo Text::_('COM_PAGTESOURO_FIELD_UASG_LABEL'); ?> <span aria-hidden="true">*</span></label>
                <select id="pagtesouro-uasg" name="uasg_id" class="form-control form-select" required<?php echo $unicaUasg ? ' data-unica="1"' : ''; ?>>
                    <?php if (!$unicaUasg) : ?>
                        <option value=""><?php echo Text::_('COM_PAGTESOURO_SELECT_UASG'); ?></option>
                    <?php endif; ?>
                    <?php foreach ($this->uasgs as $uasg) : ?>
                        <option value="<?php echo (int) $uasg['id']; ?>"<?php echo $unicaUasg ? ' selected' : ''; ?>>
                            <?php echo $this->escape($uasg['orgao'] . ' - ' . $uasg['ug'] . ' (' . $uasg['descricao'] . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="control-group">
                <label for="pagtesouro-servico"><?php echo Text::_('COM_PAGTESOURO_FIELD_SERVICO_LABEL'); ?> <span aria-hidden="true">*</span></label>
                <select id="pagtesouro-servico" name="servico_id" class="form-control form-select" required disabled>
                    <option value=""><?php echo Text::_('COM_PAGTESOURO_SELECT_SERVICE'); ?></option>
                </select>
            </div>

            <div class="control-group">
                <label for="pagtesouro-nome"><?php echo Text::_('COM_PAGTESOURO_FIELD_NOME_CONTRIBUINTE_LABEL'); ?> <span aria-hidden="true">*</span></label>
                <input type="text" id="pagtesouro-nome" name="nome_contribuinte" class="form-control" autocomplete="name" required>
            </div>

            <div class="control-group">
                <label for="pagtesouro-cpfcnpj"><?php echo Text::_('COM_PAGTESOURO_FIELD_CPF_CNPJ_LABEL'); ?> <span aria-hidden="true">*</span></label>
                <input type="text" id="pagtesouro-cpfcnpj" name="cpf_cnpj" class="form-control" maxlength="18" autocomplete="off" required>
            </div>

            <div class="control-group">
                <label for="pagtesouro-referencia"><?php echo Text::_('COM_PAGTESOURO_FIELD_NUMERO_REFERENCIA_LABEL'); ?> <span aria-hidden="true">*</span></label>
                <input type="text" id="pagtesouro-referencia" name="numero_referencia" class="form-control" autocomplete="off" required>
            </div>

            <div class="control-group">
                <label for="pagtesouro-valor"><?php echo Text::_('COM_PAGTESOURO_FIELD_VALOR_PRINCIPAL_LABEL'); ?> <span aria-hidden="true">*</span></label>
                <input type="number" id="pagtesouro-valor" name="valor_principal" class="form-control" step="0.01" min="0.01" inputmode="decimal" required>
            </div>

            <div class="control-group">
                <button type="submit" class="btn btn-primary"><?php echo Text::_('COM_PAGTESOURO_BUTTON_SUBMIT'); ?></button>
            </div>

            <p class="pagtesouro-aviso"><?php echo Text::_('COM_PAGTESOURO_AVISO_NOVA_ABA'); ?></p>
        </form>

        <noscript>
            <div class="alert alert-info"><?php echo Text::_('COM_PAGTESOURO_NOSCRIPT'); ?></div>
        </noscript>
    <?php endif; ?>
</div>
