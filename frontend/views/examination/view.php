<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Examination $model */

$label = Yii::t('frontend-views', 'Examinations');
$this->title = $model->patient->fullName;

$this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

YiiAsset::register($this);

?>

<div class="examination-view">

    <h1><?= Yii::t('frontend-views', 'Examination'); ?>: <strong><?= Html::encode($this->title) ?></strong></h1>

    <p><?= Yii::t('frontend-views', 'Details of selected examination are provided below.') ?></p>

    <p>
        <?= Html::a(Yii::t('frontend-views', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        <?= Html::a(Yii::t('frontend-views', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('frontend-views', 'Are you sure you want to delete this examination?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'patient_id',
                'label' => Yii::t('frontend-views', 'Patient'),
                'value' => function ($model, $widget) {
                    return $model->patient->fullName;
                }
            ],
            [
                'attribute' => 'created_by',
                'label' => Yii::t('frontend-views', 'Operator'),
                'value' => function ($model, $widget) {
                    return $model->user->fullName;
                }
            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'd MMMM y, kk:mm:ss']
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'd MMMM y, kk:mm:ss']
            ],
        ],
    ]) ?>

</div>
