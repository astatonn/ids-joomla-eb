<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

$app = Factory::getApplication();
$wa  = $this->getWebAssetManager();



$wa->registerAndUseStyle('font-rawline', 'media/templates/site/govbr-ds/css/rawline.css');
$wa->registerAndUseStyle('dsgov-core-css', 'media/templates/site/govbr-ds/css/core.css');
$wa->registerAndUseStyle('fontawesome-all', 'media/templates/site/govbr-ds/css/all.min.css'); /* font awesome */
$wa->registerAndUseStyle('dsgov-custom', 'media/templates/site/govbr-ds/css/custom.css');
$wa->registerAndUseScript('dsgov-core-js', 'media/templates/site/govbr-ds/js/core-init.js');
$wa->registerAndUseScript('dsgov-contrast-js', 'media/templates/site/govbr-ds/js/contrast.class.js');
$wa->registerAndUseScript('dsgov-backtotop-js', 'media/templates/site/govbr-ds/js/backtotop.js');
if ($this->params->get('cookies_aviso')) :
    $wa->registerAndUseScript('cookie-notice', 'media/templates/site/govbr-ds/js/cookie.notice.js');
    $wa->addInlineScript("new cookieNoticeJS({
                            'noticeText':'" . $this->params->get('cookies_mensagem') . "',
                            'learnMoreLinkEnabled':" . ($this->params->get('cookies_leiamais') ? "true" : "false") . "," .
        (($this->params->get('cookies_leiamais')) ?
            "'learnMoreLinkHref':'" . $this->params->get('cookies_leiamais_link') . "',
                                'learnMoreLinkText':'" . $this->params->get('cookies_leiamais_titulo') . "'," : '')
        . "'buttonText': '" . $this->params->get('cookies_ciente_titulo') . "'
                          });");
endif;
$wa->registerAndUseScript('vlibras-plugin', 'https://vlibras.gov.br/app/vlibras-plugin.js');
$wa->addInlineScript("new window.VLibras.Widget('https://vlibras.gov.br/app');");

$this->addHeadLink(HTMLHelper::_('image', 'media/templates/site/govbr-ds/favicons/apple-touch-icon.png', '', [], false, 1), 'apple-touch-icon', 'rel', ['sizes' => '180x180']);
$this->addHeadLink(HTMLHelper::_('image', 'media/templates/site/govbr-ds/favicons/favicon-32x32.png', '', [], false, 1), 'icon', 'rel', ['type' => 'image/png', 'sizes' => '32x32']);
$this->addHeadLink(HTMLHelper::_('image', 'media/templates/site/govbr-ds/favicons/favicon-16x16.png', '', [], false, 1), 'icon', 'rel', ['type' => 'image/png', 'sizes' => '16x16']);
$this->addHeadLink(HTMLHelper::_('image', 'media/templates/site/govbr-ds/favicons/manifest.json', '', [], false, 1), 'manifest', 'rel', []);
$this->addHeadLink(HTMLHelper::_('image', 'media/templates/site/govbr-ds/favicons/safari-pinned-tab.svg', '', [], false, 1), 'mask-icon', 'rel', ['color' => '#00a300']);
$this->setMetaData('msapplication-config', 'media/templates/site/govbr-ds/favicons/browserconfig.xml');
$this->setMetaData('theme-color', '#00a300');
$this->setMetaData('viewport', 'width=device-width, initial-scale=1');

$largura = $this->params->get('largura') ? 'container-fluid' : 'container-lg';
$tipomenu = $this->params->get('tipomenu') ? 'br-menu push' : 'br-menu';
$cor_footer = $this->params->get('cor_footer') ? 'br-footer inverted pt-3' : 'br-footer pt-3';
$logo_footer = $this->params->get('cor_footer') ? 'media/templates/site/govbr-ds/img/logo.svg' : 'media/templates/site/govbr-ds/img/logo_footer.svg';
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

<head>
    <?php if (!empty($this->params->get('title_page', ''))) : ?>
        <title><?php echo $this->params->get('title_page', '') ?></title>
        <!-- Meta tag para autor do template -->
        <meta name="author" content="Sgt Souza Lima - 1o CTA">
        <!-- Comentário HTML com links para redes sociais -->
        <!--Desenvolvido por Sgt Souza Lima • Github: https://github.com/astatonn • LinkedIn: https://www.linkedin.com/in/astatonn-->
    <?php endif ?>
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
</head>

