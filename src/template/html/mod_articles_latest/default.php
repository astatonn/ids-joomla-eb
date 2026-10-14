<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_news
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;

if (!$list) {
    return;
}

$colunas = "col-md-4";
if (count($list) < 4) :
    if (count($list) % 2 == 0) :
        $colunas = "col-md-6";
    endif;
endif;

?>
<div class="container">
    <div class="latestnews row">
        <?php foreach ($list as $item) : ?>
            <div class="latestnews-item <?php echo $colunas; ?>">
                <h2 class="latestnews-title">
                    <a href="<?php echo $item->link; ?>">
                        <?php echo $item->title; ?>
                    </a>
                </h2>
            </div>
        <?php endforeach; ?>
    </div>
</div>
