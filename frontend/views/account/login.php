<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

use frontend\models\LoginForm;

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var LoginForm $model */

$this->title = Yii::t('views', 'Log In');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-login">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>

        <?= Yii::t('views', 'Log in') ?><?= Yii::t('views', ' to access all the features and create your unforgettable story.') ?>

    </p>

    <div class="row">

        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="my-1 mx-0" style="color:#999;">

                    <?= Yii::t('views', 'Forgot your password?') ?> <?= Html::a(Yii::t('views', 'Reset it'), ['account/request-password-reset']) ?>.

                    <br />

                    <?= Yii::t('views', 'Don’t have an account?') ?> <?= Html::a(Yii::t('views', 'Sign up now'), ['account/signup']) ?>.

                    <br />

                    <?= Yii::t('views', 'Didn’t get the verification email?') ?> <?= Html::a(Yii::t('views', 'Resend it'), ['account/resend-verification-email']) ?>.

                </div>

                <br />

                <div class="form-group">

                    <?= Html::submitButton(Yii::t('views', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>

                </div>

            <?php ActiveForm::end(); ?>

        </div>

    </div>

</div>
