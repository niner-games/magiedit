<?php

use common\models\Command;
use common\widgets\CustomActionColumn;
use faryshta\data\EnumColumn;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('backend-views', 'Commands');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="command-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('backend-views', 'The list of all commands created in the system is shown below.') ?></p>

    <p><?= Html::a(Yii::t('backend-views', 'Add Command'), ['create'], ['class' => 'btn btn-success']) ?></p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'headerRowOptions' => ['class' => 'data-list-header-row'],
        'columns' => [
            [
                'attribute' => 'to',
                'format' => 'raw',
                'label' => Yii::t('backend-views', 'Command'),
                'headerOptions' => ['style' => 'width: 46%'],
                'contentOptions' => ['class' => 'align-middle'],
                'value' => function ($data)  {
                    return $data->destinationName . ': <strong>' . $data->getAttributeDesc('type') . '</strong>' . $this->render('_response', [
                        'response' => $data->getParsedData($data->body),
                        'options' => ['style' => 'font-size: 13px']
                    ]);
                },
            ],
            [
                'attribute' => 'status',
                'class' => EnumColumn::class,
                'enumClass' => Command::class,
                'contentOptions' => ['class' => 'text-center align-middle'],
                'headerOptions' => ['style' => 'width: 10%', 'class' => 'text-center'],
            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'kk:mm:ss, d MMMM y'],
                'contentOptions' => ['class' => 'text-center align-middle'],
                'headerOptions' => ['style' => 'width: 20%', 'class' => 'text-center'],
            ],
            [
                'attribute' => 'updated_at',
                'contentOptions' => ['class' => 'text-center align-middle'],
                'headerOptions' => ['style' => 'width: 20%', 'class' => 'text-center'],
                'value' => function ($data) {
                    return ($data->updated_at === $data->created_at) ? '---' : Yii::$app->formatter->asDate($data->updated_at, 'kk:mm:ss, d MMMM y');
                },
            ],
            [
                'class' => CustomActionColumn::class,
                'contentOptions' => ['class' => 'align-middle'],
                'deleteConfirmationText' => Yii::t('backend-views', 'Are you sure you want to delete this command?'),
                'urlCreator' => function ($action, Command $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
        'pager' => [
            'class' => LinkPager::class,
        ],
    ]); ?>


</div>
