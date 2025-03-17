<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use faryshta\widgets\EnumDropdown;

/** @var string $type */
/** @var string $status */
/** @var yii\web\View $this */
/** @var common\models\Command $model */
/** @var yii\bootstrap5\ActiveForm $form */

$type = $model->getAttributeDesc('type');
$status = $model->getAttributeDesc('status');

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend-views', 'Commands'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $type.' ('.$status.')', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend-views', 'Update');

?>

<div class="command-update">

    <h1><?= Yii::t('backend-views', 'Command') ?>: <strong><?= $type; ?></strong> (<?= $status; ?>)</h1>

    <p><?= Yii::t('backend-views', 'Please use this form to update selected command.') ?></p>

    <div class="command-form">

        <?php $form = ActiveForm::begin(); ?>

        <p><?= Html::submitButton(Yii::t('backend-views', 'Save'), ['class' => 'btn btn-success']) ?></p>

        <?= $form->field($model, 'type')->widget(EnumDropdown::class); ?>

        <?= $form->field($model, 'status')->widget(EnumDropdown::class); ?>

        <?= $form->field($model, 'body')->textarea(['rows' => 5]) ?>

        <?= $form->field($model, 'result')->textarea(['rows' => 5]) ?>

        <?php ActiveForm::end(); ?>

    </div>

</div>
