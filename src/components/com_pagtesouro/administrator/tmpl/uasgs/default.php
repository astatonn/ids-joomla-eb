<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_pagtesouro
 * @copyright   (C) 2025-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

/** @var \Astatonn\Component\Pagtesouro\Administrator\View\Uasgs\HtmlView $this */

/** @var \Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->getDocument()->getWebAssetManager();
$wa->useScript('table.columns')
    ->useScript('multiselect');

$user      = $this->getCurrentUser();
$canEdit   = $user->authorise('core.edit', 'com_pagtesouro');
$canChange = $user->authorise('core.edit.state', 'com_pagtesouro');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<form action="<?php echo Route::_('index.php?option=com_pagtesouro&view=uasgs'); ?>" method="post" name="adminForm" id="adminForm">
    <div class="row">
        <div class="col-md-12">
            <div id="j-main-container" class="j-main-container">
                <?php echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>

                <?php if (empty($this->items)) : ?>
                    <div class="alert alert-info">
                        <span class="icon-info-circle" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
                        <?php echo Text::_($this->activeFilters ? 'JGLOBAL_NO_MATCHING_RESULTS' : 'COM_PAGTESOURO_UASGS_EMPTY'); ?>
                    </div>
                <?php else : ?>
                    <table class="table itemList" id="uasgList">
                        <caption class="visually-hidden">
                            <?php echo Text::_('COM_PAGTESOURO_UASGS_TABLE_CAPTION'); ?>,
                            <span id="orderedBy"><?php echo Text::_('JGLOBAL_SORTED_BY'); ?> </span>,
                            <span id="filteredBy"><?php echo Text::_('JGLOBAL_FILTERED_BY'); ?></span>
                        </caption>
                        <thead>
                            <tr>
                                <td class="w-1 text-center">
                                    <?php echo HTMLHelper::_('grid.checkall'); ?>
                                </td>
                                <th scope="col" class="w-1 text-center">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_PAGTESOURO_HEADING_DESCRICAO', 'a.descricao', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-10">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_PAGTESOURO_HEADING_ORGAO', 'a.orgao', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-10">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_PAGTESOURO_HEADING_UG', 'a.ug', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-10 text-center d-none d-md-table-cell">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_PAGTESOURO_HEADING_SERVICOS', 'servicos', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-5 d-none d-lg-table-cell">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->items as $i => $item) : ?>
                                <tr class="row<?php echo $i % 2; ?>">
                                    <td class="text-center">
                                        <?php echo HTMLHelper::_('grid.id', $i, $item->id, false, 'cid', 'cb', $item->descricao); ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo HTMLHelper::_('jgrid.published', $item->state, $i, 'uasgs.', $canChange, 'cb'); ?>
                                    </td>
                                    <th scope="row">
                                        <?php if ($canEdit) : ?>
                                            <a href="<?php echo Route::_('index.php?option=com_pagtesouro&task=uasg.edit&id=' . (int) $item->id); ?>" title="<?php echo Text::_('JACTION_EDIT'); ?> <?php echo $this->escape($item->descricao); ?>">
                                                <?php echo $this->escape($item->descricao); ?>
                                            </a>
                                        <?php else : ?>
                                            <?php echo $this->escape($item->descricao); ?>
                                        <?php endif; ?>
                                    </th>
                                    <td>
                                        <?php echo $this->escape($item->orgao); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->escape($item->ug); ?>
                                    </td>
                                    <td class="text-center d-none d-md-table-cell">
                                        <a class="btn btn-sm btn-secondary" href="<?php echo Route::_('index.php?option=com_pagtesouro&view=servicos&filter[uasg_id]=' . (int) $item->id . '&filter[search]=&filter[state]='); ?>" title="<?php echo Text::_('COM_PAGTESOURO_UASGS_VER_SERVICOS'); ?>">
                                            <?php echo (int) $item->servicos; ?>
                                        </a>
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
