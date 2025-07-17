<?php

/** @var string $appName */
/** @var string $resetLink */
/** @var yii\web\View $this */
/** @var common\models\User $user */

?>

<?= Yii::t('mail', 'Hello!') ?> 

    <?= Yii::t('mail', 'For the account') ?>

    <?= $user->username ?>

    <?= Yii::t('mail', 'somebody has requested a password to the be reset.') ?>

<?= Yii::t('mail', 'If it was you then follow the link below to continue and reset your password:') ?>

<?= $resetLink ?>

<?= Yii::t('mail', 'If it wasn’t you then you may safely ignore this message. Your password won’t be changed.') ?>

<?= Yii::t('mail', 'Have good day!') ?>

<?= Yii::t('mail', 'The {app-name}’s Team', ['app-name' => $appName]); ?>