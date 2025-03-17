<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = Yii::t('backend-views', 'Add User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend-views', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="user-create">

    <h1><?= Yii::t('backend-views', 'User'); ?>: <strong><?= Html::encode($this->title) ?></strong></h1>

    <p><?= Yii::t('backend-views', 'Please fill out the following fields to add a user.') ?></p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
