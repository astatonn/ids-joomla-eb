<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_finder
 *
 * @copyright   (C) 2011 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

?>
<div class="br-divider"></div>
<div class="com-finder finder pt-5 pb-4">
    <?php // Load the search results layout if we are performing a search. ?>
    <?php if ($this->query->search === true) : ?>
        <div id="search-results" class="com-finder__results">
            <?php echo $this->loadTemplate('results'); ?>
        </div>
    <?php else : ?>
        <div id="search-result-empty" class="com-finder__empty">
            <p><?php echo Text::_('COM_FINDER_SEARCH_NO_RESULTS_HEADING'); ?>.</p>
        </div>
    <?php endif; ?>
</div>
