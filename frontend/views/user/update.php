<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend-views', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="user-update">

    <h1><?= Yii::t('frontend-views', 'User'); ?>: <strong><?= Html::encode($model->username) ?></strong> (<?= Html::encode($model->email) ?>)</h1>

    <p><?= Yii::t('frontend-views', 'Please use this form to update selected user.') ?></p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
