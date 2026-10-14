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

$linktype = '<span class="content">' . $module->title . '</span>';

if ($params->get('class_sfx', ''))
{
    $linktype = '<span class="icon"><i class="' . htmlspecialchars($params->get('class_sfx', ''), ENT_QUOTES, 'UTF-8') . '" aria-hidden="true"></i></span>' . $linktype;
}

$linktype = '<a class="menu-item" href="javascript: void(0)">' . $linktype . '</a>';
?>

<div class="menu-folder">
    <?php echo $linktype; ?>
    <ul>
    <?php foreach ($list as $i => &$item) {
        echo '<li>';

        switch ($item->type) :
            case 'separator':
            case 'component':
            case 'heading':
            case 'url':
                require ModuleHelper::getLayoutPath('mod_menu', 'menuprincipal_' . $item->type);
                break;

            default:
                require ModuleHelper::getLayoutPath('mod_menu', 'menuprincipal_url');
                break;
        endswitch;

        // The next item is deeper.
        if ($item->deeper) {
            echo '<ul>';
        } elseif ($item->shallower) {
            // The next item is shallower.
            echo '</li>';
            echo str_repeat('</ul></li>', $item->level_diff);
        } else {
            // The next item is on the same level.
            echo '</li>';
        }
    }
    ?></ul>
</div>
