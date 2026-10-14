<?php

/**
 * @package     IDS Gov - Exército Brasileiro (template)
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/** @var Joomla\CMS\Document\HtmlDocument $this */

// Registra CSS/JS e prepara as variáveis usadas abaixo ($tema, $logo, $titulo, $e ...)
require __DIR__ . '/includes/setup.php';

// Título fixo para todas as páginas (opcional, aba Identidade)
$tituloFixo = trim((string) $this->params->get('title_page', ''));

if ($tituloFixo !== '') {
    $this->setTitle($tituloFixo);
}

if (!$this->getMetaData('author')) {
    $this->setMetaData('author', 'Sgt Souza Lima - 1o CTA');
}

$menu          = $app->getMenu();
$itemAtivo     = $menu->getActive();
$itemInicial   = $menu->getDefault();
$paginaInicial = $itemAtivo && $itemInicial && (int) $itemAtivo->id === (int) $itemInicial->id;
$siorg         = rawurlencode((string) $this->params->get('atendimentocidadao_siorg', ''));
$falaBr        = 'https://falabr.cgu.gov.br/publico/Manifestacao/RegistrarManifestacao.aspx?orgaoDestinatario=' . $siorg . '&amp;tipo=';
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

<head>
    <!-- Desenvolvido por Sgt Souza Lima • GitHub: https://github.com/astatonn • LinkedIn: https://www.linkedin.com/in/astatonn -->
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
</head>

<body>
    <div class="template-base">
        <?php require __DIR__ . '/includes/header.php'; ?>

        <main class="d-flex flex-fill mb-5" id="main">
            <div class="<?php echo $largura; ?>">
                <jdoc:include type="modules" name="main-top" />
                <div class="row">
                    <?php require __DIR__ . '/includes/menu.php'; ?>
                    <div class="col">
                        <?php if (!$paginaInicial) : ?>
                            <jdoc:include type="modules" name="breadcrumbs" />
                        <?php endif; ?>
                        <div class="main-content  pl-sm-3 tema-<?php echo $tema; ?>-main-content" id="main-content">
                            <jdoc:include type="message" />
                            <jdoc:include type="modules" name="content-top" />
                            <jdoc:include type="component" />
                            <jdoc:include type="modules" name="content-bottom" />
                        </div>

                        <?php if ($paginaInicial && $this->params->get('componente_atendimentocidadao', '1')) : ?>
                            <div id="ouvidoria-acessoinformacao">
                                <div class="row d-block mx-auto text-center" id="ouvidoria-acessoinformacao-header">
                                    <h1 class="tema-<?php echo $tema; ?>-third">Ouvidoria e Acesso à Informação</h1>
                                    <h2>Você pode realizar manifestações nos seguintes canais</h2>
                                </div>
                                <div class="row mx-auto px-3 " id="ouvidoria-acessoinformacao-cards">
                                    <?php
                                    $canais = [
                                        1 => ['Denúncia', 'fa-bullhorn'],
                                        5 => ['Solicitação', 'fa-comments'],
                                        3 => ['Elogio', 'fa-thumbs-up'],
                                        2 => ['Reclamação', 'fa-thumbs-down'],
                                    ];
                                    ?>
                                    <?php foreach ($canais as $tipo => [$rotulo, $icone]) : ?>
                                        <div class="col-md">
                                            <div class="br-card hover">
                                                <a href="<?php echo $falaBr . $tipo; ?>">
                                                    <div class="card-content d-flex flex-column justify-content-center align-items-center">
                                                        <i class="fas <?php echo $icone; ?> tema-<?php echo $tema; ?>-third" aria-hidden="true"></i><span class="br-engengrau"><?php echo $rotulo; ?></span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <jdoc:include type="modules" name="main-bottom" />
            </div>
        </main>

        <?php require __DIR__ . '/includes/footer.php'; ?>
</body>

</html>
