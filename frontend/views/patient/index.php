<?php

use common\models\Patient;
use common\widgets\CustomActionColumn;
use faryshta\data\EnumColumn;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('frontend-views', 'Patients');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="patient-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('frontend-views', 'The list of all patients registered in the system is shown below.') ?></p>

    <p><?= Html::a(Yii::t('frontend-views', 'Add Patient'), ['create'], ['class' => 'btn btn-success']) ?></p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'headerRowOptions' => ['class' => 'data-list-header-row'],
        'columns' => [
            [
                'attribute' => 'surname',
                'label' => Yii::t('frontend-views', 'Patient'),
                'contentOptions' => ['class' => 'align-middle'],
                'headerOptions' => ['style' => 'width: 28%'],
                'value' => function ($data) {
                    return $data->fullName;
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
                'attribute' => 'sex',
                'class' => EnumColumn::class,
                'enumClass' => Patient::class,
                'contentOptions' => ['class' => 'text-center align-middle'],
                'headerOptions' => ['style' => 'width: 20%', 'class' => 'text-center'],
            ],
            [
                'attribute' => 'birth_date',
                'contentOptions' => ['class' => 'text-center align-middle'],
                'format' => ['date', 'd MMMM y'],
                'headerOptions' => ['style' => 'width: 20%', 'class' => 'text-center'],
            ],
            [
                'class' => CustomActionColumn::class,
                'contentOptions' => ['class' => 'align-middle'],
                'deleteConfirmationText' => Yii::t('frontend-views', 'Are you sure you want to delete this patient?'),
                'urlCreator' => function ($action, Patient $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
        'pager' => [
            'class' => LinkPager::class,
        ],
    ]); ?>


</div>
