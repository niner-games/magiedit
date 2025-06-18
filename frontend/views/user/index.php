<?php

use common\models\User;
use common\widgets\CustomActionColumn;
use faryshta\data\EnumColumn;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('frontend-views', 'Users');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("$('span').tooltip()");

?>

<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('frontend-views', 'The list of all users existing in the system is shown below.') ?></p>

    <p><?= Html::a(Yii::t('frontend-views', 'Add User'), ['create'], ['class' => 'btn btn-success']) ?></p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'headerRowOptions' => ['class' => 'data-list-header-row'],
        'columns' => [
            [
                'attribute' => 'surname',
                'format' => 'raw',
                'headerOptions' => ['style' => 'width: 18%'],
                'contentOptions' => ['class' => 'align-middle'],
                'label' => Yii::t('frontend-views', 'Name and surname'),
                'value' => function ($data) {
                    return Html::tag('span', $data->fullName, ['title' => $data->username]);
                },
            ],
            [
                'attribute' => 'email',
                'contentOptions' => ['class' => 'text-center align-middle'],
                'headerOptions' => ['style' => 'width: 18%', 'class' => 'text-center'],
            ],
            [
                'attribute' => 'type',
                'class' => EnumColumn::class,
                'enumClass' => User::class,
                'contentOptions' => ['class' => 'text-center align-middle'],
                'headerOptions' => ['style' => 'width: 10%', 'class' => 'text-center'],
            ],
            [
                'attribute' => 'status',
                'class' => EnumColumn::class,
                'enumClass' => User::class,
                'contentOptions' => ['class' => 'text-center align-middle'],
                'headerOptions' => ['style' => 'width: 10%', 'class' => 'text-center'],
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'd MMMM y, kk:mm:ss'],
                'contentOptions' => ['class' => 'text-center align-middle'],
                'headerOptions' => ['style' => 'width: 18%', 'class' => 'text-center'],
            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'd MMMM y, kk:mm:ss'],
                'contentOptions' => ['class' => 'text-center align-middle'],
                'headerOptions' => ['style' => 'width: 18%', 'class' => 'text-center'],
            ],
            [
                'class' => CustomActionColumn::class,
                'contentOptions' => ['class' => 'align-middle'],
                'deleteConfirmationText' => Yii::t('frontend-views', 'Are you sure you want to delete this user?'),
                'template'=> (Yii::$app->user->isAdmin) ? '{view} {update} {delete}' : '{view} {update}',
                'visibleButtons'=> [
                    'delete' => function ($model, $key, $index) {
//                        return (count($model->patients) + count($model->examinations) + count($model->results) === 0);
                        return (Yii::$app->user->id !== $model->id) && (count($model->patients) + count($model->examinations) + count($model->results) === 0);
                    }
                ],

                /**
                 * The below seems to be not working for some reason.
                 *
                 * https://forum.yiiframework.com/t/how-to-truly-disable-actioncolumns-button/135743
                 * https://forum.yiiframework.com/t/is-old-span-based-method-of-rendering-actioncolumns-button-still-supported/135742
                 */
//                'buttons' => [
//                    'view' => function ($url, $model) {
//                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
//                            'title' => Yii::t('app', 'View'),
//                        ]);
//                    },
//                    'update' => function ($url, $model) {
//                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
//                            'title' => Yii::t('app', 'Update'),
//                        ]);
//                    },
//                ],
                'urlCreator' => function ($action, User $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
        'pager' => [
            'class' => LinkPager::class,
        ],
    ]); ?>

    <small class="text-secondary">

        <?= Yii::t('frontend-views', 'You can only delete a user that has'); ?>

        <strong><?= Yii::t('frontend-views', 'no patients, no examinations and no results'); ?></strong>

        <?= Yii::t('frontend-views', 'assigned to itself'); ?>.

        <?= Yii::t('frontend-views', 'You must assign all user\'s objects to some other user before deleting it.'); ?>

    </small>

</div>