<?php
defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;



?>
<div class="container">
    <!-- HEADER  -->

    <div class="autofit-float autofit-row portlet-header">
        <div class="autofit-col autofit-col-expand">
            <div class="portlet-title-text">
                <?php echo $params['section-name']; ?>
            </div>
        </div>
    </div>


    <div class="links-container">
        <?php for ($i = 1; $i <= $params['link-amount'] ; $i++) : ?>
            <div class="link-col">
                <a href="<?php echo $params['url-link-' . $i]; ?>" target="_blank" class="link-link">
                    <div class="link-card br-card">
                        <div class="link-content">
                            <i class="<?php echo $params['icon-link-'. $i ]?>" style="color:var(--green-vivid-60);font-size:40px;margin:10px 0"></i>
                            <h5 class="link-title"><?php echo $params['name-link-' . $i]; ?></h5>
                        </div>
                    </div>
                </a>
            </div>
        <?php endfor; ?>
    </div>


</div>