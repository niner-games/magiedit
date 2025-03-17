<?php

use faryshta\widgets\EnumDropdown;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var array $users */
/** @var yii\web\View $this */
/** @var common\models\Patient $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="patient-form">

    <?php $form = ActiveForm::begin(); ?>

    <p><?= Html::submitButton(Yii::t('frontend-views', 'Save'), ['class' => 'btn btn-success']) ?></p>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pesel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sex')->widget(EnumDropdown::class); ?>

    <?= $form->field($model, 'birth_date')->textInput() ?>

    <?= $form->field($model, 'created_by')->dropdownList($users)->label(Yii::t('frontend-views', 'Operator')); ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?php ActiveForm::end(); ?>

</div>
