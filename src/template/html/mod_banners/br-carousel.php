<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_banners
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Banners\Site\Helper\BannerHelper;

?>
<div class="row">
    <div class="col">
        <div class="br-carousel" data-circular="true" data-stage="in" data-mobile-nav="">
            <div class="carousel-button">
                <button class="br-button carousel-btn-prev terciary circle" type="button" aria-label="Anterior"><i class="fas fa-chevron-left" aria-hidden="true"></i>
                </button>
            </div>
            <div class="carousel-stage">
                <?php foreach ($list as $i => $item) : ?>
                    <?php if ($i == 0) : ?>
                        <div class="carousel-page" active="active">
                    <?php else : ?>
                        <div class="carousel-page">
                    <?php endif; ?>
                            <div class="carousel-content">
                                <?php $link = Route::_('index.php?option=com_banners&task=click&id=' . $item->id); ?>
                                <?php if ($item->type == 1) : ?>
                                    <?php // Text based banners ?>
                                    <?php echo str_replace(['{CLICKURL}', '{NAME}'], [$link, $item->name], $item->custombannercode); ?>
                                <?php else : ?>
                                    <?php $imageurl = $item->params->get('imageurl'); ?>
                                    <?php $width = $item->params->get('width'); ?>
                                    <?php $height = $item->params->get('height'); ?>
                                    <?php if (BannerHelper::isImage($imageurl)) : ?>
                                        <?php // Image based banner ?>
                                        <?php $baseurl = strpos($imageurl, 'http') === 0 ? '' : Uri::base(); ?>
                                        <?php $alt = $item->params->get('alt'); ?>
                                        <?php $alt = $alt ?: $item->name; ?>
                                        <?php $alt = $alt ?: Text::_('MOD_BANNERS_BANNER'); ?>
                                        <?php if ($item->clickurl) : ?>
                                            <?php // Wrap the banner in a link ?>
                                            <?php $target = $params->get('target', 1); ?>
                                            <?php if ($target == 1) : ?>
                                                <?php // Open in a new window ?>
                                                <a
                                                    href="<?php echo $link; ?>" target="_blank" rel="noopener noreferrer"
                                                    title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8'); ?>">
                                                    <img
                                                        src="<?php echo $baseurl . $imageurl; ?>"
                                                        alt="<?php echo htmlspecialchars($alt, ENT_QUOTES, 'UTF-8'); ?>"
                                                        <?php if (!empty($width)) {
                                                            echo 'width="' . $width . '"';
                                                        } ?>
                                                        <?php if (!empty($height)) {
                                                            echo 'height="' . $height . '"';
                                                        } ?>
                                                    >
                                                </a>
                                            <?php elseif ($target == 2) : ?>
                                                <?php // Open in a popup window ?>
                                                <a
                                                    href="<?php echo $link; ?>" onclick="window.open(this.href, '',
                                                        'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550');
                                                        return false"
                                                    title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8'); ?>">
                                                    <img
                                                        src="<?php echo $baseurl . $imageurl; ?>"
                                                        alt="<?php echo htmlspecialchars($alt, ENT_QUOTES, 'UTF-8'); ?>"
                                                        <?php if (!empty($width)) {
                                                            echo 'width="' . $width . '"';
                                                        } ?>
                                                        <?php if (!empty($height)) {
                                                            echo 'height="' . $height . '"';
                                                        } ?>
                                                    >
                                                </a>
                                            <?php else : ?>
                                                <?php // Open in parent window ?>
                                                <a
                                                    href="<?php echo $link; ?>"
                                                    title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8'); ?>">
                                                    <img
                                                        src="<?php echo $baseurl . $imageurl; ?>"
                                                        alt="<?php echo htmlspecialchars($alt, ENT_QUOTES, 'UTF-8'); ?>"
                                                        <?php if (!empty($width)) {
                                                            echo 'width="' . $width . '"';
                                                        } ?>
                                                        <?php if (!empty($height)) {
                                                            echo 'height="' . $height . '"';
                                                        } ?>
                                                    >
                                                </a>
                                            <?php endif; ?>
                                        <?php else : ?>
                                            <?php // Just display the image if no link specified ?>
                                            <img
                                                src="<?php echo $baseurl . $imageurl; ?>"
                                                alt="<?php echo htmlspecialchars($alt, ENT_QUOTES, 'UTF-8'); ?>"
                                                <?php if (!empty($width)) {
                                                    echo 'width="' . $width . '"';
                                                } ?>
                                                <?php if (!empty($height)) {
                                                    echo 'height="' . $height . '"';
                                                } ?>
                                            >
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                <?php endforeach; ?>
            </div>
            <div class="carousel-button">
                <button class="br-button carousel-btn-next terciary circle" type="button" aria-label="Próximo"><i class="fas fa-chevron-right" aria-hidden="true"></i>
                </button>
            </div>
            <div class="carousel-step">
                <div class="br-step" data-initial="1" data-type="simple">
                <div class="step-progress">
                    <?php foreach ($list as $item) : ?>
                        <?php $alt = $item->params->get('alt'); ?>
                        <?php $alt = $alt ?: $item->name; ?>
                        <?php $alt = $alt ?: Text::_('MOD_BANNERS_BANNER'); ?>
                        <button class="step-progress-btn" type="button"><span class="step-info"><?php echo htmlspecialchars($alt, ENT_QUOTES, 'UTF-8'); ?></span></button>
                    <?php endforeach; ?>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
