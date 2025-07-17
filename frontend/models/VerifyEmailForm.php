<?php

namespace frontend\models;

use Yii;
use common\models\User;
use yii\base\Model;
use yii\base\InvalidArgumentException;

class VerifyEmailForm extends Model
{
    /**
     * PROPERTIES
     */
    /**
     * @var string
     */
    public $token;

    /**
     * @var User
     */
    private $_user;

    /**
     * REGULAR METHODS
     */

    /**
     * Creates a form model with given token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws InvalidArgumentException if token is empty or not valid
     */
    public function __construct($token, array $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException(Yii::t('models', 'Verify email token cannot be blank.'));
        }

        $this->_user = User::findByVerificationToken($token);

        if (!$this->_user) {
            throw new InvalidArgumentException('Wrong verify email token.');
        }

        parent::__construct($config);
    }

    /**
     * REGULAR METHODS
     */

    /**
     * Verify email
     *
     * @return User|null the saved model or null if saving fails
     */
    public function verifyEmail()
    {
        $user = $this->_user;
        $user->status = User::STATUS_ACTIVE;

        return $user->save(false) ? $user : null;
    }
}
