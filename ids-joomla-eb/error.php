<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

$app = Factory::getApplication();
$wa  = $this->getWebAssetManager();

$wa->registerAndUseStyle('font-rawline', 'https://fonts.cdnfonts.com/css/rawline');
$wa->registerAndUseStyle('dsgov-core-css', 'media/templates/site/govbr-ds/css/core.css');
$wa->registerAndUseStyle('fontawesome-all', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
$wa->registerAndUseStyle('dsgov-custom', 'media/templates/site/govbr-ds/css/custom.css');
$wa->registerAndUseScript('dsgov-core-js', 'media/templates/site/govbr-ds/js/core-init.js');
$wa->registerAndUseScript('dsgov-contrast-js', 'media/templates/site/govbr-ds/js/contrast.class.js');
$wa->registerAndUseScript('dsgov-backtotop-js', 'media/templates/site/govbr-ds/js/backtotop.js');
if ($this->params->get('cookies_aviso')):
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

$error_code = $this->error->getCode();
$this->setTitle("Erro " . $error_code . " - " . htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8'));
$error_img = Uri::root() . "/media/templates/site/govbr-ds/img/img.png";
switch ($error_code)
{
    case 403:
        $error_img = Uri::root() . "media/templates/site/govbr-ds/img/error403.png";
        break;
    case 404:
        $error_img = Uri::root() . "media/templates/site/govbr-ds/img/error404.png";
        break;
}

$largura = $this->params->get('largura') ? 'container-fluid' : 'container-lg';
$tipomenu = $this->params->get('tipomenu') ? 'br-menu push' : 'br-menu';
$cor_footer = $this->params->get('cor_footer') ? 'br-footer inverted pt-3' : 'br-footer pt-3';
$logo_footer = $this->params->get('cor_footer') ? 'media/templates/site/govbr-ds/img/logo.svg' : 'media/templates/site/govbr-ds/img/logo_footer.svg';
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
    <head>
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
                        <div class="header-logo">
                            <?php if ($this->params->get('logo_comlink', '0') == 1) : ?>
                            <a href="<?php echo Uri::root(); ?>">
                            <?php elseif ($this->params->get('logo_comlink', '0') == 2) : ?>
                            <a href="<?php echo $this->params->get('logo_link', ''); ?>">
                            <?php endif; ?>
                            <img src="<?php echo $this->params->get('imagem_logo', Uri::root() . 'media/templates/site/govbr-ds/img/logo.svg'); ?>" alt="logo" />
                            <?php if ($this->params->get('logo_comlink', '0') != 0) : ?>
                            </a>
                            <?php endif; ?>
                            <?php if (!empty($this->params->get('texto_assinatura', ''))) : ?>
                                <span class="br-divider vertical mx-half mx-sm-1"></span>
                                <div class="header-sign"><?php echo $this->params->get('texto_assinatura', ''); ?></div>
                            <?php endif; ?>
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
                                        <button class="br-button circle small" type="button" aria-label="Alto contraste" onclick="window.toggleContrast()"><i class="fas fa-adjust" aria-hidden="true"></i><span class="text">Alto contraste</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php if ($this->countModules("search")) : ?>
                                <div class="header-search-trigger">
                                    <button class="br-button circle" type="button" aria-label="Abrir Busca" data-toggle="search" data-target=".header-search"><i class="fas fa-search" aria-hidden="true"></i>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="header-bottom">
                        <div class="header-menu">
                            <div class="header-menu-trigger" id="header-navigation">
                                <button class="br-button small circle" type="button" aria-label="Menu" data-toggle="menu" data-target="#main-navigation" id="navigation"><i class="fas fa-bars" aria-hidden="true"></i>
                                </button>
                            </div>
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
                        <jdoc:include type="modules" name="search" style="none" />
                    </div>
                </div>
            </header>

            <main class="d-flex flex-fill mb-5" id="main">
                <div class="<?php echo $largura ?>">
                    <jdoc:include type="modules" name="main-top" />
                    <div class="row">
                        <div class="<?php echo $tipomenu?>" id="main-navigation">
                            <div class="menu-container">
                                <div class="menu-panel">
                                    <div class="menu-header">
                                        <div class="menu-title">
                                            <?php if ($this->params->get('cabecalhomenu', '1') == 2 || $this->params->get('cabecalhomenu', '1') == 1) : ?>
                                            <img src="<?php echo $this->params->get('imagem_logo', Uri::root() . 'media/templates/site/govbr-ds/img/logo.svg'); ?>" alt="Imagem ilustrativa"/>
                                            <?php endif; ?>
                                            <?php if ($this->params->get('cabecalhomenu', '1') == 3 || $this->params->get('cabecalhomenu', '1') == 1) : ?>
                                            <span><?php echo $this->params->get('texto_titulo', 'Template básico'); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="menu-close">
                                            <button class="br-button circle" type="button" aria-label="Fechar o menu" data-dismiss="menu"><i class="fas fa-times" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                    <nav class="menu-body">
                                        <jdoc:include type="modules" name="menu-body" style="none" />
                                    </nav>
                                    <div class="menu-footer">
                                        <?php if ($this->countModules('menu-logos')) : ?>
                                        <div class="menu-logos">
                                            <jdoc:include type="modules" name="menu-logos" style="none" />
                                        </div>
                                        <?php endif; ?>
                                        <?php if ($this->countModules('menu-links')) : ?>
                                        <div class="menu-links">
                                            <jdoc:include type="modules" name="menu-links" style="none" />
                                        </div>
                                        <?php endif; ?>
                                        <?php if ($this->params->get('redes_sociais', '1')) : ?>
                                        <div class="social-network">
                                            <div class="social-network-title">Redes Sociais</div>
                                            <div class="d-flex">
                                                <?php if (!empty($this->params->get('redes_instagram', ''))) : ?>
                                                <a class="br-button circle" href="<?php echo $this->params->get('redes_instagram', ''); ?>" aria-label="Instagram"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                                                <?php endif; ?>
                                                <?php if (!empty($this->params->get('redes_twitter', ''))) : ?>
                                                <a class="br-button circle" href="<?php echo $this->params->get('redes_twitter', ''); ?>" aria-label="Twitter"><i class="fab fa-twitter" aria-hidden="true"></i></a>
                                                <?php endif; ?>
                                                <?php if (!empty($this->params->get('redes_facebook', ''))) : ?>
                                                <a class="br-button circle" href="<?php echo $this->params->get('redes_facebook', ''); ?>" aria-label="Facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
                                                <?php endif; ?>
                                                <?php if (!empty($this->params->get('redes_youtube', ''))) : ?>
                                                <a class="br-button circle" href="<?php echo $this->params->get('redes_youtube', ''); ?>" aria-label="YouTube"><i class="fab fa-youtube" aria-hidden="true"></i></a>
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
                            <jdoc:include type="modules" name="breadcrumbs" />
                            <div class="main-content pl-sm-3" id="main-content">
                                <jdoc:include type="modules" name="content-top" />
                                <div class="template-erro">
                                    <div class="row">
                                        <div class="col-sm-4 d-flex align-items-center justify-content-center">
                                            <div class="mt-4 mt-sm-0"><img src="<?php echo $error_img ?>" alt="imagem de erro"/></div>
                                        </div>
                                        <div class="col align-self-center text-center text-sm-left">
                                            <div class="text-support-03">
                                                <p class="text-up-06 text-semi-bold my-3">
                                                    Estamos constrangidos em te ver por aqui
                                                </p>
                                            </div>
                                            <div class="text-secondary-06">
                                                <p class="text-up-03 text-medium my-3">
                                                    Mas, podemos ajudá-lo a encontrar o que está procurando de outra forma
                                                </p>
                                            </div>
                                            <p class="my-3">
                                                Talvez você tenha se equivocado ao digitar o endereço URL ou quem sabe nós tenhamos cometido uma falha por aqui. Se possível, relate o erro para
                                                que possamos sempre estar melhorando.
                                            </p>
                                        </div>
                                    </div>
                                    <?php if ($this->debug) : ?>
                                    <div class="row">
                                        <p>
                                            <?php echo $this->error->getCode(); ?> - <?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8'); ?>
                                            <br><?php echo htmlspecialchars($this->error->getFile(), ENT_QUOTES, 'UTF-8');?>:<?php echo $this->error->getLine(); ?>
                                        </p>
                                    </div>
                                    <div class="row">
                                        <?php echo $this->renderBacktrace(); ?>
                                        <?php // Check if there are more Exceptions and render their data as well ?>
                                        <?php if ($this->error->getPrevious()) : ?>
                                            <?php $loop = true; ?>
                                            <?php // Reference $this->_error here and in the loop as setError() assigns errors to this property and we need this for the backtrace to work correctly ?>
                                            <?php // Make the first assignment to setError() outside the loop so the loop does not skip Exceptions ?>
                                            <?php $this->setError($this->_error->getPrevious()); ?>
                                            <?php while ($loop === true) : ?>
                                                <p><strong><?php echo Text::_('JERROR_LAYOUT_PREVIOUS_ERROR'); ?></strong></p>
                                                <p>
                                                    <?php echo htmlspecialchars($this->_error->getMessage(), ENT_QUOTES, 'UTF-8'); ?>
                                                    <br><?php echo htmlspecialchars($this->_error->getFile(), ENT_QUOTES, 'UTF-8');?>:<?php echo $this->_error->getLine(); ?>
                                                </p>
                                                <?php echo $this->renderBacktrace(); ?>
                                                <?php $loop = $this->setError($this->_error->getPrevious()); ?>
                                            <?php endwhile; ?>
                                            <?php // Reset the main error object to the base error ?>
                                            <?php $this->setError($this->error); ?>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                    <jdoc:include type="modules" name="error-search" style="none" />
                                    <div class="row my-5 text-center justify-content-lg-center">
                                        <div class="col-lg-auto">
                                            <button class="br-button crumb" type="button" onclick="history.back()"><i class="fas fa-chevron-left" aria-hidden="true"></i><span>Ir para Página Anterior</span>
                                            </button>
                                        </div>
                                        <div class="col-lg-auto mt-1 mt-lg-0">
                                            <button class="br-button crumb" type="button" onclick="location.href = '/'"><i class="fas fa-home" aria-hidden="true"></i><span>Ir para Página Principal</span>
                                            </button>
                                        </div>
                                        <div class="col-lg-auto mt-1 mt-lg-0">
                                            <button class="br-button crumb" type="button"><i class="fas fa-comment-dots" aria-hidden="true"></i><span>Envie um Feedback</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <jdoc:include type="modules" name="content-bottom" />
                            </div>
                        </div>
                    </div>
                    <jdoc:include type="modules" name="main-bottom" />
                </div>
            </main>
            <footer class="<?php echo $cor_footer ?>" id="footer">
				<div class="<?php echo $largura ?>">
					<div class="logo"><img src="<?php echo $this->params->get('imagem_logo_footer', Uri::root() . $logo_footer); ?>" alt="Imagem"/></div>
                    <?php if ($this->countModules('menumapa')) : ?>
                        <div class="br-list horizontal" data-toggle="data-toggle" data-sub="data-sub">
                            <jdoc:include type="modules" name="menumapa" style="none" />
                        </div>
                    <?php endif; ?>
					<div class="d-none d-sm-block">
						<div class="row align-items-end justify-content-between py-5">
                            <?php if ($this->params->get('redes_sociais', '1')) : ?>
                                <div class="col social-network">
                                    <p class="text-up-01 text-extra-bold text-uppercase">Redes Sociais</p>
                                    <?php if (!empty($this->params->get('redes_instagram', ''))) : ?>
                                        <a class="br-button circle large mr-3" href="<?php echo $this->params->get('redes_instagram', ''); ?>" aria-label="Instagram"><i aria-hidden="true" class="fab fa-instagram"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($this->params->get('redes_twitter', ''))) : ?>
                                        <a class="br-button circle large mr-3" href="<?php echo $this->params->get('redes_twitter', ''); ?>" aria-label="Twitter"><i aria-hidden="true" class="fab fa-twitter"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($this->params->get('redes_facebook', ''))) : ?>
                                        <a class="br-button circle large mr-3" href="<?php echo $this->params->get('redes_facebook', ''); ?>" aria-label="Facebook"><i aria-hidden="true" class="fab fa-facebook"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($this->params->get('redes_youtube', ''))) : ?>
                                        <a class="br-button circle large mr-3" href="<?php echo $this->params->get('redes_youtube', ''); ?>" aria-label="YouTube"><i aria-hidden="true" class="fab fa-youtube"></i></a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
							<div class="col assigns text-right">
								<?php if ($this->params->get('imagem_footer_acesso', '1')) : ?>
									<a class="d-inline-block ml-4" href="https://www.gov.br/acessoainformacao"><img src="<?php echo $this->params->get('cor_footer') ? Uri::root() . 'media/templates/site/govbr-ds/img/acesso_footer_claro.svg' : Uri::root() . 'media/templates/site/govbr-ds/img/acesso_footer.svg'; ?>" alt="Acesso a Informação"/></a>
								<?php endif; ?>
								<?php if ($this->params->get('imagem_footer_brasil', '1')) : ?>
									<a class="d-inline-block ml-4" href="https://www.gov.br/"><img src="<?php echo $this->params->get('imagem_footer_brasil_src', Uri::root() . 'media/templates/site/govbr-ds/img/brasil_footer.svg')?>" alt="Imagem"/></a>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<span class="br-divider my-3"></span>
                <div class="<?php echo $largura ?>">
                    <div class="info">
                        <div class="text-down-01 text-medium pb-3">
                            <?php echo $this->params->get('texto_licenca', 'Desenvolvido com o CMS de c&oacute;digo aberto <strong><a aria-label="Desenvolvido por Comunidade Joomla" href="https://www.joomla.org">Joomla</a></strong>.'); ?>
                        </div>
                    </div>
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
</html>
