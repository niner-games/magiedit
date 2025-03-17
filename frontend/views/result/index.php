<?php

use common\models\Result;
use common\widgets\CustomActionColumn;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('frontend-views', 'Results');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="result-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('frontend-views', 'The list of all results gathered in the system is shown below.') ?></p>

    <p><?= Html::a(Yii::t('frontend-views', 'Add Result'), ['create'], ['class' => 'btn btn-success']) ?></p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'headerRowOptions' => ['class' => 'data-list-header-row'],
        'columns' => [
            [
                'attribute' => 'examination_id',
                'label' => Yii::t('frontend-views', 'Patient'),
                'contentOptions' => ['class' => 'align-middle'],
                'headerOptions' => ['style' => 'width: 28%'],
                'value' => function ($data) {
                    return $data->examination->patient->fullName;
                },
            ],
            [
                'attribute' => 'created_by',
                'label' => Yii::t('frontend-views', 'Operator'),
                'contentOptions' => ['class' => 'text-center align-middle'],
                'headerOptions' => ['style' => 'width: 28%', 'class' => 'text-center'],
                'value' => function ($data) {
                    return $data->user->fullName;
                },
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'd MMMM y, kk:mm:ss'],
                'contentOptions' => ['class' => 'text-center align-middle'],
                'headerOptions' => ['style' => 'width: 20%', 'class' => 'text-center'],
            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'd MMMM y, kk:mm:ss'],
                'contentOptions' => ['class' => 'text-center align-middle'],
                'headerOptions' => ['style' => 'width: 20%', 'class' => 'text-center'],
            ],
            [
                'class' => CustomActionColumn::class,
                'contentOptions' => ['class' => 'align-middle'],
                'deleteConfirmationText' => Yii::t('frontend-views', 'Are you sure you want to delete this examination?'),
                'urlCreator' => function ($action, Result $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
        'pager' => [
            'class' => LinkPager::class,
        ],
    ]); ?>


</div>
