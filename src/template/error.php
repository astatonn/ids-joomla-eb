<?php

/**
 * @package     IDS Gov - Exército Brasileiro (template)
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/** @var Joomla\CMS\Document\ErrorDocument $this */

// Registra CSS/JS e prepara as variáveis usadas abaixo ($tema, $logo, $titulo, $e ...)
require __DIR__ . '/includes/setup.php';

$codigoErro   = (int) $this->error->getCode();
$mensagemErro = $e($this->error->getMessage());

$this->setTitle('Erro ' . $codigoErro . ' - ' . $mensagemErro);

$imagensErro = [403 => 'img/error403.png', 404 => 'img/error404.png'];
$imagemErro  = $img('', $mediaPath . ($imagensErro[$codigoErro] ?? 'img/img.png'));

// Crédito exibido no rodapé da página de erro (como nas versões anteriores do template)
$rodapeExtra = 'Desenvolvido por 3º Sgt Int/2017 Souza Lima • <a href="https://github.com/astatonn">GitHub</a> • '
    . '<a href="https://www.linkedin.com/in/lucas-lima-477377a5/">LinkedIn</a> - 1º Centro de Telemática de Área';
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

<head>
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
                        <jdoc:include type="modules" name="breadcrumbs" />
                        <div class="main-content pl-sm-3 tema-<?php echo $tema; ?>-main-content" id="main-content">
                            <jdoc:include type="modules" name="content-top" />
                            <div class="template-erro">
                                <div class="row">
                                    <div class="col-sm-4 d-flex align-items-center justify-content-center">
                                        <div class="mt-4 mt-sm-0"><img src="<?php echo $imagemErro; ?>" alt="imagem de erro" /></div>
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
                                            <?php echo $codigoErro; ?> - <?php echo $mensagemErro; ?>
                                            <br><?php echo $e($this->error->getFile()); ?>:<?php echo (int) $this->error->getLine(); ?>
                                        </p>
                                    </div>
                                    <div class="row">
                                        <?php echo $this->renderBacktrace(); ?>
                                        <?php // Exceções encadeadas: mostra cada uma com o respectivo backtrace ?>
                                        <?php if ($this->error->getPrevious()) : ?>
                                            <?php $loop = true; ?>
                                            <?php // setError() troca o erro corrente; _error é usado pelo renderBacktrace() ?>
                                            <?php $this->setError($this->_error->getPrevious()); ?>
                                            <?php while ($loop === true) : ?>
                                                <p><strong><?php echo Text::_('JERROR_LAYOUT_PREVIOUS_ERROR'); ?></strong></p>
                                                <p>
                                                    <?php echo $e($this->_error->getMessage()); ?>
                                                    <br><?php echo $e($this->_error->getFile()); ?>:<?php echo (int) $this->_error->getLine(); ?>
                                                </p>
                                                <?php echo $this->renderBacktrace(); ?>
                                                <?php $loop = $this->setError($this->_error->getPrevious()); ?>
                                            <?php endwhile; ?>
                                            <?php // Restaura o erro principal ?>
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
                                        <a class="br-button crumb" href="<?php echo $rootUrl; ?>"><i class="fas fa-home" aria-hidden="true"></i><span>Ir para Página Principal</span>
                                        </a>
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

        <?php require __DIR__ . '/includes/footer.php'; ?>
</body>

</html>
