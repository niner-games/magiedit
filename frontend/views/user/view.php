<?php

use common\models\User;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$displayedUserIsLoggedIn = (Yii::$app->user->id === $model->id);

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend-views', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("$('span').tooltip()");

YiiAsset::register($this);

?>

<div class="user-view">

    <h1><?= Yii::t('frontend-views', 'User'); ?>: <strong><?= Html::encode($this->title) ?></strong> (<?= Html::encode($model->username) ?>)</h1>

    <p><?= Yii::t('frontend-views', 'Details of selected user are provided below.') ?></p>

    <p>
        <?= Html::a(Yii::t('frontend-views', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        <?php if ($displayedUserIsLoggedIn): ?>

        <span class="d-inline-block" tabindex="0" title="<?= Yii::t('frontend-controllers', 'You cannot delete currently logged-in user.') ?>" data-bs-placement="right">

        <?php endif; ?>

        <?= Html::a(Yii::t('frontend-views', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger' . ($displayedUserIsLoggedIn ? ' disabled' : ''),
            'data' => [
                'confirm' => Yii::t('frontend-views', 'Are you sure you want to delete this user?'),
                'method' => 'post',
            ],
        ]) ?>

        <?php if ($displayedUserIsLoggedIn): ?></span><?php endif; ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            'email:email',
            [
                'attribute' => 'type',
                'value' => function ($model, $widget) {
                    return $model->getAttributeDesc('type');
                }
            ],
            [
                'attribute' => 'status',
                'value' => function ($model, $widget) {
                    return $model->getAttributeDesc('status');
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

<small class="text-secondary">

    <?= Yii::t('frontend-views', 'This user'); ?>

    <?php if ($model->status === User::STATUS_ACTIVE): ?>

        <strong><?= Yii::t('frontend-views', 'is active'); ?></strong>

        <?= Yii::t('frontend-views', 'and'); ?>

        <em><?= Yii::t('frontend-views', 'can login'); ?></em>

    <?php else: ?>

        <strong><?= Yii::t('frontend-views', 'is not active'); ?></strong>

        <?= Yii::t('frontend-views', 'and'); ?>

        <em><?= Yii::t('frontend-views', 'cannot login'); ?></em>

    <?php endif; ?>

    .<br>

    <?php if ($displayedUserIsLoggedIn): ?>

        <?= Yii::t('frontend-controllers', 'You cannot delete currently logged-in user.') ?>

    <?php endif; ?>

</small>