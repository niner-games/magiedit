<?php

    use common\models\User;
    use yii\helpers\Html;
    use yii\web\YiiAsset;
    use yii\widgets\DetailView;

    /** @var yii\web\View $this */
    /** @var common\models\User $model */

    $this->title = $model->username;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Users'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;

    $this->registerJs("$('span').tooltip()");

    YiiAsset::register($this);

?>

<div class="user-view">

    <h1><strong><?= Html::encode($model->username) ?></strong> (<?= Html::encode($model->email) ?>)</h1>
    <p><?= Yii::t('views', 'Details of selected user are provided below.') ?></p>

    <p>
        <?= Html::a(Yii::t('views', 'Edit'), ['update', 'id' => $model->id], ['class' => 'btn btn-success']) ?>

        <?php if ($model->getIsCurrentUser()): ?>

            <span class="d-inline-block" tabindex="0" title="<?= Yii::t('controllers', 'You cannot delete currently logged-in user.') ?>" data-bs-placement="right">

        <?php endif; ?>

        <?= Html::a(Yii::t('views', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger delete-user-btn' . ($model->getIsCurrentUser() ? ' disabled' : ''),
            'data' => [
                'modal-username' => Html::encode($model->username),
                'modal-type' => Html::encode($model->getAttributeDesc('type')),
                'modal-status' => Html::encode($model->getAttributeDesc('status'))
            ],
        ]) ?>

        <?php if ($model->getIsCurrentUser()): ?></span><?php endif; ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'options' => [
            'class' => 'table table-striped table-bordered table-hover table-success mt-3',
            'style' => 'border-color: #B6C1BA; border-radius: .375rem; overflow: hidden;',
        ],
        'template' => '<tr><th style="width: 33%; vertical-align: middle;">{label}</th><td style="width: 67%;">{value}</td></tr>',
        'attributes' => [
            'username',
            'email:email',
            [
                'attribute' => 'type',
                'value' => function ($model, $widget) {
                    return $model->getAttributeDesc('type');
                },
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

<p>

    <?= Yii::t('views', 'This user'); ?>

    <?php
        $statusActive = ($model->status === User::STATUS_ACTIVE);
        $hasAuthKey = !empty($model->auth_key);

        if ($statusActive && $hasAuthKey):
    ?>

        <strong><?= Yii::t('views', 'is active'); ?></strong><?= Yii::t('views', ', so they '); ?>
        <em><?= Yii::t('views', 'can log in'); ?></em>.

    <?php else: ?>

        <?php
        if (!$statusActive && !$hasAuthKey) {
            $reason = Yii::t('views', 'is not active');
            $reason .= Yii::t('views', ' and ');
            $reason .= Yii::t('views', 'has no password set');
        } elseif (!$statusActive) {
            $reason = Yii::t('views', 'is not active');
        } else {
            $reason = Yii::t('views', 'has no password set');
        }
        ?>

        <strong><?= $reason; ?></strong><?= Yii::t('views', ', so they '); ?>
        <em><?= Yii::t('views', 'cannot log in'); ?></em>.

    <?php endif; ?>

    <?php if ($model->getIsCurrentUser()): ?>

        <br /><?= Yii::t('controllers', 'You cannot delete currently logged-in user.') ?>

    <?php endif; ?>

</p>

<?= $this->render('_dialog', []) ?>