<body>
    <div class="template-base">
        <nav class="br-skiplink">
            <a class="br-item" href="#main-content" accesskey="1">Ir para o conteúdo (1/4) <span class="br-tag text ml-1">1</span></a>
            <a class="br-item" href="#header-navigation" accesskey="2">Ir para o menu (2/4) <span class="br-tag text ml-1">2</span></a>
            <a class="br-item" href="#main-searchbox" accesskey="3">Ir para a busca (3/4) <span class="br-tag text ml-1">3</span></a>
            <a class="br-item" href="#footer" accesskey="4">Ir para o rodapé (4/4) <span class="br-tag text ml-1">4</span></a>
        </nav>

        <header class="br-header mb-4" id="header" data-sticky="data-sticky">
            <div class="<?php echo $largura ?>">
                <div class="header-top">
                    <div class="menu-align-top">
                        <div class="header-menu">
                            <?php if ($this->params->get('tipo-menu') == 0): ?>
                                <div class="header-menu-trigger" id="header-navigation">
                                    <button class="br-button small circle" type="button" aria-label="Menu" data-toggle="menu" data-target="#main-navigation" id="navigation"><i class="fas fa-bars tema-<?php echo $this->params->get('tema'); ?>-main" style="font-size: 25px;" aria-hidden="true"></i>
                                    </button>
                                </div>
                            <?php endif; ?>
                            <?php if ($this->params->get('tipo-menu') == 1): ?>
                                <div class="header-menu-trigger" id="header-navigation2">
                                    <button class="br-button small circle" type="button" id="header-navigation2"><i class="fas fa-bars tema-<?php echo $this->params->get('tema'); ?>-main" style="font-size: 25px;" aria-hidden="true"></i>
                                    </button>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                    <div class="header-logo">
                        <div class="logo-om">
                            <?php if ($this->params->get('logo_comlink', '0') == 1) : ?>
                                <a href="<?php echo Uri::root(); ?>">
                                <?php elseif ($this->params->get('logo_comlink', '0') == 2) : ?>
                                    <a href="<?php echo $this->params->get('logo_link', ''); ?>">
                                    <?php endif; ?>
                                    <img src="<?php echo $this->params->get('imagem_logo', 'media/templates/site/govbr-ds/img/logo.svg'); ?>" alt="logo" />
                                    <?php if ($this->params->get('logo_comlink', '0') != 0) : ?>
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($this->params->get('texto_assinatura', ''))) : ?>
                                    <span class="br-divider vertical mx-half mx-sm-1"></span>
                                    <div class="header-sign"><?php echo $this->params->get('texto_assinatura', ''); ?></div>
                                <?php endif; ?>
                        </div>
                        <span class="br-divider vertical"></span>


                        <div class="header-info">
                            <div class="header-title">
                                <a href="<?php echo Uri::root(); ?>" title="<?php echo $this->params->get('texto_titulo', 'Template básico'); ?>">
                                    <?php echo $this->params->get('texto_titulo', 'Template básico'); ?>
                                </a>
                            </div>
                            <?php if (!empty($this->params->get('texto_subtitulo', ''))) : ?>
                                <div class="header-subtitle"><?php echo $this->params->get('texto_subtitulo', 'Subtítulo do Header'); ?></div>
                            <?php endif; ?>
                        </div>


                    </div>
                    <div class="header-govlinks tema-<?php echo $this->params->get('tema'); ?>-main">

                        <ul aria-label="Páginas do site">
                            <li>
                                <a href="https://www.gov.br/defesa/" class="header-sign h8 m-0 header-sign-border tema-<?php echo $this->params->get('tema'); ?>-main-header-border">Ministério da Defesa</a>
                            </li>
                            <li>
                                <a href="https://www.gov.br/pt-br/orgaos-do-governo" target="_blank">
                                    <span> Órgãos do Governo </span>
                                </a>

                            </li>

                            <li>
                                <a href="https://www.gov.br/acessoainformacao/pt-br" target="_blank">
                                    <span class="text-truncate"> Acesso à Informação </span>
                                </a>

                            </li>


                            <li>
                                <a href="https://www4.planalto.gov.br/legislacao" target="_blank">
                                    <span> Legislação </span>
                                </a>

                            </li>

                            <li>
                                <a href="https://www.gov.br/governodigital/pt-br/acessibilidade-digital" target="_blank">
                                    <span> Acessibilidade </span>
                                </a>

                            </li>
                        </ul>
                    </div>
                    <div class="header-actions">
                        <?php if ($this->countModules('menuacesso')) : ?>
                            <div class="header-links dropdown">
                                <button class="br-button circle small" type="button" data-toggle="dropdown" aria-label="Abrir Acesso Rápido"><i class="fas fa-ellipsis-v" aria-hidden="true"></i></button>
                                <div class="br-list">
                                    <div class="header">
                                        <div class="title">Acesso Rápido</div>
                                    </div>
                                    <jdoc:include type="modules" name="menuacesso" style="none" />
                                </div>
                            </div>
                            <span class="br-divider vertical mx-half mx-sm-1"></span>
                        <?php endif; ?>
                        <div class="header-functions dropdown">
                            <button class="br-button circle small" type="button" data-toggle="dropdown" aria-label="Abrir Funcionalidades do Sistema"><i class="fas fa-th" aria-hidden="true"></i>
                            </button>
                            <div class="br-list">
                                <div class="header">
                                    <div class="title">Funcionalidades do Sistema</div>
                                </div>
                                <div class="align-items-center br-item">
                                    <button class="br-button circle small" type="button" aria-label="Alto contraste" onclick="window.toggleContrast()"><i class="fas fa-adjust tema-<?php echo $this->params->get('tema'); ?>-main" aria-hidden="true"></i><span class="text">Alto contraste</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php if ($this->countModules("search")) : ?>
                            <div class="header-search-trigger">
                                <button class="br-button circle" type="button" aria-label="Abrir Busca" data-toggle="search" data-target=".header-search"><i class="fas fa-search tema-<?php echo $this->params->get('tema'); ?>-main" aria-hidden="true"></i>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="header-bottom tema-<?php echo $this->params->get('tema'); ?>-main-search">

                    <jdoc:include type="modules" name="search" style="none" />
                </div>
            </div>
            <div id="menu-box-horizontal" class="menu-box-horizontal">
                <div class="menu-box-horizontal-container">
                    <div class="menu-box-horizontal-row">
                        <jdoc:include type="modules" name="menu-links" style="none" />

                    </div>
                </div>

            </div>
        </header>


        <main class="d-flex flex-fill mb-5" id="main">
            <div class="<?php echo $largura ?>">
                <jdoc:include type="modules" name="main-top" />
                <div class="row">
                    <div class="<?php echo $tipomenu ?>" id="main-navigation">

                        <div class="menu-container">
                            <div class="menu-panel">
                                <div class="menu-header">
                                    <div class="menu-title">
                                        <?php if ($this->params->get('cabecalhomenu', '1') == 2 || $this->params->get('cabecalhomenu', '1') == 1) : ?>
                                            <img src="<?php echo $this->params->get('imagem_logo', 'media/templates/site/govbr-ds/img/logo.svg'); ?>" alt="Imagem ilustrativa" />
                                        <?php endif; ?>
                                        <?php if ($this->params->get('cabecalhomenu', '1') == 3 || $this->params->get('cabecalhomenu', '1') == 1) : ?>
                                            <span><?php echo $this->params->get('texto_titulo', 'Template básico'); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="menu-close">
                                        <button class="br-button circle" type="button" aria-label="Fechar o menu" data-dismiss="menu"><i class="fas fa-times tema-<?php echo $this->params->get('tema'); ?>-main" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <nav class="menu-body tema-<?php echo $this->params->get('tema'); ?>-menu-body">
                                    <jdoc:include type="modules" name="menu-body" style="none" />
                                </nav>
                                <div class="menu-footer">
                                    <?php if ($this->countModules('menu-logos')) : ?>
                                        <div class="menu-logos">
                                            <jdoc:include type="modules" name="menu-logos" style="none" />
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($this->countModules('menu-links')) : ?>
                                        <div class="menu-links tema-<?php echo $this->params->get('tema'); ?>-menu-links">
                                            <jdoc:include type="modules" name="menu-links" style="none" />
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($this->params->get('redes_sociais', '1')) : ?>
                                        <div class="social-network">
                                            <div class="social-network-title">Redes Sociais</div>
                                            <div class="d-flex">
                                                <?php if (!empty($this->params->get('redes_instagram', ''))) : ?>
                                                    <a class="br-button circle" href="<?php echo $this->params->get('redes_instagram', ''); ?>" aria-label="Instagram"><i class="fab fa-instagram tema-<?php echo $this->params->get('tema'); ?>-main" aria-hidden="true"></i></a>
                                                <?php endif; ?>
                                                <?php if (!empty($this->params->get('redes_twitter', ''))) : ?>
                                                    <a class="br-button circle" href="<?php echo $this->params->get('redes_twitter', ''); ?>" aria-label="Twitter"><i class="fab fa-twitter tema-<?php echo $this->params->get('tema'); ?>-main" aria-hidden="true"></i></a>
                                                <?php endif; ?>
                                                <?php if (!empty($this->params->get('redes_facebook', ''))) : ?>
                                                    <a class="br-button circle" href="<?php echo $this->params->get('redes_facebook', ''); ?>" aria-label="Facebook"><i class="fab fa-facebook-f tema-<?php echo $this->params->get('tema'); ?>-main" aria-hidden="true"></i></a>
                                                <?php endif; ?>
                                                <?php if (!empty($this->params->get('redes_youtube', ''))) : ?>
                                                    <a class="br-button circle" href="<?php echo $this->params->get('redes_youtube', ''); ?>" aria-label="YouTube"><i class="fab fa-youtube tema-<?php echo $this->params->get('tema'); ?>-main" aria-hidden="true"></i></a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($this->params->get('texto_menuinfo', ''))) : ?>
                                        <div class="menu-info">
                                            <div class="text-center text-down-01"><?php echo $this->params->get('texto_menuinfo', 'Desenvolvido com o CMS de c&oacute;digo aberto <strong><a aria-label="Desenvolvido por Comunidade Joomla" href="https://www.joomla.org">Joomla</a></strong>.'); ?></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="menu-scrim" data-dismiss="menu" tabindex="0"></div>
                        </div>
                    </div>
                    <div class="col">
                        <?php if ($app->getMenu()->getActive() != $app->getMenu()->getDefault()) : ?>
                            <jdoc:include type="modules" name="breadcrumbs" />
                        <?php endif; ?>
                        <div class="main-content  pl-sm-3 tema-<?php echo $this->params->get('tema'); ?>-main-content" id="main-content">
                            <jdoc:include type="message" />
                            <jdoc:include type="modules" name="content-top" />
                            <jdoc:include type="component" />
                            <jdoc:include type="modules" name="content-bottom" />
                        </div>



                        <?php if ($this->params->get('componente_atendimentocidadao', '1')) : ?>
                            <?php if ($app->getMenu()->getActive() == $app->getMenu()->getDefault()) : ?>
                                <div id="ouvidoria-acessoinformacao">
                                    <div class="row d-block mx-auto text-center" id="ouvidoria-acessoinformacao-header">
                                        <h1 class="tema-<?php echo $this->params->get('tema'); ?>-third">Ouvidoria e Acesso à Informação</h1>
                                        <h2>Você pode realizar manifestações nos seguintes canais</h2>
                                    </div>
                                    <div class="row mx-auto px-3 " id="ouvidoria-acessoinformacao-cards">
                                        <div class="col-md">
                                            <div class="br-card hover">
                                                <a href="https://falabr.cgu.gov.br/publico/Manifestacao/RegistrarManifestacao.aspx?tipo=1&orgaoDestinatario=<?php echo $this->params->get('atendimentocidadao_siorg', ''); ?>">
                                                    <div class="card-content d-flex flex-column justify-content-center align-items-center">
                                                        <i class="fas fa-bullhorn tema-<?php echo $this->params->get('tema'); ?>-third" aria-hidden="true"></i><span class="br-engengrau">Denúncia</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="br-card hover">
                                                <a href="https://falabr.cgu.gov.br/publico/Manifestacao/RegistrarManifestacao.aspx?tipo=5&orgaoDestinatario=<?php echo $this->params->get('atendimentocidadao_siorg', ''); ?>">
                                                    <div class="card-content d-flex flex-column justify-content-center align-items-center">
                                                        <i class="fas fa-comments tema-<?php echo $this->params->get('tema'); ?>-third" aria-hidden="true"></i><span class="br-engengrau">Solicitação</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="br-card hover">
                                                <a href="https://falabr.cgu.gov.br/publico/Manifestacao/RegistrarManifestacao.aspx?tipo=3&orgaoDestinatario=<?php echo $this->params->get('atendimentocidadao_siorg', ''); ?>">
                                                    <div class="card-content d-flex flex-column justify-content-center align-items-center">
                                                        <i class="fas fa-thumbs-up tema-<?php echo $this->params->get('tema'); ?>-third" aria-hidden="true"></i><span class="br-engengrau">Elogio</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="br-card hover">
                                                <a href="https://falabr.cgu.gov.br/publico/Manifestacao/RegistrarManifestacao.aspx?tipo=2&orgaoDestinatario=<?php echo $this->params->get('atendimentocidadao_siorg', ''); ?>">
                                                    <div class="card-content d-flex flex-column justify-content-center align-items-center">
                                                        <i class="fas fa-thumbs-down tema-<?php echo $this->params->get('tema'); ?>-third" aria-hidden="true"></i><span class="br-engengrau">Reclamação</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <jdoc:include type="modules" name="main-bottom" />
            </div>
        </main>

        <footer class="<?php echo $cor_footer ?> tema-<?php echo $this->params->get('tema'); ?>-bg" id="footer">
            <div class="<?php echo $largura ?>">
                <div class="logo"><img src="<?php echo $this->params->get('imagem_logo_footer', $logo_footer); ?>" alt="Imagem" /></div>
                <?php if ($this->countModules('menumapa')) : ?>
                    <div class="horizontal br-footer-menu tema-<?php echo $this->params->get('tema'); ?>-menu" data-toggle="data-toggle" data-sub="data-sub">
                        <jdoc:include type="modules" name="menumapa" style="none" />
                    </div>
                <?php endif; ?>
                <div class="d-none d-sm-block">
                    <div class="row align-items-end justify-content-between py-5">
                        <?php if ($this->params->get('redes_sociais', '1')) : ?>
                            <div class="col social-network">
                                <p class="text-up-01 text-extra-bold text-uppercase">Redes Sociais</p>
                                <?php if (!empty($this->params->get('redes_instagram', ''))) : ?>
                                    <a class="br-button circle large mr-3" href="<?php echo $this->params->get('redes_instagram', ''); ?>" aria-label="Instagram"><i aria-hidden="true" class="fab fa-instagram tema-<?php echo $this->params->get('tema'); ?>-main"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($this->params->get('redes_twitter', ''))) : ?>
                                    <a class="br-button circle large mr-3" href="<?php echo $this->params->get('redes_twitter', ''); ?>" aria-label="Twitter"><i aria-hidden="true" class="fab fa-twitter tema-<?php echo $this->params->get('tema'); ?>-main"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($this->params->get('redes_facebook', ''))) : ?>
                                    <a class="br-button circle large mr-3" href="<?php echo $this->params->get('redes_facebook', ''); ?>" aria-label="Facebook"><i aria-hidden="true" class="fab fa-facebook tema-<?php echo $this->params->get('tema'); ?>-main"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($this->params->get('redes_youtube', ''))) : ?>
                                    <a class="br-button circle large mr-3" href="<?php echo $this->params->get('redes_youtube', ''); ?>" aria-label="YouTube"><i aria-hidden="true" class="fab fa-youtube tema-<?php echo $this->params->get('tema'); ?>-main"></i></a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <div class="col assigns text-right">
                            <?php if ($this->params->get('imagem_footer_acesso', '1')) : ?>
                                <a class="d-inline-block ml-4" href="https://www.gov.br/acessoainformacao"><img src="<?php echo $this->params->get('cor_footer') ? 'media/templates/site/govbr-ds/img/acesso_footer_claro.svg' : 'media/templates/site/govbr-ds/img/acesso_footer.svg'; ?>" alt="Acesso a Informação" /></a>
                            <?php endif; ?>
                            <?php if ($this->params->get('imagem_footer_brasil', '1')) : ?>
                                <a class="d-inline-block ml-4" href="https://www.gov.br/"><img src="<?php echo $this->params->get('imagem_footer_brasil_src', 'media/templates/site/govbr-ds/img/brasil_footer.svg') ?>" alt="Imagem" /></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <span class="br-divider my-3"></span>
            <div class="texto-copyright">
                <?php echo $this->params->get('texto_licenca', ''); ?>
            </div>
        </footer>
        <button id="gotop" class="br-button primary circle backtotop" aria-label="Ir para o topo" onclick="goTop()"><i class="fa fa-chevron-up"></i></button>
        <jdoc:include type="modules" name="debug" style="none" />
    </div>
    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>
</body>
<jdoc:include type="scripts" />
<script>
    document.getElementById('header-navigation2').addEventListener('click', function() {
        var menu = document.getElementById('menu-box-horizontal');
        if (menu.classList.contains('menu-ativo')) {
            menu.classList.remove('menu-ativo');
        } else {

            menu.classList.add('menu-ativo');
        }

    });
</script>

</html>