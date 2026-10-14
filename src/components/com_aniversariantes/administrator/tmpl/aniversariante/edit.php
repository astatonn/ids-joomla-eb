<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_aniversariantes
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

/** @var \Astatonn\Component\Aniversariantes\Administrator\View\Aniversariante\HtmlView $this */

/** @var \Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->getDocument()->getWebAssetManager();
$wa->useScript('keepalive')
    ->useScript('form.validate');
?>
<form action="<?php echo Route::_('index.php?option=com_aniversariantes&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="aniversariante-form" aria-label="<?php echo Text::_('COM_ANIVERSARIANTES_FIELDSET_DETAILS'); ?>" class="form-validate">
    <div class="main-card">
        <div class="row">
            <div class="col-lg-8">
                <fieldset class="options-form">
                    <legend><?php echo Text::_('COM_ANIVERSARIANTES_FIELDSET_DETAILS'); ?></legend>
                    <?php echo $this->form->renderFieldset('details'); ?>
                </fieldset>
            </div>
        </div>
    </div>

    <input type="hidden" name="task" value="">
    <?php echo HTMLHelper::_('form.token'); ?>
</form>
