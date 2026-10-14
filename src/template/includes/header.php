<?php

/**
 * @package     IDS Gov - Exército Brasileiro (template)
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 *
 * Links de salto e cabeçalho (br-header).
 * Parte compartilhada por index.php e error.php (usa as variáveis de includes/setup.php).
 */

defined('_JEXEC') or die;

/** @var Joomla\CMS\Document\HtmlDocument $this */
?>
        <nav class="br-skiplink">
            <a class="br-item" href="#main-content" accesskey="1">Ir para o conteúdo (1/4) <span class="br-tag text ml-1">1</span></a>
            <a class="br-item" href="#header-navigation" accesskey="2">Ir para o menu (2/4) <span class="br-tag text ml-1">2</span></a>
            <a class="br-item" href="#main-searchbox" accesskey="3">Ir para a busca (3/4) <span class="br-tag text ml-1">3</span></a>
            <a class="br-item" href="#footer" accesskey="4">Ir para o rodapé (4/4) <span class="br-tag text ml-1">4</span></a>
        </nav>

        <header class="br-header mb-4" id="header" data-sticky="data-sticky">
            <div class="<?php echo $largura; ?>">
                <div class="header-top">
                    <div class="menu-align-top">
                        <div class="header-menu">
                            <div class="header-menu-trigger" id="header-navigation">
                                <?php if (!$menuHorizontal) : ?>
                                    <button class="br-button small circle" type="button" aria-label="Menu" data-toggle="menu" data-target="#main-navigation" id="navigation"><i class="fas fa-bars tema-<?php echo $tema; ?>-main" style="font-size: 25px;" aria-hidden="true"></i>
                                    </button>
                                <?php else : ?>
                                    <button class="br-button small circle" type="button" aria-label="Menu" aria-controls="menu-box-horizontal" aria-expanded="false" id="header-navigation2"><i class="fas fa-bars tema-<?php echo $tema; ?>-main" style="font-size: 25px;" aria-hidden="true"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="header-logo">
                        <div class="logo-om">
                            <?php if ($logoComLink === 1) : ?>
                                <a href="<?php echo $rootUrl; ?>"><img src="<?php echo $logo; ?>" alt="logo" /></a>
                            <?php elseif ($logoComLink === 2 && $logoLink !== '') : ?>
                                <a href="<?php echo $logoLink; ?>"><img src="<?php echo $logo; ?>" alt="logo" /></a>
                            <?php else : ?>
                                <img src="<?php echo $logo; ?>" alt="logo" />
                            <?php endif; ?>
                            <?php if ($assinatura !== '') : ?>
                                <span class="br-divider vertical mx-half mx-sm-1"></span>
                                <div class="header-sign"><?php echo $assinatura; ?></div>
                            <?php endif; ?>
                        </div>
                        <span class="br-divider vertical"></span>

                        <div class="header-info">
                            <div class="header-title">
                                <a href="<?php echo $rootUrl; ?>" title="<?php echo $titulo; ?>">
                                    <?php echo $titulo; ?>
                                </a>
                            </div>
                            <?php if ($subtitulo !== '') : ?>
                                <div class="header-subtitle"><?php echo $subtitulo; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="header-govlinks tema-<?php echo $tema; ?>-main">
                        <ul aria-label="Páginas do site">
                            <li>
                                <a href="https://www.gov.br/defesa/" class="header-sign h8 m-0 header-sign-border tema-<?php echo $tema; ?>-main-header-border">Ministério da Defesa</a>
                            </li>
                            <li>
                                <a href="https://www.gov.br/pt-br/orgaos-do-governo" target="_blank" rel="noopener">
                                    <span> Órgãos do Governo </span>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.gov.br/acessoainformacao/pt-br" target="_blank" rel="noopener">
                                    <span class="text-truncate"> Acesso à Informação </span>
                                </a>
                            </li>
                            <li>
                                <a href="https://www4.planalto.gov.br/legislacao" target="_blank" rel="noopener">
                                    <span> Legislação </span>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.gov.br/governodigital/pt-br/acessibilidade-digital" target="_blank" rel="noopener">
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
                                    <button class="br-button circle small" type="button" aria-label="Alto contraste" onclick="window.toggleContrast()"><i class="fas fa-adjust tema-<?php echo $tema; ?>-main" aria-hidden="true"></i><span class="text">Alto contraste</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php if ($this->countModules('search')) : ?>
                            <div class="header-search-trigger">
                                <button class="br-button circle" type="button" aria-label="Abrir Busca" data-toggle="search" data-target=".header-search"><i class="fas fa-search tema-<?php echo $tema; ?>-main" aria-hidden="true"></i>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="header-bottom tema-<?php echo $tema; ?>-main-search">
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
