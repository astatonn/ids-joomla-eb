<?php

defined('_JEXEC') or die;

use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\HTML\HTMLHelper;


foreach ($list as $i => &$item) {
    $attributes = array();

    if ($item->anchor_title) {
        $attributes['title'] = $item->anchor_title;
    }

    $linktype = $item->title;

    if ($item->menu_image) {
        // The link is an image, maybe with its own class
        $image_attributes = [];

        if ($item->menu_image_css) {
            $image_attributes['class'] = $item->menu_image_css;
        }

        $linktype = HTMLHelper::_('image', $item->menu_image, $item->title, $image_attributes);
    }

    if ($item->browserNav == 1) {
        $attributes['target'] = '_blank';
        $attributes['rel'] = 'noopener noreferrer';

        if ($item->anchor_rel == 'nofollow') {
                $attributes['rel'] .= ' nofollow';
        }
    } elseif ($item->browserNav == 2) {
        $options = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,' . $params->get('window_open');
        $attributes['onclick'] = "window.open(this.href, 'targetWindow', '" . $options . "'); return false;";
    }

    echo HTMLHelper::_('link', OutputFilter::ampReplace(htmlspecialchars($item->flink, ENT_COMPAT, 'UTF-8', false)), $linktype, $attributes);
}
?>