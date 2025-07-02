<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
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
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => Yii::t('frontend-models', 'Password reset is available for verified accounts only.')
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
     * REGULAR METHODS
     */

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail(): bool
    {
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }
        
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        $appName = Yii::$app->name;
        $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['account/reset-password', 'token' => $user->password_reset_token]);

        return Yii::$app
            ->mailer
            ->compose(
                [
                    'html' => 'passwordResetToken-html',
                    'text' => 'passwordResetToken-text'
                ],
                [
                    'user' => $user,
                    'appName' => $appName,
                    'resetLink' => $resetLink
                ]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => $appName])
            ->setTo($this->email)
            ->setSubject($appName . '. ' . Yii::t('frontend-models', 'Password Reset'))
            ->send();
    }
}
