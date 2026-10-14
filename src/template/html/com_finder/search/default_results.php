<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_finder
 *
 * @copyright   (C) 2011 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

?>
<?php // Display the 'no results' message and exit the template. ?>
<?php if (($this->total === 0) || ($this->total === null)) : ?>
    <div id="search-result-empty" class="com-finder__empty">
        <p><?php echo Text::_('COM_FINDER_SEARCH_NO_RESULTS_HEADING'); ?></p>
        <p><?php echo Text::sprintf('COM_FINDER_SEARCH_NO_RESULTS_BODY', $this->escape($this->query->input)); ?></p>
    </div>
    <?php // Exit this template. ?>
    <?php return; ?>
<?php else : ?>
    <?php if ($this->params->get('show_pagination_results', 1) > 0) : ?>
        <p>
            <?php // Prepare the pagination string.  Results X - Y of Z ?>
            <?php $start = (int) $this->pagination->limitstart + 1; ?>
            <?php $total = (int) $this->pagination->total; ?>
            <?php $limit = (int) $this->pagination->limit * $this->pagination->pagesCurrent; ?>
            <?php $limit = (int) ($limit > $total ? $total : $limit); ?>
            <?php echo Text::sprintf('COM_FINDER_SEARCH_RESULTS_OF', $start, $limit, $total); ?>
        </p>
    <?php endif; ?>
<?php endif; ?>
<?php // Activate the highlighter if enabled. ?>
<?php if (!empty($this->query->highlight) && $this->params->get('highlight_terms', 1)) : ?>
    <?php
        // Allow a maximum of 10 tokens to be highlighted. Otherwise the URL can get too long.
        $this->document->getWebAssetManager()->useScript('highlight');
        $this->document->addScriptOptions(
            'highlight',
            [[
                    'class'      => 'js-highlight',
                    'highLight'  => array_slice($this->query->highlight, 0, 10),
            ]]
        );
    ?>
<?php endif; ?>
<?php // Display a list of results ?>
<ul id="search-result-list" class="js-highlight com-finder__results-list">
    <?php $this->baseUrl = Uri::getInstance()->toString(['scheme', 'host', 'port']); ?>
    <?php foreach ($this->results as $i => $result) : ?>
        <?php $this->result = &$result; ?>
        <?php $this->result->counter = $i + 1; ?>
        <?php $layout = $this->getLayoutFile($this->result->layout); ?>
        <?php echo $this->loadTemplate($layout); ?>
    <?php endforeach; ?>
</ul>
<?php // Display the pagination ?>
<div class="com-finder__navigation search-pagination">
    <?php if ($this->params->get('show_pagination', 1) > 0) : ?>
    <div class="com-finder__pagination w-100">
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>
    <?php endif; ?>

</div>
