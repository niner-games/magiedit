<?php

namespace frontend\models;

use common\models\User;

use Yii;
use yii\base\Model;
use recaptcha\ReCaptchaValidator;

/**
 * Signup form
 */
class SignupForm extends Model
{
    /**
     * PROPERTIES
     */
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $reCaptcha = '';

    /**
     * CONFIGURATION
     */

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            /**
             * Data types
             */
            ['email', 'string', 'min' => 5, 'max' => 255],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
            ['reCaptcha', ReCaptchaValidator::class, 'message' => Yii::t('models', 'You must solve reCAPTCHA in order to continue.')],

            /**
             * Other rules
             */
            [['email', 'username'], 'trim'],
            ['email', 'email', 'message' => Yii::t('models', 'This field must contain a valid email address.')],
            [['username', 'email', 'password'], 'required', 'message' => Yii::t('models', 'This field cannot be blank.')],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('models', 'This username has already been taken.')],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('models', 'This email address has already been taken.')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'username' => Yii::t('models', 'Username'),
            'password' => Yii::t('models', 'Password'),
            'email' => Yii::t('models', 'Email Address'),
        ];
    }

    /**
     * REGULAR METHODS
     */

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     * @throws yii\base\Exception when setting password fails (due to unknown error in generated password hash)
     * @throws yii\base\InvalidArgumentException when generation of auth key or email verification token fails
     * @throws yii\db\Exception in case model update or insert fails
     */
    public function signup(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $user = new User();

        $user->email = $this->email;
        $user->username = $this->username;

        $user->generateAuthKey();
        $user->setPassword($this->password);
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
            ->setSubject($appName . '. ' . Yii::t('models', 'Account Sign Up'))
            ->send();
    }
}
