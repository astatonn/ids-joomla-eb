<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_breadcrumbs
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\WebAsset\WebAssetManager;

?>
<div class="br-breadcrumb">
	<?php if ($params->get('showHere', 1)) : ?>
		<span class="sr-only"><?php echo Text::_('MOD_BREADCRUMBS_HERE'); ?></span>
	<?php endif; ?>
	<ul class="crumb-list">
		<?php $show_home = $params->get('showHome', 1);

		if ($show_home)
		{
			array_shift($list);
			if (!empty($list)) : ?>
				<li class="crumb home">
					<a class="br-button circle" href="<?php echo Uri::root(); ?>"><span class="sr-only"><?php echo $params->get('homeText', "Página inicial"); ?></span><i class="fas fa-home"></i></a>
				</li>
			<?php endif;
		}
		// Get rid of duplicated entries on trail including home page when using multilanguage
		for ($i = 0; $i < $count; $i++)
		{
			if ($i === 1 && !empty($list[$i]->link) && !empty($list[$i - 1]->link) && $list[$i]->link === $list[$i - 1]->link)
			{
				unset($list[$i]);
			}
		}

		// Find last and penultimate items in breadcrumbs list
		end($list);
		$last_item_key = key($list);
		prev($list);
		$penult_item_key = key($list);

		// Make a link if not the last item in the breadcrumbs
		$show_last = $params->get('showLast', 1);

		// Generate the trail
		foreach ($list as $key => $item) :
			if ($key !== $last_item_key) :
				if (!empty($item->link)) :
					$breadcrumbItem = HTMLHelper::_('link', Route::_($item->link), '<span>' . $item->name . '</span>');
				else :
					$breadcrumbItem = '<span>' . $item->name . '</span>';
				endif;
				echo '<li class="crumb"><i class="icon fas fa-chevron-right"></i>' . $breadcrumbItem . '</li>';

			elseif ($show_last) :
				// Render last item if required.
				$breadcrumbItem = '<span>' . $item->name . '</span>';
				echo '<li class="crumb" data-active="active"><i class="icon fas fa-chevron-right"></i>' . $breadcrumbItem . '</li>';
			endif;
		endforeach; ?>
	</ul>
	<?php

    // Structured data as JSON
    $data = [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => []
    ];

    // Use an independent counter for positions. E.g. if Heading items in pathway.
    $itemsCounter = 0;

    // If showHome is disabled use the fallback $homeCrumb for startpage at first position.
    if (isset($homeCrumb)) {
        $data['itemListElement'][] = [
                '@type'    => 'ListItem',
                'position' => ++$itemsCounter,
                'item'     => [
                        '@id'  => Route::_($homeCrumb->link, true, Route::TLS_IGNORE, true),
                        'name' => $homeCrumb->name,
                ],
        ];
    }

    foreach ($list as $key => $item) {
        // Only add item to JSON if it has a valid link, otherwise skip it.
        if (!empty($item->link)) {
            $data['itemListElement'][] = [
                    '@type'    => 'ListItem',
                    'position' => ++$itemsCounter,
                    'item'     => [
                            '@id'  => Route::_($item->link, true, Route::TLS_IGNORE, true),
                            'name' => $item->name,
                    ],
            ];
        } elseif ($key === $last_item_key) {
            // Add the last item (current page) to JSON, but without a link.
            // Google accepts items without a URL only as the current page.
            $data['itemListElement'][] = [
                    '@type'    => 'ListItem',
                    'position' => ++$itemsCounter,
                    'item'     => [
                            'name' => $item->name,
                    ],
            ];
        }
    }

    if ($itemsCounter) {
        /** @var WebAssetManager $wa */
        $wa = $app->getDocument()->getWebAssetManager();
        $wa->addInline('script', json_encode($data, JSON_UNESCAPED_UNICODE), [], ['type' => 'application/ld+json']);
    }
    ?>
</div>
