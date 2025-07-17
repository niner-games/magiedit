<?php

use yii\helpers\Html;

/** @var string $appName */
/** @var string $verifyLink */
/** @var yii\web\View $this */
/** @var common\models\User $user */

?>

<div class="verify-email">

    <p><?= Yii::t('mail', 'Hello!') ?> </p>

    <p><?= Yii::t('mail', 'Somebody has created an account in our website with the following data:') ?></p>

    <ul>

        <li><?= Yii::t('mail', 'Username:') ?> <strong><?= Html::encode($user->username) ?></strong>.</li>

        <li><?= Yii::t('mail', 'Email address:') ?> <strong><?= Html::encode($user->email) ?></strong>.</li>

    </ul>

    <p><?= Yii::t('mail', 'If it was you then follow the link below to verify your email address:') ?></p>

    <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>

    <p><?= Yii::t('mail', 'If it wasn’t you then you may safely ignore this message. Unverified email addresses are automatically removed from our system.') ?></p>

    <p><?= Yii::t('mail', 'Have good day!') ?></p>

    <p><?= Yii::t('mail', 'The {app-name}’s Team', ['app-name' => $appName]); ?></p>

</div>
