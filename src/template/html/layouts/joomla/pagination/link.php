<?php

/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   (C) 2014 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

$item    = $displayData['data'];
$display = $item->text;
$app = Factory::getApplication();

switch ((string) $item->text) {
    // Check for "Prev" item
    case $item->text === Text::_('JPREV'):
        $item->text = Text::_('JPREVIOUS');
        $icon = 'fas fa-angle-left';
        $aria = Text::sprintf('JLIB_HTML_GOTO_POSITION', strtolower($item->text));
        break;

    // Check for "Next" item
    case Text::_('JNEXT'):
        $icon = 'fas fa-angle-right';
        $aria = Text::sprintf('JLIB_HTML_GOTO_POSITION', strtolower($item->text));
        break;

    default:
        $icon = null;
        $aria = Text::sprintf('JLIB_HTML_GOTO_PAGE', strtolower($item->text));
        break;
}

if ($icon !== null) {
    $display = '<i class="' . $icon . '" aria-hidden="true"></i>';
}

if ($displayData['active']) {
    if ($item->base > 0) {
        $limit = 'limitstart.value=' . $item->base;
    } else {
        $limit = 'limitstart.value=0';
    }

    $class = 'active';

    if ($app->isClient('site')) {
        $link = 'href="' . $item->link . '"';
    }
} else {
    $class = (property_exists($item, 'active') && $item->active) ? 'active' : 'disabled';
}

?>
<?php if ($displayData['active']) : ?>
    <li>
        <?php if (($item->text === Text::_('JPREVIOUS')) || ($item->text === Text::_('JNEXT'))) : ?>
            <a class="br-button circle" aria-label="<?php echo $aria; ?>" <?php echo $link; ?>><?php echo $display; ?></a>
        <?php else : ?>
            <a class="page" aria-label="<?php echo $aria; ?>" <?php echo $link; ?>>
                <?php echo $display; ?>
            </a>
        <?php endif; ?>
    </li>
<?php elseif (isset($item->active) && $item->active) : ?>
    <?php $aria = Text::sprintf('JLIB_HTML_PAGE_CURRENT', strtolower($item->text)); ?>
    <li>
        <a class="page <?php echo $class; ?>" aria-current="true" aria-label="<?php echo $aria; ?>" href="javascript:void(0)"><?php echo $display; ?></a>
    </li>
<?php else : ?>
    <li>
        <a class="br-button circle <?php echo $class; ?>" aria-label="<?php echo $aria; ?>" href="javascript:void(0)"><?php echo $display; ?></a>
    </li>
<?php endif; ?>
