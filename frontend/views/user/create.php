<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = Yii::t('frontend-views', 'Add User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend-views', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('frontend-views', 'Please fill out the following fields to add a user.') ?></p>

    <div class="mb-3">

        <?= $this->render('_form', [
            'model' => $model,
            'showDeleteButton' => true
        ]) ?>

    </div>

    <p><?= Yii::t('frontend-views', 'The user will not be able to log in until a password is set. Inform them to reset password during first login.') ?></p>

</div>
