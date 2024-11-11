<div class="container">
    <!-- HEADER  -->
    <div class="autofit-float autofit-row portlet-header">
        <div class="autofit-col autofit-col-expand">
            <div class="portlet-title-text">
                <?php echo $params['section-name']; ?>
            </div>
        </div>
    </div>

    <div class="links-container<?php echo ($params['compact-links'] == 1) ? " links-compact": "";?>">
        <?php for ($i = 1; $i <= $params['link-amount'] ; $i++) : ?>
            <div class="link-col">
                <a href="<?php echo $params['url-link-' . $i]; ?>" target="_blank" class="link-link">
                    <div class="link-card br-card">
                        <div class="link-content">
                            <!-- Exibe o ícone somente se o compact-links estiver desativado -->
                            <?php if($params['compact-links'] == 0) : ?> 
                                <i class="<?php echo $params['icon-link-'. $i ]?> tema-<?php echo $app->getConfig()['themeParams']['tema']; ?>-third" style="font-size:40px;margin:10px 0"></i>
                            <?php endif; ?>    
                            <h5 class="link-title"><?php echo $params['name-link-' . $i]; ?></h5>
                        </div>
                    </div>
                </a>
            </div>
        <?php endfor; ?>
    </div>
</div>
