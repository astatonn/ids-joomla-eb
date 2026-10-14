<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  mod_links
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * @var string  $titulo    Título da seção
 * @var bool    $compacto  Lista compacta (sem ícones)?
 * @var array   $links     Links: nome, url, icone, novaAba
 * @var string  $tema      Tema de cores do template
 */

if (!$links) {
    return;
}

$esc = static fn ($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
?>
<div class="container mod-links">
    <?php if ($titulo !== '') : ?>
        <div class="autofit-float autofit-row portlet-header">
            <div class="autofit-col autofit-col-expand">
                <div class="portlet-title-text">
                    <?php echo $esc($titulo); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="links-container<?php echo $compacto ? ' links-compact' : ''; ?>">
        <?php foreach ($links as $link) : ?>
            <div class="link-col">
                <a href="<?php echo $esc($link['url']); ?>" class="link-link"<?php echo $link['novaAba'] ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
                    <div class="link-card br-card">
                        <div class="link-content">
                            <?php if (!$compacto && $link['icone'] !== '') : ?>
                                <i class="<?php echo $esc($link['icone']); ?> tema-<?php echo $esc($tema); ?>-third" style="font-size:40px;margin:10px 0" aria-hidden="true"></i>
                            <?php endif; ?>
                            <h5 class="link-title"><?php echo $esc($link['nome']); ?></h5>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
