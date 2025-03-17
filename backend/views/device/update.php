<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Device $model */

$this->title = Yii::t('backend-views', 'Update Device: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend-views', 'Devices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend-views', 'Update');

?>

<div class="device-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('backend-views', 'Please use this form to update selected device.') ?></p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
