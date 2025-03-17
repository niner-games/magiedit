<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = $model->fullName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend-views', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="user-update">

    <h1><?= Yii::t('backend-views', 'User'); ?>: <strong><?= Html::encode($this->title) ?></strong> (<?= Html::encode($model->username) ?>)</h1>

    <p><?= Yii::t('backend-views', 'Please use this form to update selected user.') ?></p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
