<?php

defined('_JEXEC') or die;

use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\HTML\HTMLHelper;

?>
                            <div class="col-2">
                                <a class="br-item header" href="javascript:void(0)">
                                    <div class="content text-down-01 text-bold text-uppercase"><?php echo htmlspecialchars($module->title, ENT_QUOTES, 'UTF-8'); ?></div>
                                    <div class="support"><i class="fas fa-angle-down" aria-hidden="true"></i></div>
                                </a>
                                <div class="br-list">
                                    <span class="br-divider d-md-none"></span>
<?php
foreach ($list as $i => &$item) {
    $attributes = array();
    
    $attributes['class'] = 'br-item';
    $linktype = '<div class="content">' . $item->title . '</div>';
    
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
                                    <span class="br-divider d-md-none"></span>
                                </div>
                            </div>