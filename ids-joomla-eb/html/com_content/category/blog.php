<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Layout\LayoutHelper;

$app = Factory::getApplication();

$this->category->text = $this->category->description;
$app->triggerEvent('onContentPrepare', [$this->category->extension . '.categories', &$this->category, &$this->params, 0]);
$this->category->description = $this->category->text;

$results = $app->triggerEvent('onContentAfterTitle', [$this->category->extension . '.categories', &$this->category, &$this->params, 0]);
$afterDisplayTitle = trim(implode("\n", $results));

$results = $app->triggerEvent('onContentBeforeDisplay', [$this->category->extension . '.categories', &$this->category, &$this->params, 0]);
$beforeDisplayContent = trim(implode("\n", $results));

$results = $app->triggerEvent('onContentAfterDisplay', [$this->category->extension . '.categories', &$this->category, &$this->params, 0]);
$afterDisplayContent = trim(implode("\n", $results));

$htag    = $this->params->get('show_page_heading') ? 'h2' : 'h1';

?>
<div class="com-content-category-blog blog" itemscope itemtype="https://schema.org/Blog">
    <div class="br-divider pb-4"></div>

    <?php $showHeader = $this->params->get('show_page_heading') || $this->params->get('show_category_title'); ?>
	<?php if ($showHeader) : ?>
    <div class="pt-4"></div>
    <?php if ($this->params->get('show_page_heading')) : ?>
    <p class="page-header"> <?php echo $this->escape($this->params->get('page_heading')); ?> </p>
    <?php endif; ?>
    <?php if ($this->params->get('show_category_title', 1)) : ?>
        <<?php echo $htag; ?> itemprop="headline">
            <?php echo $this->category->title; ?>
        </<?php echo $htag; ?>>
    <?php endif; ?>
    <?php endif; ?>
    <?php echo $afterDisplayTitle; ?>

    <?php if ($beforeDisplayContent || $afterDisplayContent || $this->params->get('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
        <div itemprop="description">
        <?php echo $beforeDisplayContent; ?>
        <?php if ($this->params->get('show_description') && $this->category->description) : ?>
            <?php echo HTMLHelper::_('content.prepare', $this->category->description, '', 'com_content.category'); ?>
        <?php endif; ?>
        <?php echo $afterDisplayContent; ?>
    </div>
    <?php endif; ?>
    <?php if ($showHeader || $this->params->get('show_description', 1)) : ?>
    <div class="br-divider"></div>
    <?php endif; ?>

    <?php if (empty($this->lead_items) && empty($this->link_items) && empty($this->intro_items)) : ?>
        <?php if ($this->params->get('show_no_articles', 1)) : ?>
            <div class="alert alert-info">
                <span class="icon-info-circle" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
                    <?php echo Text::_('COM_CONTENT_NO_ARTICLES'); ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="com-content-category-blog__items blog-items">
        <?php if (!empty($this->lead_items)) : ?>
            <?php foreach ($this->lead_items as &$item) : ?>
                <div class="com-content-category-blog__item blog-item" itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
                    <?php
                    $this->item = &$item;
                    echo $this->loadTemplate('item');
                    ?>
                </div>
                <div class="br-divider mb-3"></div>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php if (!empty($this->intro_items)) : ?>
            <?php foreach ($this->intro_items as $key => &$item) : ?>
                <div class="com-content-category-blog__item blog-item" itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
                    <?php
                    $this->item = & $item;
                    echo $this->loadTemplate('item');
                    ?>
                </div>
                <div class="br-divider mb-3"></div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="mb-5"></div>

    <?php if (($this->params->def('show_pagination', 1) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
        <div class="paginacao">
            <?php echo $this->pagination->getPagesLinks(); ?>
        </div>
    <?php endif; ?>
</div>
