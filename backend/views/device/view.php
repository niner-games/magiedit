<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Device $model */

$label = Yii::t('backend-views', 'Devices');
$this->title = $model->name;

$this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

YiiAsset::register($this);

?>

<div class="device-view">

    <h1><?= Yii::t('backend-views', 'Device'); ?>: <strong><?= Html::encode($this->title) ?></strong></h1>

    <p><?= Yii::t('backend-views', 'Details of selected device are provided below.') ?></p>

    <p>
        <?= Html::a(Yii::t('backend-views', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend-views', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('backend-views', 'Are you sure you want to delete this device?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'tid',
            [
                'attribute' => 'type',
                'value' => function ($model, $widget) {
                    return $model->getAttributeDesc('type');
                }
            ],
            [
                'attribute' => 'status',
                'value' => function ($model, $widget) {
                    return $model->getAttributeDesc('status');
                }
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'd MMMM y, kk:mm:ss'],
                'label' => Yii::t('backend-views', 'Last contact'),
            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'd MMMM y, kk:mm:ss'],
            ],
        ],
    ]) ?>

</div>
