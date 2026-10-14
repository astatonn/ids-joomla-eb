<?php

/**
 * @package     IDS Gov - Exército Brasileiro (template)
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 *
 * Preparação compartilhada por index.php e error.php: registra CSS/JS, favicons e
 * normaliza os parâmetros do estilo. É incluído no escopo do documento ($this).
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\HtmlDocument $this */

$app = Factory::getApplication();
$wa  = $this->getWebAssetManager();

// Pasta pública do template (media/templates/site/govbr-ds)
$mediaPath = 'media/templates/site/govbr-ds/';

/**
 * Escapa um valor para saída em HTML.
 */
$e = static function ($value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

/**
 * Resolve o endereço de uma imagem escolhida em um campo "media" (ou o padrão do template).
 * Remove o sufixo "#joomlaImage://..." gravado pelo Joomla e devolve um caminho que funciona
 * em qualquer página, inclusive com URLs amigáveis.
 */
$img = static function ($value, string $fallback) use ($e): string {
    $value = trim((string) $value);

    if ($value === '') {
        $value = $fallback;
    }

    $url = HTMLHelper::cleanImageURL($value)->url;

    if (!preg_match('#^([a-z][a-z0-9+.-]*:)?//#i', $url) && strncmp($url, '/', 1) !== 0 && strncmp($url, 'data:', 5) !== 0) {
        $url = Uri::root(true) . '/' . $url;
    }

    return $e($url);
};

// ---------------------------------------------------------------------------
// CSS e JavaScript
// ---------------------------------------------------------------------------
$wa->registerAndUseStyle('font-rawline', $mediaPath . 'css/rawline.css');
$wa->registerAndUseStyle('dsgov-core-css', $mediaPath . 'css/core.css');
$wa->registerAndUseStyle('fontawesome-all', $mediaPath . 'css/all.min.css');
$wa->registerAndUseStyle('dsgov-custom', $mediaPath . 'css/custom.css');
$wa->registerAndUseScript('dsgov-core-js', $mediaPath . 'js/core-init.js');
$wa->registerAndUseScript('dsgov-contrast-js', $mediaPath . 'js/contrast.class.js');
$wa->registerAndUseScript('dsgov-backtotop-js', $mediaPath . 'js/backtotop.js');

if ($this->params->get('cookies_aviso')) {
    $cookieOptions = [
        'noticeText'           => (string) $this->params->get('cookies_mensagem', ''),
        'learnMoreLinkEnabled' => (bool) $this->params->get('cookies_leiamais'),
        'buttonText'           => (string) $this->params->get('cookies_ciente_titulo', 'Ciente'),
    ];

    if ($cookieOptions['learnMoreLinkEnabled']) {
        $cookieOptions['learnMoreLinkHref'] = (string) $this->params->get('cookies_leiamais_link', '');
        $cookieOptions['learnMoreLinkText'] = (string) $this->params->get('cookies_leiamais_titulo', '');
    }

    // Sem mensagem configurada, o script usa o texto padrão dele.
    if ($cookieOptions['noticeText'] === '') {
        unset($cookieOptions['noticeText']);
    }

    $wa->registerAndUseScript('cookie-notice', $mediaPath . 'js/cookie.notice.js');
    $wa->addInlineScript(
        'new cookieNoticeJS(' . json_encode($cookieOptions, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) . ');'
    );
}

// VLibras (tradução para Libras). Se o servidor do VLibras estiver inacessível, o site segue funcionando.
$wa->registerAndUseScript('vlibras-plugin', 'https://vlibras.gov.br/app/vlibras-plugin.js');
$wa->addInlineScript("if (window.VLibras) { new window.VLibras.Widget('https://vlibras.gov.br/app'); }");

// ---------------------------------------------------------------------------
// Favicons e metadados
// ---------------------------------------------------------------------------
$this->addHeadLink(HTMLHelper::_('image', $mediaPath . 'favicons/apple-touch-icon.png', '', [], false, 1), 'apple-touch-icon', 'rel', ['sizes' => '180x180']);
$this->addHeadLink(HTMLHelper::_('image', $mediaPath . 'favicons/favicon-32x32.png', '', [], false, 1), 'icon', 'rel', ['type' => 'image/png', 'sizes' => '32x32']);
$this->addHeadLink(HTMLHelper::_('image', $mediaPath . 'favicons/favicon-16x16.png', '', [], false, 1), 'icon', 'rel', ['type' => 'image/png', 'sizes' => '16x16']);
$this->addHeadLink(HTMLHelper::_('image', $mediaPath . 'favicons/manifest.json', '', [], false, 1), 'manifest', 'rel', []);
$this->addHeadLink(HTMLHelper::_('image', $mediaPath . 'favicons/safari-pinned-tab.svg', '', [], false, 1), 'mask-icon', 'rel', ['color' => '#00a300']);
$this->setMetaData('msapplication-config', Uri::root(true) . '/' . $mediaPath . 'favicons/browserconfig.xml');
$this->setMetaData('theme-color', '#00a300');
$this->setMetaData('viewport', 'width=device-width, initial-scale=1');

// ---------------------------------------------------------------------------
// Parâmetros do estilo já normalizados para uso no HTML
// ---------------------------------------------------------------------------
$rootUrl        = $e(Uri::root());
$tema           = preg_replace('/[^a-z0-9_-]/i', '', (string) $this->params->get('tema', ''));
$menuHorizontal = (int) $this->params->get('tipo-menu', 0) === 1;
$rodapeClaro    = (bool) $this->params->get('cor_footer');

$largura    = $this->params->get('largura') ? 'container-fluid' : 'container-lg';
$tipomenu   = $this->params->get('tipomenu') ? 'br-menu push' : 'br-menu';
$cor_footer = $rodapeClaro ? 'br-footer inverted pt-3' : 'br-footer pt-3';

$titulo      = $e($this->params->get('texto_titulo', 'Template básico'));
$subtitulo   = $e($this->params->get('texto_subtitulo', ''));
$assinatura  = $e($this->params->get('texto_assinatura', ''));
$logo        = $img($this->params->get('imagem_logo', ''), $mediaPath . 'img/logo.svg');
$logoRodape  = $img($this->params->get('imagem_logo_footer', ''), $mediaPath . ($rodapeClaro ? 'img/logo.svg' : 'img/logo_footer.svg'));
$logoBrasil  = $img($this->params->get('imagem_footer_brasil_src', ''), $mediaPath . 'img/brasil_footer.svg');
$logoAcesso  = $img('', $mediaPath . ($rodapeClaro ? 'img/acesso_footer_claro.svg' : 'img/acesso_footer.svg'));
$logoComLink = (int) $this->params->get('logo_comlink', 0);
$logoLink    = $e($this->params->get('logo_link', ''));
$cabecalho   = (int) $this->params->get('cabecalhomenu', 1);

$redesSociais = [];

if ($this->params->get('redes_sociais', '1')) {
    foreach (
        [
            'redes_instagram' => ['Instagram', 'fab fa-instagram'],
            'redes_twitter'   => ['Twitter', 'fab fa-twitter'],
            'redes_facebook'  => ['Facebook', 'fab fa-facebook-f'],
            'redes_youtube'   => ['YouTube', 'fab fa-youtube'],
        ] as $param => [$rotulo, $icone]
    ) {
        $endereco = trim((string) $this->params->get($param, ''));

        if ($endereco !== '') {
            $redesSociais[] = ['url' => $e($endereco), 'rotulo' => $rotulo, 'icone' => $icone];
        }
    }
}
