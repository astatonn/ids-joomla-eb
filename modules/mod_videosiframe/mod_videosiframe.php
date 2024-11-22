<?php
defined('_JEXEC') or die;

// Obter os parâmetros do módulo
$videoAmount = (int) $params->get('video-amount', 4);
$videoWidth = (int) $params->get('video-width', 560);
$videoHeight = (int) $params->get('video-height', 315);

// Obter links dos vídeos
$videos = [];
for ($i = 1; $i <= $videoAmount; $i++) {
    $videoLink = $params->get("video-link-$i");
    if (!empty($videoLink)) {
        $videos[] = $videoLink;
    }
}

// Clonar os itens no início e no final para loop infinito
$clonedVideos = array_merge($videos, $videos, $videos);

// Gerar HTML
?>
<style>
    /* Contêiner principal que limita o carrossel */
    .video-carousel-container {
        position: relative;
        overflow: hidden;
        width: 100%;
        margin: auto;
    }

    /* Carrossel flexível */
    .video-carousel {
        display: flex;
        gap: 20px;
        animation: slide 15s linear infinite;
        /* Tempo aumentado para 15s */
    }

    /* Cada vídeo ocupa 25% da largura */
    .video-item {
        flex: 1 0 25%;
        box-sizing: border-box;
    }

    /* Animação que desliza continuamente */
    @keyframes slide {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-100%);
        }
    }

    /* Pausar o carrossel ao passar o mouse */
    .video-carousel-container:hover .video-carousel {
        animation-play-state: paused;
    }
</style>

<div class="container" style="margin-bottom:50px">
    <!-- HEADER  -->
    <div class="autofit-float autofit-row portlet-header">
        <div class="autofit-col autofit-col-expand">
            <div class="portlet-title-text">
                Vídeos
            </div>
        </div>
    </div>
    <div class="video-carousel-container">
        <div class="video-carousel">
            <?php foreach ($clonedVideos as $video): ?>
                <?php
                // Extrair o ID do vídeo do YouTube
                preg_match('/(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $video, $matches);
                $videoId = $matches[1] ?? null;
                if (!$videoId) {
                    continue; // Ignorar vídeos inválidos
                }
                ?>
                <div class="video-item">
                    <iframe width="<?php echo $videoWidth; ?>" height="<?php echo $videoHeight; ?>"
                        src="https://www.youtube.com/embed/<?php echo $videoId; ?>"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen>
                    </iframe>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>