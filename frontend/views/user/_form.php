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

        <p>

            <?= Html::submitButton(Yii::t('frontend-views', 'Save'), ['class' => 'btn btn-success']) ?>

            <?php if (!empty($showDeleteButton)) : ?>
                <?= Html::a(Yii::t('frontend-views', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger delete-user-btn',
                    'data' => [
                        'modal-username' => Html::encode($model->username),
                        'modal-type' => Html::encode($model->getAttributeDesc('type')),
                        'modal-status' => Html::encode($model->getAttributeDesc('status'))
                    ],
                ]) ?>
            <?php endif; ?>

        </p>

        <table class="table table-striped table-bordered detail-view table-success">
            <tr>
                <th class="align-middle" style="width: 33%"><?= $model->getAttributeLabel('username') ?></th>
                <td class="align-middle" style="width: 67%">
                    <?= $form->field($model, 'username', [
                        'template' => '{input}{error}', // Remove label wrapper
                    ])->textInput()->label(false) ?>
                </td>
            </tr>
            <tr>
                <th class="align-middle"><?= $model->getAttributeLabel('email') ?></th>
                <td>
                    <?= $form->field($model, 'email', [
                        'template' => '{input}{error}',
                    ])->textInput()->label(false) ?>
                </td>
            </tr>
            <tr>
                <th class="align-middle"><?= $model->getAttributeLabel('type') ?></th>
                <td>
                    <?= $form->field($model, 'type', [
                        'template' => '{input}{error}',
                    ])->widget(EnumDropdown::class)->label(false) ?>
                </td>
            </tr>
            <tr>
                <th class="align-middle"><?= $model->getAttributeLabel('status') ?></th>
                <td>
                    <?= $form->field($model, 'status', [
                        'template' => '{input}{error}',
                    ])->widget(EnumDropdown::class)->label(false) ?>
                </td>
            </tr>
        </table>

    <?php ActiveForm::end(); ?>

</div>
