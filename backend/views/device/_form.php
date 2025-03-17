<?php

use faryshta\widgets\EnumDropdown;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Device $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="device-form">

    <?php $form = ActiveForm::begin(); ?>

    <p><?= Html::submitButton(Yii::t('backend-views', 'Save'), ['class' => 'btn btn-success']) ?></p>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->widget(EnumDropdown::class); ?>

    <?= $form->field($model, 'status')->widget(EnumDropdown::class); ?>

    <?= $form->field($model, 'updated_at')->textInput()->label(Yii::t('backend-views', 'Last contact')) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?php ActiveForm::end(); ?>

</div>
