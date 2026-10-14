<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_aniversariantes
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/** @var \Astatonn\Component\Aniversariantes\Site\View\Aniversariantes\HtmlView $this */

$this->getDocument()->getWebAssetManager()
    ->getRegistry()->addExtensionRegistryFile('com_aniversariantes');
$this->getDocument()->getWebAssetManager()->useStyle('com_aniversariantes.lista');

$meses = [
    1 => 'JANUARY', 2 => 'FEBRUARY', 3 => 'MARCH', 4 => 'APRIL', 5 => 'MAY', 6 => 'JUNE',
    7 => 'JULY', 8 => 'AUGUST', 9 => 'SEPTEMBER', 10 => 'OCTOBER', 11 => 'NOVEMBER', 12 => 'DECEMBER',
];
?>
<div class="com-aniversariantes aniversariantes<?php echo $this->escape((string) $this->params->get('pageclass_sfx', '')); ?>">
    <?php if ($this->params->get('show_page_heading')) : ?>
        <div class="page-header">
            <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
        </div>
    <?php endif; ?>

    <?php if (!$this->grupos) : ?>
        <p class="aniversariantes-vazio"><?php echo Text::_('COM_ANIVERSARIANTES_NENHUM_NO_PERIODO'); ?></p>
    <?php endif; ?>

    <?php foreach ($this->grupos as $mes => $itens) : ?>
        <section class="aniversariantes-mes">
            <h2 class="aniversariantes-mes-titulo"><?php echo Text::_($meses[$mes]); ?></h2>
            <ul class="aniversariantes-lista">
                <?php foreach ($itens as $item) : ?>
                    <li class="aniversariantes-item<?php echo $item->hoje ? ' aniversariantes-hoje' : ''; ?>">
                        <span class="aniversariantes-data"><?php echo $item->data; ?></span>
                        <span class="aniversariantes-nome"><?php echo $this->escape(trim($item->pg . ' ' . $item->nome)); ?></span>
                        <?php if ($item->hoje) : ?>
                            <span class="aniversariantes-selo"><?php echo Text::_('COM_ANIVERSARIANTES_HOJE'); ?> 🎉</span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endforeach; ?>
</div>
