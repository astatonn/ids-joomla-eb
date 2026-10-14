<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  mod_instafeed
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/**
 * @var \Joomla\CMS\Application\CMSApplicationInterface  $app
 * @var string  $perfil   Nome de usuário do Instagram
 * @var string  $url      Endereço do perfil
 * @var int     $largura  Largura máxima, em pixels
 */

// Script oficial do Instagram, que transforma o <blockquote> abaixo no quadro do perfil.
// Se o instagram.com estiver bloqueado, fica visível apenas o link para o perfil.
$app->getDocument()->getWebAssetManager()
    ->registerAndUseScript('mod_instafeed.embed', 'https://www.instagram.com/embed.js', [], ['async' => true]);

$esc = static fn ($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
?>
<div class="mod-instafeed" style="display:flex; align-items:center; justify-content:center; padding:75px 20px">
    <blockquote
        class="instagram-media"
        data-instgrm-permalink="<?php echo $esc($url); ?>"
        data-instgrm-version="14"
        style="background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin:1px; max-width:<?php echo (int) $largura; ?>px; min-width:326px; padding:0; width:99.375%;">
        <div style="padding:16px; text-align:center;">
            <a href="<?php echo $esc($url); ?>" target="_blank" rel="noopener noreferrer" style="font-family:Arial,sans-serif; font-size:14px; font-weight:600; line-height:18px; text-decoration:none;">
                <i class="fab fa-instagram" aria-hidden="true"></i>
                <?php echo Text::sprintf('MOD_INSTAFEED_VER_PERFIL', '@' . $esc($perfil)); ?>
            </a>
        </div>
    </blockquote>
</div>
