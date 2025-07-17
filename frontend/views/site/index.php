<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;

?>

<div class="site-index" xmlns="http://www.w3.org/1999/html">

    <div class="p-4 mb-4 bg-transparent rounded-3">

        <div class="container-fluid py-5 text-center">

            <div class="mx-auto" style="max-width: 461px;">
                <?= Html::img('@web/images/logo-oneliner-black.png', [
                    'alt' => 'Logo of MagiEdit, one-liner version, black color',
                    'class' => 'img-fluid'
                ]) ?>
            </div>

            <h3 class="fw-light mb-3 mb-md-4">

                <?= Yii::t('views', 'Where visual novels and gamebooks begin their journey') ?>.

            </h3>

            <p class="fs-5">

                <?= Html::a(Yii::t('views', 'Log in'), ['account/login']) ?><?= Yii::t('views', ' to access all the features and create your unforgettable story.') ?>
                <br />
                <?= Yii::t('views', 'Don’t have an account?') ?> <?= Html::a(Yii::t('views', 'Sign up now'), ['account/signup']) ?>.

            </p>

    </div>

    <div class="body-content">



    </div>

</div>
