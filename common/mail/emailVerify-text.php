<?php

/** @var string $appName */
/** @var string $verifyLink */
/** @var yii\web\View $this */
/** @var common\models\User $user */

?>

<?= Yii::t('common-mail', 'Hello!') ?> 

<?= Yii::t('common-mail', 'Somebody has created an account in our website with the following data:') ?>

    <?= Yii::t('common-mail', 'Username:') ?> <?= $user->username ?>.

    <?= Yii::t('common-mail', 'Email address:') ?> <?= $user->email ?>.

<?= Yii::t('common-mail', 'If it was you then follow the link below to verify your email address:') ?>

<?= $verifyLink ?>

<?= Yii::t('common-mail', 'If it wasn’t you then you may safely ignore this message. Unverified email addresses are automatically removed from our system.') ?>

<?= Yii::t('common-mail', 'Have good day!') ?>

<?= Yii::t('common-mail', 'The {app-name}’s Team', ['app-name' => $appName]); ?>