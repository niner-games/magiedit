<?php

use faryshta\widgets\EnumDropdown;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var array $users */
/** @var array $examinations */
/** @var yii\web\View $this */
/** @var common\models\Result $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="result-form">

    <?php $form = ActiveForm::begin(); ?>

    <p><?= Html::submitButton(Yii::t('frontend-views', 'Save'), ['class' => 'btn btn-success']) ?></p>

    <?= $form->field($model, 'examination_id')->dropdownList($examinations)->label(Yii::t('frontend-views', 'Examination')); ?>

    <?= $form->field($model, 'created_by')->dropdownList($users)->label(Yii::t('frontend-views', 'Operator')); ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'hip_left')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hip_right')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hip_difference')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'leg_left')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'leg_right')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'leg_difference')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shoulder_left')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shoulder_right')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shoulder_difference')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'distance_feet')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'distance_knees')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'suspicion_scoliosis')->widget(EnumDropdown::class); ?>

    <?= $form->field($model, 'suspicion_knee_varus')->widget(EnumDropdown::class); ?>

    <?= $form->field($model, 'suspicion_knee_valgus')->widget(EnumDropdown::class); ?>

    <?= $form->field($model, 'main_comment')->textarea(['rows' => 6]) ?>

    <?php ActiveForm::end(); ?>

</div>
