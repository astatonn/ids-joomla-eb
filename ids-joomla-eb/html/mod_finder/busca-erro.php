<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_finder
 *
 * @copyright   (C) 2011 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Module\Finder\Site\Helper\FinderHelper;

// Load the smart search component language file.
$lang = $app->getLanguage();
$lang->load('com_finder', JPATH_SITE);

Text::script('MOD_FINDER_SEARCH_VALUE', true);

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $app->getDocument()->getWebAssetManager();
$wa->getRegistry()->addExtensionRegistryFile('com_finder');

/*
 * This segment of code sets up the autocompleter.
 */
if ($params->get('show_autosuggest', 1)) {
    $wa->usePreset('awesomplete');
    $app->getDocument()->addScriptOptions('finder-search', array('url' => Route::_('index.php?option=com_finder&task=suggestions.suggest&format=json&tmpl=component', false)));
}

$wa->useScript('com_finder.finder');

?>

<div class="my-3">
    <p>Aproveite para fazer uma nova busca</p>
    <form action="<?php echo Route::_($route); ?>" method="get">
        <div class="br-input input-button mt-n1 input-highlight">
            <label class="sr-only" for="error-search">Texto da pesquisa</label>
            <input id="error-search" type="search" name="q" placeholder="O que você procura?"/>
            <button class="br-button crumb" type="submit" aria-label="Buscar"><i class="fas fa-search" aria-hidden="true"></i></button>
        </div>
    </form>
</div>
