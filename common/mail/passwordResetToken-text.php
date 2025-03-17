<?php

/** @var string $appName */
/** @var string $resetLink */
/** @var yii\web\View $this */
/** @var common\models\User $user */

?>

<?= Yii::t('common-mail', 'Hello!') ?> 

    <?= Yii::t('common-mail', 'For the account') ?>

    <?= $user->username ?>

    <?= Yii::t('common-mail', 'somebody has requested a password to the be reset.') ?>

<?= Yii::t('common-mail', 'If it was you then follow the link below to continue and reset your password:') ?>

<?= $resetLink ?>

<?= Yii::t('common-mail', 'If it wasn\'t you then you may safely ignore this message. Your password won\'t be changed.') ?>

<?= Yii::t('common-mail', 'Have good day!') ?>

<?= Yii::t('common-mail', 'The {app-name}\'s Team', ['app-name' => $appName]); ?>