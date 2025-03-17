<?php

use common\models\Device;
use common\widgets\CustomActionColumn;
use faryshta\data\EnumColumn;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('backend-views', 'Devices');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="device-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('backend-views', 'The list of all devices existing in the system is shown below.') ?></p>

    <p><?= Html::a(Yii::t('backend-views', 'Add Device'), ['create'], ['class' => 'btn btn-success']) ?></p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'headerRowOptions' => ['class' => 'data-list-header-row'],
        'columns' => [
            [
                'attribute' => 'name',
                'headerOptions' => ['style' => 'width: 20%'],
                'contentOptions' => ['class' => 'align-middle'],
            ],
            [
                'attribute' => 'tid',
                'contentOptions' => ['class' => 'text-center align-middle'],
                'headerOptions' => ['style' => 'width: 11%', 'class' => 'text-center'],
            ],
            [
                'attribute' => 'type',
                'class' => EnumColumn::class,
                'enumClass' => Device::class,
                'contentOptions' => ['class' => 'text-center align-middle'],
                'headerOptions' => ['style' => 'width: 15%', 'class' => 'text-center'],
            ],
            [
                'attribute' => 'status',
                'class' => EnumColumn::class,
                'enumClass' => Device::class,
                'contentOptions' => ['class' => 'text-center align-middle'],
                'headerOptions' => ['style' => 'width: 10%', 'class' => 'text-center'],
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'd MMMM y, kk:mm:ss'],
                'label' => Yii::t('backend-views', 'Last contact'),
                'contentOptions' => ['class' => 'text-center align-middle'],
                'headerOptions' => ['style' => 'width: 20%', 'class' => 'text-center'],
            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'd MMMM y, kk:mm:ss'],
                'label' => Yii::t('backend-views', 'Added'),
                'contentOptions' => ['class' => 'text-center align-middle'],
                'headerOptions' => ['style' => 'width: 20%', 'class' => 'text-center'],
            ],
            [
                'class' => CustomActionColumn::class,
                'contentOptions' => ['class' => 'align-middle'],
                'deleteConfirmationText' => Yii::t('backend-views', 'Are you sure you want to delete this device?'),
                'urlCreator' => function ($action, Device $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
        'pager' => [
            'class' => LinkPager::class,
        ],
    ]); ?>


</div>
