<?php

use yii\helpers\Html;

/** @var string $appName */
/** @var string $resetLink */
/** @var yii\web\View $this */
/** @var common\models\User $user */

?>

<div class="password-reset">

    <p><?= Yii::t('mail', 'Hello!') ?> </p>

    <p>

        <?= Yii::t('mail', 'For the account') ?>

        <strong><?= Html::encode($user->username) ?></strong>

        <?= Yii::t('mail', 'somebody has requested a password to the be reset.') ?>

    </p>

    <p><?= Yii::t('mail', 'If it was you then follow the link below to continue and reset your password:') ?></p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>

    <p><?= Yii::t('mail', 'If it wasn’t you then you may safely ignore this message. Your password won’t be changed.') ?></p>

    <p><?= Yii::t('mail', 'Have good day!') ?></p>

    <p><?= Yii::t('mail', 'The {app-name}’s Team', ['app-name' => $appName]); ?></p>

</div>