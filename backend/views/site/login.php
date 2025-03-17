<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = Yii::t('backend-views', 'Login');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-login">
    <div class="mt-5 offset-lg-3 col-lg-6">
        <h1><?= Html::encode($this->title) ?></h1>

        <p><?= Yii::t('backend-views', 'Please fill out the following fields to login:') ?></p>

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'rememberMe')->checkbox() ?>

        <div class="my-1 mx-0" style="color:#999;">

            <?= Yii::t('backend-views', 'Only logged-in users with administrator status have access to the backend. Login is also independent between the backend and the frontend. The person logged in to the backend must also log in to the backend.') ?>

            <br /><br />

            <?= Yii::t('backend-views', 'If you do not have an account, did not receive the verification email or have forgotten your password, please contact your system administrator. These kinds of situations must be solved manually.') ?>

        </div>

        <br />

            <div class="form-group">
                <?= Html::submitButton(Yii::t('backend-views', 'Login'), ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
