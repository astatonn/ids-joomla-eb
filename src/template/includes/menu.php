<?php

/**
 * @package     IDS Gov - Exército Brasileiro (template)
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 *
 * Menu principal lateral (br-menu).
 * Parte compartilhada por index.php e error.php (usa as variáveis de includes/setup.php).
 */

defined('_JEXEC') or die;

/** @var Joomla\CMS\Document\HtmlDocument $this */
?>
                    <div class="<?php echo $tipomenu; ?>" id="main-navigation">
                        <div class="menu-container">
                            <div class="menu-panel">
                                <div class="menu-header">
                                    <div class="menu-title">
                                        <?php if ($cabecalho === 1 || $cabecalho === 2) : ?>
                                            <img src="<?php echo $logo; ?>" alt="Imagem ilustrativa" />
                                        <?php endif; ?>
                                        <?php if ($cabecalho === 1 || $cabecalho === 3) : ?>
                                            <span><?php echo $titulo; ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="menu-close">
                                        <button class="br-button circle" type="button" aria-label="Fechar o menu" data-dismiss="menu"><i class="fas fa-times tema-<?php echo $tema; ?>-main" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <nav class="menu-body tema-<?php echo $tema; ?>-menu-body">
                                    <jdoc:include type="modules" name="menu-body" style="none" />
                                </nav>
                                <div class="menu-footer">
                                    <?php if ($this->countModules('menu-logos')) : ?>
                                        <div class="menu-logos">
                                            <jdoc:include type="modules" name="menu-logos" style="none" />
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($this->countModules('menu-links')) : ?>
                                        <div class="menu-links tema-<?php echo $tema; ?>-menu-links">
                                            <jdoc:include type="modules" name="menu-links" style="none" />
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($redesSociais) : ?>
                                        <div class="social-network">
                                            <div class="social-network-title">Redes Sociais</div>
                                            <div class="d-flex">
                                                <?php foreach ($redesSociais as $rede) : ?>
                                                    <a class="br-button circle" href="<?php echo $rede['url']; ?>" aria-label="<?php echo $rede['rotulo']; ?>"><i class="<?php echo $rede['icone']; ?> tema-<?php echo $tema; ?>-main" aria-hidden="true"></i></a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (trim((string) $this->params->get('texto_menuinfo', '')) !== '') : ?>
                                        <div class="menu-info">
                                            <div class="text-center text-down-01"><?php echo $this->params->get('texto_menuinfo'); ?></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="menu-scrim" data-dismiss="menu" tabindex="0"></div>
                        </div>
                    </div>
