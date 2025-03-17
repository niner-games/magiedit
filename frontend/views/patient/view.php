<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Patient $model */

$label = Yii::t('frontend-views', 'Patients');
$this->title = $model->fullName;

$this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

YiiAsset::register($this);

?>

<div class="patient-view">

    <h1><?= Yii::t('frontend-views', 'Patient'); ?>: <strong><?= Html::encode($this->title) ?></strong></h1>

    <p><?= Yii::t('frontend-views', 'Details of selected patient are provided below.') ?></p>

    <p>
        <?= Html::a(Yii::t('frontend-views', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        <?= Html::a(Yii::t('frontend-views', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('frontend-views', 'Are you sure you want to delete this patient?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'surname',
            'pesel',
            [
                'attribute' => 'sex',
                'value' => function ($model, $widget) {
                    return $model->getAttributeDesc('sex');
                }
            ],
            [
                'attribute' => 'birth_date',
                'format' => ['date', 'd MMMM y'],
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
