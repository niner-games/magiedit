<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Device $model */

$this->title = Yii::t('backend-views', 'Add Device');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend-views', 'Devices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="device-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('backend-views', 'Please fill out the following fields to add a device.') ?></p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
