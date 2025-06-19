<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;

$this->registerJs("$('span').tooltip(); $('a').tooltip()");

?>

<div class="site-index" xmlns="http://www.w3.org/1999/html">

    <div class="p-4 mb-4 bg-transparent rounded-3">

        <div class="container-fluid py-5 text-center">

            <?= Html::img('@web/images/logo-oneliner-black.png', [
                'alt' => 'Logo of MagiEdit, one-liner version, black color',
                'width' => 461,
                'height' => 177,
            ]) ?>

            <p class="fs-5">

                <?= Yii::t('frontend-views', 'A web application for creating paragraph games and visual novels') ?>

            </p>

    </div>

    <div class="body-content">



    </div>

</div>
