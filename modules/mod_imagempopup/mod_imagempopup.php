<?php
defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;

$segundos = ($params['segundos'] * 1000) ?? 0;

?>

<div class="bg-pop" id="div-pop">
    <img class="img-pop" id="img-pop" src="<?= $params['imagem'];?>">
</div>


<style>

.img-pop {
    cursor: default;
    max-height: 90%;
    max-width: 90%;
}

.bg-pop {
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: <?=$params['cor'];?>;
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    z-index: 9999;
}

</style>


<script>

function removePopUp(){
    document.getElementById('div-pop').style.display = 'none';
}

window.onclick = function(e) {
    if (!document.getElementById('img-pop').contains(e.target)) {
        removePopUp();
    }
}

<?php if($params['temporizador'] == true){ ?>
    setTimeout(removePopUp, <?=$segundos;?>);
<?php } ?>

</script>