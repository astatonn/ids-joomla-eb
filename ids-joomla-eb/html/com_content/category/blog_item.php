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
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Component\Content\Site\Helper\RouteHelper;

// Create a shortcut for params.
$user   = Factory::getUser();
$params = $this->item->params;
$images = json_decode($this->item->images);

$currentDate   = Factory::getDate()->format('Y-m-d H:i:s');
$isUnpublished = ($this->item->state == ContentComponent::CONDITION_UNPUBLISHED || $this->item->publish_up > $currentDate)
    || ($this->item->publish_down < $currentDate && $this->item->publish_down !== null);

if (!empty($images->image_intro)) {
    $layoutAttr = [
        'src'   => $images->image_intro,
        'alt'   => empty($images->image_intro_alt) && empty($images->image_intro_alt_empty) ? false : $images->image_intro_alt,
        'class' => 'itemImage',
    ];
?>

<div class="tileImage">
    <a href="<?php echo Route::_(RouteHelper::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language)); ?>" itemprop="url" title="<?php echo $this->escape($this->item->title); ?>">
        <?php echo LayoutHelper::render('joomla.html.image', $layoutAttr); ?>
    </a>
</div>

<?php
}
?>

<div class="item-content">
    <?php if ($isUnpublished) : ?>
        <div class="system-unpublished">
    <?php endif; ?>

    <h2 itemprop="name">
        <?php if ($params->get('link_titles') && ($params->get('access-view') || $params->get('show_noauth', '0') == '1')) : ?>
            <a href="<?php echo Route::_(RouteHelper::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language)); ?>" itemprop="url">
                <?php echo $this->escape($this->item->title); ?>
            </a>
        <?php else : ?>
            <?php echo $this->escape($this->item->title); ?>
        <?php endif; ?>
    </h2>

    <?php if (!empty($this->item->tags->itemTags)) : ?>
    <div class="tags">
        <span>Tags:</span>
        <?php $authorised = $user->getAuthorisedViewLevels(); ?>
        <?php foreach ($this->item->tags->itemTags as $tag) : ?>
            <?php if (in_array($tag->access, $authorised)) : ?>
                <span>
                    <a href="<?php echo Route::_(Joomla\Component\Tags\Site\Helper\RouteHelper::getComponentTagRoute($tag->tag_id . ':' . $tag->alias, $tag->language)); ?>" class="tags-link">
                        <?php echo $this->escape($tag->title); ?>
                    </a>
                </span>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php // @todo Not that elegant would be nice to group the params ?>

    <div class="info"><?php echo HTMLHelper::_('date', $this->item->publish_up, Text::_('d/m/Y H:i')); ?></div>

    <?php if ($isUnpublished) : ?>
        </div>
    <?php endif; ?>

</div>
