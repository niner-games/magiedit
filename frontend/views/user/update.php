<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var bool $isCurrentUser */
/** @var common\models\User $model */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend-views', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="user-update">

    <h1><strong><?= Html::encode($model->username) ?></strong> (<?= Html::encode($model->email) ?>)</h1>

    <p><?= Yii::t('frontend-views', 'Please use this form to update selected user.') ?></p>

    <?= $this->render('_form', [
        'model' => $model,
        'showDeleteButton' => true,
        'isCurrentUser' => $isCurrentUser
    ]) ?>

    <?= $this->render('_dialog', []) ?>

</div>
