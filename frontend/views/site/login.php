<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

$this->title = Yii::t('frontend-views', 'Login');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-login">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('frontend-views', 'Please fill out the following fields to login:') ?></p>

    <div class="row">

        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="my-1 mx-0" style="color:#999;">

                    <?= Yii::t('frontend-views', 'Forgotten password?') ?> <?= Html::a(Yii::t('frontend-views', 'Reset it'), ['site/request-password-reset']) ?>.

                    <br />

                    <?= Yii::t('frontend-views', 'You do not have an account?') ?> <?= Html::a(Yii::t('frontend-views', 'Register it'), ['site/signup']) ?>.

                    <br />

                    <?= Yii::t('frontend-views', 'The verification email did not arrive?') ?> <?= Html::a(Yii::t('frontend-views', 'Resend it'), ['site/resend-verification-email']) ?>.

                </div>

                <br />

                <div class="form-group">

                    <?= Html::submitButton(Yii::t('frontend-views', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>

                </div>

            <?php ActiveForm::end(); ?>

        </div>

    </div>

</div>
