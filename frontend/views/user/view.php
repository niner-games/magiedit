<?php

use common\models\User;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$displayedUserIsLoggedIn = (Yii::$app->user->id === $model->id);
$totalSum = count($model->patients) + count($model->examinations) + count($model->results);

$this->title = $model->fullName;
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

        <?php if ($totalSum > 0 || $displayedUserIsLoggedIn): ?>

            <span class="d-inline-block" tabindex="0" title="<?= (($totalSum > 0) ? Yii::t('frontend-views', 'You must assign all user\'s objects to some other user before deleting it.') : Yii::t('frontend-controllers', 'You cannot delete currently logged-in user.')); ?>" data-bs-placement="right">

        <?php endif; ?>

        <?= Html::a(Yii::t('frontend-views', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger' . ($totalSum > 0 || $displayedUserIsLoggedIn ? ' disabled' : ''),
            'data' => [
                'confirm' => Yii::t('frontend-views', 'Are you sure you want to delete this user?'),
                'method' => 'post',
            ],
        ]) ?>

        <?php if ($totalSum > 0 || $displayedUserIsLoggedIn): ?></span><?php endif; ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'surname',
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

    <?php if (!$displayedUserIsLoggedIn): ?>

        <?= Yii::t('frontend-views', 'This user has'); ?>

        <strong><?= Yii::t('frontend-views', '{p, plural, =0{no patients} =1{# patient} other{# patients}}', ['p' => count($model->patients)]); ?></strong>,

        <strong><?= Yii::t('frontend-views', '{p, plural, =0{no examinations} =1{# examination} other{# examinations}}', ['p' => count($model->examinations)]); ?></strong>

        <?= Yii::t('frontend-views', 'and'); ?>

        <strong><?= Yii::t('frontend-views', '{p, plural, =0{no results} =1{# result} other{# results}}', ['p' => count($model->results)]); ?></strong>

        <?= Yii::t('frontend-views', 'and therefore'); ?>

        <?php if ($totalSum > 0): ?>

            <em><?= Yii::t('frontend-views', 'cannot be deleted'); ?></em>.

            <?= Yii::t('frontend-views', 'You must assign all user\'s objects to some other user before deleting it.'); ?>

        <?php else: ?>

            <em><?= Yii::t('frontend-views', 'can be deleted'); ?></em>.

        <?php endif; ?>

    <?php else: ?>

        <?= Yii::t('frontend-controllers', 'You cannot delete currently logged-in user.') ?>

    <?php endif; ?>

</small>