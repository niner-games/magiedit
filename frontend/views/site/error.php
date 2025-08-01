<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception $exception */

use yii\helpers\Html;

$this->title = $name;

?>

<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        <?= Yii::t('views', 'The above error occurred while server was processing your request.') ?>
    </p>
    <p>
        <?= Yii::t('views', 'Please contact us if you think this is a server error. Thank you.') ?>
    </p>

</div>
