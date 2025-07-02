<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    /**
     * PROPERTIES
     */
    public string $email = '';
    public string $username = '';
    public string $password = '';

    /**
     * CONFIGURATION
     */

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            /**
             * Data types
             */
            [['email'], 'string', 'min' => 5, 'max' => 255],
            [['username'], 'string', 'min' => 2, 'max' => 255],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],

            /**
             * Other rules
             */
            [['email', 'username'], 'trim'],
            ['email', 'email', 'message' => Yii::t('frontend-models', 'This field must contain a valid email address.')],
            [['username', 'email', 'password'], 'required', 'message' => Yii::t('frontend-models', 'This field cannot be blank.')],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('frontend-models', 'This username has already been taken.')],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('frontend-models', 'This email address has already been taken.')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('frontend-models', 'Username'),
            'password' => Yii::t('frontend-models', 'Password'),
            'email' => Yii::t('frontend-models', 'Email Address'),
        ];
    }

    /**
     * REGULAR METHODS
     */

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();

        $user->email = $this->email;
        $user->username = $this->username;

        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        return $user->save() && $this->sendEmail($user);
    }

    /**
     * Sends confirmation email to user.
     *
     * @param User $user user model to with email should be send.
     *
     * @return bool whether the email was sent.
     */
    protected function sendEmail(User $user): bool
    {
        $appName = Yii::$app->name;
        $verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['account/verify-email', 'token' => $user->verification_token]);

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
            ->setSubject($appName . '. ' . Yii::t('frontend-models', 'Account Sign Up'))
            ->send();
    }
}
