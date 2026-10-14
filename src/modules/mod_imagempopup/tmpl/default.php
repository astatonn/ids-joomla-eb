<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  mod_imagempopup
 * @copyright   (C) 2024-2026 Henrique Michels Voicolesco, Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/**
 * @var \Joomla\CMS\Application\CMSApplicationInterface  $app
 * @var \stdClass                                        $module
 * @var string  $imagem      Endereço da imagem ('' quando não há)
 * @var string  $imagemAlt   Texto alternativo da imagem
 * @var string  $link        Endereço aberto ao clicar na imagem ('' quando não há)
 * @var string  $texto       Texto (HTML) do popup
 * @var int     $segundos    Segundos até fechar sozinho (0 = não fecha sozinho)
 * @var string  $frequencia  sempre | sessao | dia
 * @var bool    $fecharFora  Fechar ao clicar fora da janela?
 * @var string  $assinatura  Identifica o conteúdo atual do popup
 */

/** @var \Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $app->getDocument()->getWebAssetManager();
$wa->getRegistry()->addExtensionRegistryFile('mod_imagempopup');
$wa->useStyle('mod_imagempopup.popup')
    ->useScript('mod_imagempopup.popup');

$esc = static fn ($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
$id  = 'mod-imagempopup-' . (int) $module->id;
?>
<div
    class="overlay mod-imagempopup"
    id="<?php echo $id; ?>"
    role="dialog"
    aria-modal="true"
    aria-label="<?php echo $esc($module->title); ?>"
    data-segundos="<?php echo (int) $segundos; ?>"
    data-frequencia="<?php echo $esc($frequencia); ?>"
    data-fechar-fora="<?php echo $fecharFora ? '1' : '0'; ?>"
    data-assinatura="<?php echo $esc($assinatura); ?>"
    hidden>
    <div class="popup-box">
        <button type="button" class="close-btn" data-popup-fechar>
            <?php echo Text::_('MOD_IMAGEMPOPUP_FECHAR'); ?><?php if ($segundos > 0) : ?>
                (<span data-popup-contador><?php echo (int) $segundos; ?></span>)<?php endif; ?>
        </button>

        <div class="text-pop">
            <?php if ($imagem !== '') : ?>
                <?php if ($link !== '') : ?>
                    <a href="<?php echo $esc($link); ?>"><img class="img-pop" src="<?php echo $esc($imagem); ?>" alt="<?php echo $esc($imagemAlt); ?>"></a>
                <?php else : ?>
                    <img class="img-pop" src="<?php echo $esc($imagem); ?>" alt="<?php echo $esc($imagemAlt); ?>">
                <?php endif; ?>
            <?php endif; ?>

            <?php echo $texto; ?>
        </div>
    </div>
</div>
