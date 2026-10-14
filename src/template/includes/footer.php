<?php

/**
 * @package     IDS Gov - Exército Brasileiro (template)
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 *
 * Rodapé, botão "voltar ao topo", VLibras e scripts.
 * Parte compartilhada por index.php e error.php (usa as variáveis de includes/setup.php).
 */

defined('_JEXEC') or die;

/** @var Joomla\CMS\Document\HtmlDocument $this */
?>
        <footer class="<?php echo $cor_footer; ?> tema-<?php echo $tema; ?>-bg" id="footer">
            <div class="<?php echo $largura; ?>">
                <div class="logo"><img src="<?php echo $logoRodape; ?>" alt="Imagem" /></div>
                <?php if ($this->countModules('menumapa')) : ?>
                    <div class="horizontal br-footer-menu tema-<?php echo $tema; ?>-menu" data-toggle="data-toggle" data-sub="data-sub">
                        <jdoc:include type="modules" name="menumapa" style="none" />
                    </div>
                <?php endif; ?>
                <div class="d-none d-sm-block">
                    <div class="row align-items-end justify-content-between py-5">
                        <?php if ($redesSociais) : ?>
                            <div class="col social-network">
                                <p class="text-up-01 text-extra-bold text-uppercase">Redes Sociais</p>
                                <?php foreach ($redesSociais as $rede) : ?>
                                    <a class="br-button circle large mr-3" href="<?php echo $rede['url']; ?>" aria-label="<?php echo $rede['rotulo']; ?>"><i aria-hidden="true" class="<?php echo $rede['icone']; ?> tema-<?php echo $tema; ?>-main"></i></a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <div class="col assigns text-right">
                            <?php if ($this->params->get('imagem_footer_acesso', '1')) : ?>
                                <a class="d-inline-block ml-4" href="https://www.gov.br/acessoainformacao"><img src="<?php echo $logoAcesso; ?>" alt="Acesso à Informação" /></a>
                            <?php endif; ?>
                            <?php if ($this->params->get('imagem_footer_brasil', '1')) : ?>
                                <a class="d-inline-block ml-4" href="https://www.gov.br/"><img src="<?php echo $logoBrasil; ?>" alt="Governo Federal" /></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <span class="br-divider my-3"></span>
            <div class="texto-copyright  ">
                <?php echo $this->params->get('texto_licenca', ''); ?>
            </div>
            <?php if (!empty($rodapeExtra)) : ?>
                <div class="<?php echo $largura; ?>">
                    <div class="info">
                        <div class="text-down-01 text-medium pb-3"><?php echo $rodapeExtra; ?></div>
                    </div>
                </div>
            <?php endif; ?>
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
    <jdoc:include type="scripts" />
    <?php if ($menuHorizontal) : ?>
        <script>
            (function () {
                var botao = document.getElementById('header-navigation2');
                var menu  = document.getElementById('menu-box-horizontal');

                if (!botao || !menu) {
                    return;
                }

                botao.addEventListener('click', function () {
                    botao.setAttribute('aria-expanded', menu.classList.toggle('menu-ativo') ? 'true' : 'false');
                });
            })();
        </script>
    <?php endif; ?>
