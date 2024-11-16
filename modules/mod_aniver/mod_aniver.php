<?php


$model = new Astatonn\Component\Aniversariantes\Site\Model\AniversariantesModel();
$items = $model->getItemsWithNextBirthdays();

if (!function_exists('getUniqueBirthdayEmoji')) {
    function getUniqueBirthdayEmoji($aniversario) {
        $dataAtual = date('m-d');
        $aniversario = date('m-d', strtotime($aniversario));

        if ($aniversario === $dataAtual) {
            return "🎉";
        }
        return "";
    }
}


?>

<div class="container">
    <!-- HEADER  -->

    <div class="autofit-float autofit-row portlet-header">
        <div class="autofit-col autofit-col-expand">
            <div class="portlet-title-text">
                Aniversariantes
            </div>
        </div>
    </div>

    <div class="birthdays-container">
        <div class="birthdays-col">
            <div class="birthday-content">
                <?php foreach ($items as $item) : ?>
                    <?php $emoji = getUniqueBirthdayEmoji($item->aniversario); ?>
                    <div class='birthday-data'>
                        <?php echo $item->pg .' '.$item->nome . " - " . date('d/m', strtotime($item->aniversario)) . " " . $emoji; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
