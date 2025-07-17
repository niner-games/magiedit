<?php

    use common\models\User;
    use faryshta\widgets\EnumDropdown;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

    /** @var yii\web\View $this */
    /** @var bool $isCurrentUser */
    /** @var bool $showDeleteButton */
    /** @var common\models\User $model */
    /** @var yii\widgets\ActiveForm $form */

    $statusActive = ($model->status === User::STATUS_ACTIVE);
    $hasAuthKey = !empty($model->auth_key);

    if ($statusActive && $hasAuthKey) {
        $message = '<strong>' . Yii::t('views', 'is active') . '</strong>' .
            Yii::t('views', ', so they ') .
            '<em>' . Yii::t('views', 'can log in') . '</em>.';
    } else {
        if (!$statusActive && !$hasAuthKey) {
            $reason = Yii::t('views', 'is not active') .
                Yii::t('views', ' and ') .
                Yii::t('views', 'has no password set');
        } elseif (!$statusActive) {
            $reason = Yii::t('views', 'is not active');
        } else {
            $reason = Yii::t('views', 'has no password set');
        }

        $message = '<strong>' . $reason . '</strong>' .
            Yii::t('views', ', so they ') .
            '<em>' . Yii::t('views', 'cannot log in') . '</em>.';
    }

    $userStatusActive = User::STATUS_ACTIVE;

    $this->registerJs("$('span').tooltip()");
?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const authKeySet = <?= json_encode(!empty($model->auth_key)) ?>;
        const summaryBox = document.getElementById('login-status-summary');
        const statusField = document.querySelector('[name="User[status]"]');

        summaryBox.innerHTML = <?= json_encode($message) ?>;

        function updateSummary() {
            let reasonText = '';
            const status = parseInt(statusField.value);
            let summaryText = '<?= Yii::t('views', 'This user'); ?>';

            if (status === <?= json_encode($userStatusActive) ?> && authKeySet) {
                summaryText = summaryText + ' <strong><?= Yii::t('views', 'is active') ?></strong>' +
                    '<?= Yii::t('views', ', so they ') ?>' +
                    '<em><?= Yii::t('views', 'can log in') ?></em>.';
            } else {
                if (status !== <?= json_encode($userStatusActive) ?> && !authKeySet) {
                    reasonText = '<?= Yii::t('views', 'is not active') ?>' +
                        '<?= Yii::t('views', ' and ') ?>' +
                        '<?= Yii::t('views', 'has no password set') ?>';
                } else if (status !== <?= json_encode($userStatusActive) ?>) {
                    reasonText = '<?= Yii::t('views', 'is not active') ?>';
                } else {
                    reasonText = '<?= Yii::t('views', 'has no password set') ?>';
                }

                summaryText = summaryText + ' <strong>' + reasonText + '</strong>' +
                    '<?= Yii::t('views', ', so they ') ?>' +
                    '<em><?= Yii::t('views', 'cannot log in') ?></em>.';
            }

            summaryBox.innerHTML = summaryText;
        }

        updateSummary();

        statusField.addEventListener('change', updateSummary);
    });
</script>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

        <p>

            <?= Html::submitButton(Yii::t('views', 'Save'), ['class' => 'btn btn-success']) ?>

            <?php if ($showDeleteButton): ?>

                <?php if ($isCurrentUser): ?>

                    <span class="d-inline-block" tabindex="0" title="<?= Yii::t('controllers', 'You cannot delete currently logged-in user.') ?>" data-bs-placement="right">

                <?php endif; ?>

                <?= Html::a(Yii::t('views', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger delete-user-btn' . ($isCurrentUser ? ' disabled' : ''),
                    'data' => [
                        'modal-username' => Html::encode($model->username),
                        'modal-type' => Html::encode($model->getAttributeDesc('type')),
                        'modal-status' => Html::encode($model->getAttributeDesc('status'))
                    ],
                ]) ?>

                <?php if ($isCurrentUser): ?></span><?php endif; ?>

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

    <p>

        <span id="login-status-summary"></span>

        <?php if ($isCurrentUser): ?>

            <br /><?= Yii::t('controllers', 'You cannot delete currently logged-in user.') ?>

        <?php endif; ?>

        <?php if (!$showDeleteButton): ?>

            <br /><?= Yii::t('views', 'The user will not be able to log in until a password is set. Inform them to reset password during first login.') ?>

        <?php endif; ?>

    </p>

</div>