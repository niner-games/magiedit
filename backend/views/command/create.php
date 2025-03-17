<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use faryshta\widgets\EnumDropdown;

/** @var yii\web\View $this */
/** @var common\models\Command $model */
/** @var yii\db\ActiveRecord[] $devices */
/** @var yii\bootstrap5\ActiveForm $form */

$this->title = Yii::t('backend-views', 'Add Command');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend-views', 'Commands'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="command-create">

    <h1><?= Yii::t('backend-views', 'Command') ?>: <strong><?= $this->title; ?></h1>

    <p><?= Yii::t('backend-views', 'Please fill out the following fields to add a command.') ?></p>

    <div class="command-form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group">

            <?= Html::submitButton(Yii::t('backend-views', 'Save'), ['class' => 'btn btn-success']) ?>

        </div>

        <?= $form->field($model, 'to')->dropdownList($devices); ?>

        <?= $form->field($model, 'from')->dropdownList($devices); ?>

        <?= $form->field($model, 'type')->widget(EnumDropdown::class); ?>

        <?= $form->field($model, 'status')->widget(EnumDropdown::class); ?>

        <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

        <?php ActiveForm::end(); ?>

    </div>

</div>
