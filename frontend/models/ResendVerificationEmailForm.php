<?php

namespace frontend\models;

use Yii;
use common\models\User;
use yii\base\Model;

class ResendVerificationEmailForm extends Model
{
    /**
     * PROPERTIES
     */
    public $email;

    /**
     * CONFIGURATION
     */

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email', 'message' => Yii::t('frontend-models', 'This field must contain a valid e-mail address.')],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_INACTIVE],
                'message' => Yii::t('frontend-models', 'There is no user with this email address.')
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('frontend-models', 'Email Address')
        ];
    }

    /**
     * MAGIC METHODS
     */

    /**
     * Sends confirmation email to user.
     *
     * @return bool whether the email was sent.
     */
    public function sendEmail()
    {
        $user = User::findOne([
            'email' => $this->email,
            'status' => User::STATUS_INACTIVE
        ]);

        if ($user === null) {
            return false;
        }

        $appName = Yii::t('frontend-application', "MagiEdit");
        $verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);

        return Yii::$app
            ->mailer
            ->compose(
                [
                    'html' => 'emailVerify-html',
                    'text' => 'emailVerify-text'
                ],
                [
                    'user' => $user,
                    'appName' => $appName,
                    'verifyLink' => $verifyLink
                ]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => $appName])
            ->setTo($this->email)
            ->setSubject($appName . '. ' . Yii::t('frontend-models', 'Account registration') . Yii::$app->name)
            ->send();
    }
}
