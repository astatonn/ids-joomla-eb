<?php
defined('_JEXEC') or die;
use Joomla\CMS\Uri\Uri;

?>

<?php if ($params['showbuttonreademore'] == 'yes'): ?>
<div class="news-readmore">
        <a href="<?php echo Uri::root() ?>index.php?Itemid=<?php echo $params['menu-readmore'] ?>">
            <div class="br-button br-button-readmore">
                Leia Mais
            </div>
        </a>
    </div>
<?php endif; ?>