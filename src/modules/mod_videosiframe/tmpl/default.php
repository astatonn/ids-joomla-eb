<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  mod_videosiframe
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/**
 * @var \Joomla\CMS\Application\CMSApplicationInterface  $app
 * @var \stdClass                                        $module
 * @var string  $titulo     Título da seção
 * @var array   $videos     Vídeos: id (YouTube) e titulo
 * @var int     $largura    Largura de cada vídeo, em pixels
 * @var int     $altura     Altura de cada vídeo, em pixels
 * @var bool    $movimento  Carrossel em movimento contínuo?
 * @var int     $duracao    Duração de uma volta completa, em segundos
 */

if (!$videos) {
    return;
}

/** @var \Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $app->getDocument()->getWebAssetManager();
$wa->getRegistry()->addExtensionRegistryFile('mod_videosiframe');
$wa->useStyle('mod_videosiframe.carrossel');

$esc = static fn ($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');

// Em movimento, a lista é repetida uma vez para a animação recomeçar sem "pulo".
$copias = $movimento ? 2 : 1;
?>
<div class="container mod-videosiframe" style="margin-bottom:50px">
    <?php if ($titulo !== '') : ?>
        <div class="autofit-float autofit-row portlet-header">
            <div class="autofit-col autofit-col-expand">
                <div class="portlet-title-text">
                    <?php echo $esc($titulo); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="video-carousel-container<?php echo $movimento ? ' video-carousel-animado' : ''; ?>" style="--video-largura: <?php echo (int) $largura; ?>px; --video-duracao: <?php echo (int) $duracao; ?>s;">
        <div class="video-carousel">
            <?php for ($copia = 0; $copia < $copias; $copia++) : ?>
                <?php foreach ($videos as $video) : ?>
                    <?php $tituloVideo = $video['titulo'] !== '' ? $video['titulo'] : Text::_('MOD_VIDEOSIFRAME_PLAYER_TITULO'); ?>
                    <div class="video-item"<?php echo $copia > 0 ? ' aria-hidden="true"' : ''; ?>>
                        <iframe
                            width="<?php echo (int) $largura; ?>"
                            height="<?php echo (int) $altura; ?>"
                            src="https://www.youtube.com/embed/<?php echo $esc($video['id']); ?>"
                            title="<?php echo $esc($tituloVideo); ?>"
                            loading="lazy"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin"
                            <?php echo $copia > 0 ? 'tabindex="-1"' : ''; ?>
                            allowfullscreen></iframe>
                    </div>
                <?php endforeach; ?>
            <?php endfor; ?>
        </div>
    </div>
</div>
