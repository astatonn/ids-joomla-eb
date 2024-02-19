<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

$linktype = '<span class="content">' . $item->title . '</span>';

if ($item->menu_icon) {
    // The link has an icon
    $linktype = '<span class="icon"><i class="fas ' . $item->menu_icon . '" aria-hidden="true"></i></span>' . $linktype;
}

?>
<a class="menu-item" href="javascript: void(0)"><?php echo $linktype; ?></a>
