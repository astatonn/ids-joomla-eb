<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\HTML\HTMLHelper;

?>
<?php foreach ($list as $i => &$item) {
    $attributes = [];

    $attributes['class'] = 'menu-item divider';

    if ($item->anchor_title) {
        $attributes['title'] = $item->anchor_title;
    }

    if ($item->anchor_css) {
        $attributes['class'] .= $item->anchor_css;
    }

    if ($item->anchor_rel) {
        $attributes['rel'] = $item->anchor_rel;
    }

    if ($item->id == $active_id) {
        $attributes['aria-current'] = 'location';

        if ($item->current) {
            $attributes['class'] .= ' active';
        }
    }

    $linktype = '<span class="content">' . $item->title . '</span>';

    if ($item->menu_icon) {
        // The link has an icon
        $linktype = '<span class="icon"><i class="fas ' . $item->menu_icon . '" aria-hidden="true"></i></span>' . $linktype;
    }

    if ($item->browserNav == 1) {
        $attributes['target'] = '_blank';
        if ($item->type == 'url') {
            $attributes['rel'] = 'noopener noreferrer';

            if ($item->anchor_rel == 'nofollow') {
                $attributes['rel'] .= ' nofollow';
            }
        }
    } elseif ($item->browserNav == 2) {
        $options = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes';

        $attributes['onclick'] = "window.open(this.href, 'targetWindow', '" . $options . "'); return false;";
    }

    echo HTMLHelper::_('link', OutputFilter::ampReplace(htmlspecialchars($item->flink, ENT_COMPAT, 'UTF-8', false)), $linktype, $attributes);
}
?>
