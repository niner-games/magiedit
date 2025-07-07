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

$this->registerJs("
    $('span').tooltip();
    $('a').tooltip()
");

$this->registerJs("
    const csrfParam = '".Yii::$app->request->csrfParam."';
    const csrfToken = '".Yii::$app->request->getCsrfToken()."';
");

$this->registerJs("
    let deleteUrl = '';
    
    $(document).on('click', '.delete-user-btn', function (e) {
        e.preventDefault();
        const btn = $(this);
        deleteUrl = $(this).attr('href');
        
        $('#delete-user-type').text(btn.data('modal-type'));
        $('#delete-user-status').text(btn.data('modal-status'));
        $('#delete-user-username').text(btn.data('modal-username'));
        
        const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        
        modal.show();
    });

    $('#confirmDeleteBtn').on('click', function () {
        if (deleteUrl) {
            const form = $('<form>', {
                method: 'POST',
                action: deleteUrl
            });

            const csrfInput = $('<input>', {
                type: 'hidden',
                name: csrfParam,
                value: csrfToken
            });

            form.append(csrfInput).appendTo('body');
            form.submit();
            
            form.on('submit', function () {
                $(this).remove();
            });
        }
    });
");


$this->registerCss("
    @media (max-width: 575.98px) {
        .modal-dialog.modal-sm {
            max-width: 200px;
            margin: auto;
        }
    }
    
    .modal-dialog.modal-sm {
        max-width: 330px;
        margin: auto;
    }
");
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

    <div class="table-responsive">
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
                    'contentOptions' => ['class' => 'text-center align-middle d-none d-md-table-cell'],
                    'headerOptions' => ['style' => 'width: 18%', 'class' => 'text-center d-none d-md-table-cell'],
                ],
                [
                    'attribute' => 'created_at',
                    'format' => ['date', 'd MMMM y, kk:mm:ss'],
                    'contentOptions' => ['class' => 'text-center align-middle d-none d-md-table-cell'],
                    'headerOptions' => ['style' => 'width: 18%', 'class' => 'text-center d-none d-md-table-cell'],
                ],
                [
                    'class' => CustomActionColumn::class,
                    'contentOptions' => ['class' => 'text-center align-middle'],
                    'deleteConfirmationText' => Yii::t('frontend-views', 'Are you sure you want to delete this user?'),
                    'template'=> (Yii::$app->user->identity->getIsAdmin()) ? '{view} {update} {delete}' : '{view} {update}',
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            return Html::a(
                                '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-chart-gantt-icon lucide-square-chart-gantt"><rect fill="none" width="18" height="18" x="3" y="3" rx="2"/><path fill="none" d="M9 8h7"/><path fill="none" d="M8 12h6"/><path fill="none" d="M11 16h5"/></svg>',
                                $url,
                                [
                                    'aria-label' => Yii::t('frontend-views', 'View Details'),
                                    'title' => Yii::t('frontend-views', 'View Details'),
                                    'class' => 'awfully-black-button',
                                    'data-pjax' => '0',
                                ]
                            );
                        },
                        'update' => function ($url, $model, $key) {
                            return Html::a(
                                '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen"><path fill="none" d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path fill="none" d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg>',
                                $url,
                                [
                                    'aria-label' => Yii::t('frontend-views', 'Edit User'),
                                    'title' => Yii::t('frontend-views', 'Edit User'),
                                    'class' => 'awfully-black-button',
                                    'data-pjax' => '0',
                                ]
                            );
                        },
                        'delete' => function ($url, $model, $key) {
                            return (Yii::$app->user->id === $model->id) ? '' : Html::a(
                                '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shredder-icon lucide-shredder"><path fill="none" d="M10 22v-5"/><path fill="none" d="M14 19v-2"/><path fill="none" d="M14 2v4a2 2 0 0 0 2 2h4"/><path fill="none" d="M18 20v-3"/><path fill="none" d="M2 13h20"/><path fill="none" d="M20 13V7l-5-5H6a2 2 0 0 0-2 2v9"/><path fill="none" d="M6 20v-3"/></svg>',
                                $url,
                                [
                                    'aria-label' => Yii::t('frontend-views', 'Delete User'),
                                    'title' => Yii::t('frontend-views', 'Delete User'),
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'top',
                                    'class' => 'awfully-black-button delete-user-btn',
                                    'data-user-id' => $model->id,
                                    'data-pjax' => '0',
                                    'data-modal-username' => Html::encode($model->username),
                                    'data-modal-type' => Html::encode($model->getAttributeDesc('type')),
                                    'data-modal-status' => Html::encode($model->getAttributeDesc('status')),
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
    </div>

    <p>

        <?= Yii::t('frontend-views', 'You can only delete a user that has'); ?>
        <strong><?= Yii::t('frontend-views', 'no objects'); ?></strong>
        <?= Yii::t('frontend-views', 'assigned to itself'); ?>.
        <?= Yii::t('frontend-views', 'You must assign all user’s objects to some other user before deleting it.'); ?>

    </p>

</div>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?= Yii::t('frontend-views', 'The following user will be deleted:'); ?></p>
                <ul id="delete-user-details" style="">
                    <li><?= Yii::t('common-models', 'Username'); ?>: <strong><span id="delete-user-username"></span></strong></li>
                    <li><?= Yii::t('common-models', 'Status'); ?>: <strong><span id="delete-user-status"></span></strong></li>
                    <li><?= Yii::t('common-models', 'Type'); ?>: <strong><span id="delete-user-type"></span></strong></li>
                </ul>
                <p><?= Yii::t('frontend-views', 'Are you sure?'); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= Yii::t('frontend-views', 'No'); ?></button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn"><?= Yii::t('frontend-views', 'Yes'); ?></button>
            </div>
        </div>
    </div>
</div>