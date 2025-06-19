<?php

use faryshta\widgets\EnumDropdown;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <p><?= Html::submitButton(Yii::t('frontend-views', 'Save'), ['class' => 'btn btn-success']) ?></p>

    <?= $form->field($model, 'username')->textInput() ?>

    <?= $form->field($model, 'email')->textInput() ?>

    <?= $form->field($model, 'type')->widget(EnumDropdown::class); ?>

    <?= $form->field($model, 'status')->widget(EnumDropdown::class); ?>

    <?php ActiveForm::end(); ?>

</div>
