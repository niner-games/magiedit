<?php

namespace common\models;

use faryshta\base\EnumTrait;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $verification_token
 * @property integer $type
 *
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    use EnumTrait;

    /**
     * PROPERTIES
     */
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 1;
    const STATUS_ACTIVE = 2;
    const TYPE_REGULAR = 1;
    const TYPE_ADMINISTRATOR = 9;

    /**
     * CONFIGURATION
     */

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            /**
             * Data types
             */
            [['auth_key'], 'string', 'min' => 2, 'max' => 32],
            [['username', 'password_hash', 'password_reset_token', 'email', 'verification_token'], 'string', 'min' => 2, 'max' => 255],
            [['created_at', 'updated_at', 'status', 'type'], 'integer'],

            /**
             * Default values
             */
            ['type', 'default', 'value' => self::TYPE_REGULAR],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],

            /**
             * Safe attributes
             */
            [['name'], 'safe'],

            /**
             * Other rules
             */
            [['email', 'username'], 'trim'],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            ['type', 'in', 'range' => [self::TYPE_REGULAR, self::TYPE_ADMINISTRATOR]],
            ['email', 'email', 'message' => Yii::t('models', 'This field must contain a valid email address.')],
            [['username', 'email'], 'required', 'message' => Yii::t('models', 'This field cannot be blank.')],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('models', 'This username has already been taken.')],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('models', 'This email address has already been taken.')],

            /**
             * Custom validations
             */
            [['status'], 'validateSelfDeactivation'],
            [['type'], 'validateMinimumActiveAdmins'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('models', 'ID'),
            'username' => Yii::t('models', 'Username'),
            'auth_key' => Yii::t('models', 'Auth Key'),
            'password_hash' => Yii::t('models', 'Password Hash'),
            'password_reset_token' => Yii::t('models', 'Password Reset Token'),
            'email' => Yii::t('models', 'Email Address'),
            'status' => Yii::t('models', 'Status'),
            'created_at' => Yii::t('models', 'Created At'),
            'updated_at' => Yii::t('models', 'Updated At'),
            'verification_token' => Yii::t('models', 'Verification Token'),
            'password' => Yii::t('models', 'Password'),
            'type' => Yii::t('models', 'Type'),
        ];
    }


    /**
     * Returns a configuration array used by EnumTrait to replace numeric (enum) value with actual human-readable text.
     *
     * The structure is `['model-field' => ['enum-value' => 'human-readable text']]`
     *
     * @return array[]
     * @see \faryshta\base\EnumTrait
     */
    public static function enums(): array
    {
        return [
            'status' => [
                self::STATUS_ACTIVE => Yii::t('models', 'Active'),
                self::STATUS_INACTIVE => Yii::t('models', 'Inactive'),
            ],
            'type' => [
                self::TYPE_REGULAR => Yii::t('models', 'Regular'),
                self::TYPE_ADMINISTRATOR => Yii::t('models', 'Administrator'),
            ],
        ];
    }

    /**
     * GETTERS AND SETTERS
     */

    /**
     * {@inheritdoc}
     * @return array|mixed|null
     */
    public function getId(): mixed
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @throws Exception on bad password parameter or cost parameter
     */
    public function setPassword(string $password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * QUERY FINDERS
     */

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id): User|IdentityInterface|null
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null): ?IdentityInterface
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername(string $username): null|static
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken(string $token): null|static
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken(string $token): null|static
    {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * REGULAR METHODS
     */

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid(string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);

        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): ?bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates "remember me" authentication key
     * @throws Exception on failure
     */
    public function generateAuthKey(): void
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     * @throws Exception on failure
     */
    public function generatePasswordResetToken(): void
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     * @throws Exception on failure
     */
    public function generateEmailVerificationToken(): void
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken(): void
    {
        $this->password_reset_token = null;
    }

    /**
     * Checks whether given user model is an administrator.
     *
     * @return bool whether this user is an administrator
     */
    public function getIsAdmin(): bool
    {
        return $this->type === self::TYPE_ADMINISTRATOR;
    }

    /**
     * Checks whether given user model is currently logged in use.
     *
     * @return bool whether this user is currently logged in
     */
    public function getIsCurrentUser(): bool
    {
        return $this->id === Yii::$app->user->id;
    }

    public function validateMinimumActiveAdmins($attribute, $params): void
    {
        if (Yii::$app->user->id === NULL || $this->id === NULL) {
            return;
        }

        if ($this->type !== self::TYPE_ADMINISTRATOR || $this->status !== self::STATUS_ACTIVE) {
            if ($this->countActiveAdmins() < 1) {
                $this->addError($attribute, Yii::t('models', 'At least one active administrator is required in this application.'));
            }
        }
    }

    public function validateSelfDeactivation($attribute, $params): void
    {
        if (Yii::$app->user->id === NULL || $this->id === NULL) {
            return;
        }

        if ($this->id === (int)Yii::$app->user->id && (int)$this->status === self::STATUS_INACTIVE) {
            $this->addError($attribute, Yii::t('models', 'Your own account must remain active.'));
        }
    }

    /**
     * Checks how many users in type of administrator are currently in the system.
     *
     * @return int number of administrators
     */
    protected function countActiveAdmins(): int
    {
        return self::find()
            ->where(['type' => self::TYPE_ADMINISTRATOR, 'status' => self::STATUS_ACTIVE])
            ->andWhere(['<>', 'id', $this->id])
            ->count();
    }
}