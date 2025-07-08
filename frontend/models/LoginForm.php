<?php

namespace frontend\models;

use common\models\User;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    /**
     * PROPERTIES
     */
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;

    /**
     * CONFIGURATION
     */

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required', 'message' => Yii::t('common-models', 'This field cannot be blank.')],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('common-models', 'Username'),
            'password' => Yii::t('common-models', 'Password'),
            'rememberMe' => Yii::t('common-models', 'Remember Me')
        ];
    }

    /**
     * GETTERS AND SETTERS
     */

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser(): ?User
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    /**
     * REGULAR METHODS
     */

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('common-models', 'Incorrect username or password.'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login(): bool
    {
        if ($this->validate()) {
            $user = $this->getUser();

            return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        
        return false;
    }
}
