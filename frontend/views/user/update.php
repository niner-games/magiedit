<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="user-update">

    <h1><strong><?= Html::encode($model->username) ?></strong> (<?= Html::encode($model->email) ?>)</h1>

    <p><?= Yii::t('views', 'Please use this form to update selected user.') ?></p>

    <?= $this->render('_form', [
        'model' => $model,
        'showDeleteButton' => true
    ]) ?>

    <?= $this->render('_dialog') ?>

</div>
