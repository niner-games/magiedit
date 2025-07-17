<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\SignupForm $model */

use common\widgets\ReCaptcha;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = Yii::t('views', 'Sign Up');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('views', 'Please fill in the fields below to create your account.') ?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'reCaptcha')->widget(ReCaptcha::class, [
                    'id' => 'sign-up-captcha',
                    'render' => ReCaptcha::RENDER_EXPLICIT,
                ])->label(false) ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('views', 'Sign Up'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
