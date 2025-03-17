<?php

use common\models\Command;
use faryshta\data\EnumColumn;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var string $type */
/** @var string $status */
/** @var yii\web\View $this */
/** @var common\models\Command $model */

$type = $model->getAttributeDesc('type');
$status = $model->getAttributeDesc('status');

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend-views', 'Commands'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $type.' ('.$status.')';

YiiAsset::register($this);

?>

<div class="command-view">

    <h1><?= Yii::t('backend-views', 'Command') ?>: <strong><?= $type; ?></strong> (<?= $status; ?>)</h1>

    <p><?= Yii::t('backend-views', 'Details of selected command are provided below.') ?></p>

    <p>
        <?= Html::a(Yii::t('backend-views', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        <?= Html::a(Yii::t('backend-views', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('backend-views', 'Are you sure you want to delete this command?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'to',
                'value' => function ($model, $widget) {
                    return $model->destinationName;
                }
            ],
            [
                'attribute' => 'type',
                'value' => function ($model, $widget) use ($type) {
                    return $type;
                }
            ],
            [
                'attribute' => 'body',
                'format' => 'raw',
                'value' => function ($model, $widget) use ($status) {
                    return $this->render('_response', ['response' => $model->getParsedData($model->body)]);
                }
            ],
            [
                'attribute' => 'result',
                'format' => 'raw',
                'value' => function ($model, $widget) use ($status) {
                    return $this->render('_response', ['response' => $model->getParsedData($model->result)]);
                }
            ],
            [
                'attribute' => 'status',
                'value' => function ($model, $widget) use ($status) {
                    return $status;
                }
            ],
            [
                'attribute' => 'from',
                'value' => function ($model, $widget) {
                    return $model->destinationName;
                }
            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'kk:mm:ss, d MMMM y']
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'kk:mm:ss, d MMMM y']
            ],
        ],
    ]) ?>

</div>
