<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_tags
 *
 * @copyright   (C) 2013 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Tags\Site\Helper\RouteHelper;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('com_tags.tag-default');

// Get the user object.
$user = Factory::getUser();

// Check if user is allowed to add/edit based on tags permissions.
// Do we really have to make it so people can see unpublished tags???
$canEdit      = $user->authorise('core.edit', 'com_tags');
$canCreate    = $user->authorise('core.create', 'com_tags');
$canEditState = $user->authorise('core.edit.state', 'com_tags');
?>
<div class="com-tags__items">

    <?php if (empty($this->items)) : ?>
        <div class="alert alert-info">
            <span class="icon-info-circle" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
            <?php echo Text::_('COM_TAGS_NO_ITEMS'); ?>
        </div>
    <?php else : ?>
        <?php foreach ($this->items as $i => $item) : ?>
            <div class="com-tags-tag__category category-item">
                <?php if (($item->type_alias === 'com_users.category') || ($item->type_alias === 'com_banners.category')) : ?>
                    <h2 itemprop="name">
                        <?php echo $this->escape($item->core_title); ?>
                    </h2>
                <?php else : ?>
                    <h2 itemprop="name">
                        <a href="<?php echo Route::_($item->link); ?>">
                            <?php echo $this->escape($item->core_title); ?>
                        </a>
                    </h2>
                <?php endif; ?>

                <?php $images  = json_decode($item->core_images); ?>
                <?php if ($this->params->get('tag_list_show_item_image', 1) == 1 && !empty($images->image_intro)) : ?>
                    <a href="<?php echo Route::_(RouteHelper::getItemRoute($item->content_item_id, $item->core_alias, $item->core_catid, $item->core_language, $item->type_alias, $item->router)); ?>">
                        <?php echo HTMLHelper::_('image', $images->image_intro, $images->image_intro_alt); ?>
                    </a>
                <?php endif; ?>

                <div class="info"><?php echo HTMLHelper::_('date', $item->core_publish_up, Text::_('d/m/Y H:i')); ?></div>
            </div>
            <div class="br-divider mb-3"></div>
            <?php endforeach; ?>
    <?php endif; ?>
</div>
