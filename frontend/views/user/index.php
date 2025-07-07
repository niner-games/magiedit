<?php

use common\models\User;
use common\widgets\CustomActionColumn;

use faryshta\data\EnumColumn;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap5\LinkPager;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

/**
 * SVG icons used in this view:
 * - https://lucide.dev/icons/
 * - https://tabler.io/icons/
 * - https://heroicons.com/
 */

$this->title = Yii::t('frontend-views', 'Users');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("$('span').tooltip(); $('a').tooltip()");

?>

<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('frontend-views', 'The list of all users existing in the system is shown below.') ?></p>

    <p><?= Html::a(
            Yii::t('frontend-views', 'Add User'),
            ['create'],
            ['class' => 'btn btn-success']
        ); ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'headerRowOptions' => ['class' => 'data-list-header-row'],
        'columns' => [
            [
                'attribute' => 'username',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::tag('span', $model->username, [
                        'data-bs-toggle' => 'tooltip',
                        'data-bs-placement' => 'top',
                        'title' => $model->email,
                        'style' => 'cursor: default;',
                    ]);
                },
                'contentOptions' => ['class' => 'text-left align-middle'],
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
                'contentOptions' => ['class' => 'text-center align-middle'],
                'deleteConfirmationText' => Yii::t('frontend-views', 'Are you sure you want to delete this user?'),
                'template'=> (Yii::$app->user->identity->getIsAdmin()) ? '{view} {update} {delete}' : '{view} {update}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a(
                            '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-chart-gantt-icon lucide-square-chart-gantt"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 8h7"/><path d="M8 12h6"/><path d="M11 16h5"/></svg>',
                            $url,
                            [
                                'aria-label' => Yii::t('frontend-views', 'View details'),
                                'title' => Yii::t('frontend-views', 'View details'),
                                'class' => 'awfully-black-button',
                                'data-pjax' => '0',
                            ]
                        );
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a(
                            '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg>',
                            $url,
                            [
                                'aria-label' => Yii::t('frontend-views', 'Edit user'),
                                'title' => Yii::t('frontend-views', 'Edit user'),
                                'class' => 'awfully-black-button',
                                'data-pjax' => '0',
                            ]
                        );
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a(
                            '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shredder-icon lucide-shredder"><path d="M10 22v-5"/><path d="M14 19v-2"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M18 20v-3"/><path d="M2 13h20"/><path d="M20 13V7l-5-5H6a2 2 0 0 0-2 2v9"/><path d="M6 20v-3"/></svg>',
                            $url,
                            [
                                'aria-label' => Yii::t('frontend-views', 'Delete user'),
                                'title' => Yii::t('frontend-views', 'Delete user'),
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-placement' => 'top', // or 'bottom', 'left', 'right'
                                'class' => 'awfully-black-button',
                                'data-pjax' => '0',
                            ]
                        );
                    },
                ],
                'urlCreator' => function ($action, User $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
        'pager' => [
            'class' => LinkPager::class,
        ],
    ]); ?>

    <p>

        <?= Yii::t('frontend-views', 'You can only delete a user that has'); ?>
        <strong><?= Yii::t('frontend-views', 'no objects'); ?></strong>
        <?= Yii::t('frontend-views', 'assigned to itself'); ?>.
        <?= Yii::t('frontend-views', 'You must assign all user’s objects to some other user before deleting it.'); ?>

    </p>

</div>