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

<div class="header-search" id="main-searchbox">
    <form action="<?php echo Route::_($route); ?>" method="get" role="search">
        <div class="br-input has-icon">
            <label for="searchbox">Texto da pesquisa</label>
            <input id="searchbox" type="text" name="q" placeholder="O que você procura?" value="<?php echo htmlspecialchars($app->input->get('q', '', 'string'), ENT_COMPAT, 'UTF-8'); ?>" />
            <button class="br-button circle small" type="submit" aria-label="Pesquisar"><i class="fas fa-search" aria-hidden="true"></i></button>
        </div>
    </form>
        <button class="br-button circle search-close ml-1" type="button" aria-label="Fechar Busca" data-dismiss="search"><i class="fas fa-times" aria-hidden="true"></i></button>
</div>
