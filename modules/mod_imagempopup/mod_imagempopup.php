<?php
defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;

$segundos = ($params['segundos'] ?? 0);
?>

<div class="overlay" id="div-pop">
    <div class="popup-box">
        <button id="close-btn" class="close-btn">Fechar (<span id="counter"><?=$segundos;?></span>)</button>
        

        
        <div class='text-pop'>
            <?php if($params['imagem']): ?>
                        <img class="img-pop" id="img-pop" src="<?= htmlspecialchars($params['imagem']); ?>" onerror="this.style.display='none';">

                        <?php endif ?>
            <?php if($params['popuptext']){

                echo  $params['popuptext'];
            }
            ?>
        </div>
    </div>
</div>

<style>
.overlay {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(0, 0, 0, 0.7);
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 9999;
}

.popup-box {
    background-color: white;
    width: 50%;
    height: 80%;
    max-height: 80%;
    padding: 20px;
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    overflow-y: auto;
}

@media (max-width: 768px) {
    .popup-box {
        width: 80%;
    }
}

.img-pop {
    cursor: default;
    max-height: 90%;
    max-width: 90%;
}

.text-pop {
   width: 100%;
   max-height: 100%;
   overflow-y: auto;
   text-align: center;
}

.close-btn {
    align-self: flex-end;
    padding: 10px 20px;
    font-size: 16px;
    border: none;
    background-color: red;
    color: white;
    cursor: pointer;
    border-radius: 5px;
    margin-bottom: 10px;
}
</style>

<script>
function removePopUp(){
    document.getElementById('div-pop').style.display = 'none';
}

document.getElementById('close-btn').addEventListener('click', removePopUp);

let counter = <?=$segundos;?>;
const counterElement = document.getElementById('counter');

if (counter > 0) {
    const interval = setInterval(() => {
        counter--;
        counterElement.textContent = counter;
        if (counter <= 0) {
            clearInterval(interval);
            removePopUp();
        }
    }, 1000);
}
</script>
