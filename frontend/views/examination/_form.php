<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var array $users */
/** @var array $patients */
/** @var yii\web\View $this */
/** @var common\models\Examination $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="examination-form">

    <?php $form = ActiveForm::begin(); ?>

    <p><?= Html::submitButton(Yii::t('frontend-views', 'Save'), ['class' => 'btn btn-success']) ?></p>

    <?= $form->field($model, 'patient_id')->dropdownList($patients)->label(Yii::t('frontend-views', 'Patient')); ?>

    <?= $form->field($model, 'created_by')->dropdownList($users)->label(Yii::t('frontend-views', 'Operator')); ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?php ActiveForm::end(); ?>

</div>
