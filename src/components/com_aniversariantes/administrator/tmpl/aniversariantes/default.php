<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_aniversariantes
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

/** @var \Astatonn\Component\Aniversariantes\Administrator\View\Aniversariantes\HtmlView $this */

/** @var \Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->getDocument()->getWebAssetManager();
$wa->useScript('table.columns')
    ->useScript('multiselect');

$user      = $this->getCurrentUser();
$canEdit   = $user->authorise('core.edit', 'com_aniversariantes');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$hoje      = (new DateTimeImmutable('now', $user->getTimezone()))->format('m-d');
?>
<form action="<?php echo Route::_('index.php?option=com_aniversariantes&view=aniversariantes'); ?>" method="post" name="adminForm" id="adminForm">
    <div class="row">
        <div class="col-md-12">
            <div id="j-main-container" class="j-main-container">
                <?php echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>

                <?php if (empty($this->items)) : ?>
                    <div class="alert alert-info">
                        <span class="icon-info-circle" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
                        <?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                    </div>
                <?php else : ?>
                    <table class="table itemList" id="aniversarianteList">
                        <caption class="visually-hidden">
                            <?php echo Text::_('COM_ANIVERSARIANTES_TABLE_CAPTION'); ?>,
                            <span id="orderedBy"><?php echo Text::_('JGLOBAL_SORTED_BY'); ?> </span>,
                            <span id="filteredBy"><?php echo Text::_('JGLOBAL_FILTERED_BY'); ?></span>
                        </caption>
                        <thead>
                            <tr>
                                <td class="w-1 text-center">
                                    <?php echo HTMLHelper::_('grid.checkall'); ?>
                                </td>
                                <th scope="col" class="w-15">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_ANIVERSARIANTES_HEADING_PG', 'a.pg', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_ANIVERSARIANTES_HEADING_NOME', 'a.nome', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-15">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_ANIVERSARIANTES_HEADING_DIA', 'diames', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-15 d-none d-md-table-cell">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_ANIVERSARIANTES_HEADING_NASCIMENTO', 'a.aniversario', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-5 d-none d-lg-table-cell">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->items as $i => $item) : ?>
                                <?php
                                $nascimento = DateTimeImmutable::createFromFormat('!Y-m-d', (string) $item->aniversario) ?: null;
                                $ehHoje     = $nascimento && $nascimento->format('m-d') === $hoje;
                                ?>
                                <tr class="row<?php echo $i % 2; ?>">
                                    <td class="text-center">
                                        <?php echo HTMLHelper::_('grid.id', $i, $item->id, false, 'cid', 'cb', $item->nome); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->escape($item->pg); ?>
                                    </td>
                                    <th scope="row">
                                        <?php if ($canEdit) : ?>
                                            <a href="<?php echo Route::_('index.php?option=com_aniversariantes&task=aniversariante.edit&id=' . (int) $item->id); ?>" title="<?php echo Text::_('JACTION_EDIT'); ?> <?php echo $this->escape($item->nome); ?>">
                                                <?php echo $this->escape($item->nome); ?>
                                            </a>
                                        <?php else : ?>
                                            <?php echo $this->escape($item->nome); ?>
                                        <?php endif; ?>
                                    </th>
                                    <td>
                                        <?php echo $nascimento ? $nascimento->format('d/m') : '-'; ?>
                                        <?php if ($ehHoje) : ?>
                                            <span class="badge bg-success"><?php echo Text::_('COM_ANIVERSARIANTES_HOJE'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <?php echo $nascimento ? $nascimento->format('d/m/Y') : '-'; ?>
                                    </td>
                                    <td class="d-none d-lg-table-cell">
                                        <?php echo (int) $item->id; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <?php echo $this->pagination->getListFooter(); ?>
                <?php endif; ?>

                <input type="hidden" name="task" value="">
                <input type="hidden" name="boxchecked" value="0">
                <?php echo HTMLHelper::_('form.token'); ?>
            </div>
        </div>
    </div>
</form>
