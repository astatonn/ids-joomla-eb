<?php

defined('_JEXEC') or die;

use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\HTML\HTMLHelper;

?>

<div class="row d-flex flex-wrap justify-content-center mb-4">
<?php
foreach ($list as $i => &$item) {

    $itemParams = $item->getParams();
    $attributes = [];

    if ($item->anchor_title) {
        $attributes['title'] = $item->anchor_title;
    }

    if ($item->anchor_css) {
        $attributes['class'] = $item->anchor_css;
    }

    $linktype = '<span>' . $item->title . '</span>';

    if ($item->menu_icon) {
        // The link is an icon
        if ($itemParams->get('menu_text', 1)) {
            // If the link text is to be displayed, the icon is added with aria-hidden
            $linktype = '<i class="fas ' . $item->menu_icon . '" aria-hidden="true"></i><span>' . $item->title . '</span>';
        } else {
            // If the icon itself is the link, it needs a visually hidden text
            $linktype = '<i class="fas ' . $item->menu_icon . '" aria-hidden="true"></i><span class="visually-hidden">' . $item->title . '</span>';
        }
    } elseif ($item->menu_image) {
        // The link is an image, maybe with its own class
        $image_attributes = [];
    
        if ($item->menu_image_css) {
            $image_attributes['class'] = $item->menu_image_css;
        }
    
        $linktype = HTMLHelper::_('image', $item->menu_image, $item->title, $image_attributes);
    
        if ($itemParams->get('menu_text', 1)) {
            $linktype .= '<span class="image-title">' . $item->title . '</span>';
        }
    }
    
    if ($item->browserNav == 1) {
        $attributes['target'] = '_blank';
    } elseif ($item->browserNav == 2) {
        $options = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes';
    
        $attributes['onclick'] = "window.open(this.href, 'targetWindow', '" . $options . "'); return false;";
    }

    $linktype = '<div class="card-content d-flex flex-column justify-content-center align-items-center text-center">' . $linktype . '</div>';
?>
    <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="br-card h-fixed hover">
            <?php echo HTMLHelper::_('link', OutputFilter::ampReplace(htmlspecialchars($item->flink, ENT_COMPAT, 'UTF-8', false)), $linktype, $attributes); ?>
        </div>
    </div>
<?php
}
?>
</div>
