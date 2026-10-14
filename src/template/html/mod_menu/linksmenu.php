<?php

defined('_JEXEC') or die;

use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\HTML\HTMLHelper;


foreach ($list as $i => &$item) {
    $attributes = array();
    
    switch ($item->type) :
        case 'alias':
        case 'component':
            $linktype = '<span>' . $item->title . '</span>';
            if ($item->menu_icon) {
                $linktype = '<i class="fas ' . $item->menu_icon . '" aria-hidden="true"></i>' . $linktype;
            }
            break;
        default:
            $linktype = '<span class="mr-1">' . $item->title . '</span><i class="fas fa-external-link-square-alt" aria-hidden="true"></i>';
            break;
    endswitch;
    
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