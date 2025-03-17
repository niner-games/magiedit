<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Result $model */

$label = Yii::t('frontend-views', 'Results');
$this->title = $model->examination->patient->fullName;

$this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

YiiAsset::register($this);

?>

<div class="result-view">

    <h1><?= Yii::t('frontend-views', 'Result'); ?>: <strong><?= Html::encode($this->title) ?></strong></h1>

    <p><?= Yii::t('frontend-views', 'Details of selected result are provided below.') ?></p>

    <p>
        <?= Html::a(Yii::t('frontend-views', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('frontend-views', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('frontend-views', 'Are you sure you want to delete this result?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'hip_left',
            'hip_right',
            'hip_difference',
            'leg_left',
            'leg_right',
            'leg_difference',
            'shoulder_left',
            'shoulder_right',
            'shoulder_difference',
            'distance_feet',
            'distance_knees',
            [
                'attribute' => 'suspicion_scoliosis',
                'value' => function ($model, $widget) {
                    return $model->getAttributeDesc('suspicion_scoliosis');
                }
            ],
            [
                'attribute' => 'suspicion_knee_varus',
                'value' => function ($model, $widget) {
                    return $model->getAttributeDesc('suspicion_knee_varus');
                }
            ],
            [
                'attribute' => 'suspicion_knee_valgus',
                'value' => function ($model, $widget) {
                    return $model->getAttributeDesc('suspicion_knee_valgus');
                }
            ],
            'main_comment:ntext',
            [
                'attribute' => 'created_by',
                'label' => Yii::t('frontend-views', 'Operator'),
                'value' => function ($model, $widget) {
                    return $model->user->fullName;
                }
            ],
            [
                'attribute' => 'examination_id',
                'label' => Yii::t('frontend-views', 'Examination'),
                'value' => function ($model, $widget) {
                    return Yii::$app->formatter->asDate($model->examination->updated_at, 'd MMMM y, kk:mm:ss') . ' (' . $model->examination->user->fullName . ')';
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
