<?php

namespace common\models;

use faryshta\base\EnumTrait;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

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
 * @property string $name
 * @property string $surname
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
    const TYPE_OPERATOR = 1;
    const TYPE_PHYSIOTHERAPIST = 2;
    const TYPE_DOCTOR = 3;
    const TYPE_ADMINISTRATOR = 9;

    /**
     * CONFIGURATION
     */

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            /**
             * Data types
             */
            [['name'], 'string', 'min' => 2, 'max' => 15],
            [['surname'], 'string', 'min' => 2, 'max' => 25],
            [['auth_key'], 'string', 'min' => 2, 'max' => 32],
            [['username', 'password_hash', 'password_reset_token', 'email', 'verification_token'], 'string', 'min' => 2, 'max' => 255],
            [['created_at', 'updated_at', 'status', 'type'], 'integer'],

            /**
             * Default values
             */
            ['type', 'default', 'value' => self::TYPE_OPERATOR],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],

            /**
             * Safe attributes
             */
            [['name'], 'safe'],

            /**
             * Other rules
             */
            [['email', 'username', 'name', 'surname'], 'trim'],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            ['type', 'in', 'range' => [self::TYPE_OPERATOR, self::TYPE_PHYSIOTHERAPIST, self::TYPE_DOCTOR, self::TYPE_ADMINISTRATOR]],
            ['email', 'email', 'message' => Yii::t('common-models', 'This field must contain a valid e-mail address.')],
            [['username', 'email', 'surname', 'name'], 'required', 'message' => Yii::t('common-models', 'This field cannot be blank.')],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('common-models', 'This username has already been taken.')],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('common-models', 'This email address has already been taken.')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function extraFields()
    {
        return ['examinations', 'patients', 'results'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common-models', 'ID'),
            'username' => Yii::t('common-models', 'Username'),
            'auth_key' => Yii::t('common-models', 'Auth Key'),
            'password_hash' => Yii::t('common-models', 'Password Hash'),
            'password_reset_token' => Yii::t('common-models', 'Password Reset Token'),
            'email' => Yii::t('common-models', 'Email Address'),
            'status' => Yii::t('common-models', 'Status'),
            'created_at' => Yii::t('common-models', 'Created At'),
            'updated_at' => Yii::t('common-models', 'Updated At'),
            'verification_token' => Yii::t('common-models', 'Verification Token'),
            'password' => Yii::t('common-models', 'Password'),
            'type' => Yii::t('common-models', 'Type'),
            'name' => Yii::t('common-models', 'Name'),
            'surname' => Yii::t('common-models', 'Surname'),
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
                self::STATUS_ACTIVE => Yii::t('common-models', 'Active'),
//                self::STATUS_DELETED => Yii::t('common-models', 'Deleted'),
                self::STATUS_INACTIVE => Yii::t('common-models', 'Inactive'),
            ],
            'type' => [
                self::TYPE_DOCTOR => Yii::t('common-models', 'Doctor'),
                self::TYPE_OPERATOR => Yii::t('common-models', 'Operator'),
                self::TYPE_ADMINISTRATOR => Yii::t('common-models', 'Administrator'),
                self::TYPE_PHYSIOTHERAPIST => Yii::t('common-models', 'Physiotherapist'),
            ],
        ];
    }

    /**
     * GETTERS AND SETTERS
     */

    /**
     * Gets query for [[Examinations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExaminations()
    {
        return $this->hasMany(Examination::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Patients]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPatients()
    {
        return $this->hasMany(Patient::class, ['created_by' => 'id']);
    }

    /**
     * Gets full name of given user (compositing of first and/or last name) or a "N/A" string instead.
     *
     * @return string user's full name or "N/A" string
     */
    public function getFullName(): string
    {
        return isset($this->name) || isset($this->surname) ? trim(trim($this->name) . ' ' . trim($this->surname)) : Yii::t('common-models', 'N/A');
    }

    /**
     * Gets query for [[Results]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResults()
    {
        return $this->hasMany(Result::class, ['created_by' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * QUERY FINDERS
     */

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
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
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Gets all users.
     *
     * @return array
     */
    public static function getAllUsers(): array
    {
        return self::find()->select(['id', 'name', 'surname'])->asArray()->all();
    }

    /**
     * Gets all users in form of array, needed by dropdown boxes.
     *
     * Since we keep user name and surname in separate model's attributes, we have to implement our own version of
     * ArrayHelper::map to map something like 'id' => 'name' . ' ' . 'surname'.
     *
     * @return array
     * @see ArrayHelper::map()
     */
    public static function getAllUsersAsArray(): array
    {
        $array = self::getAllUsers();
        $result = [];

        foreach ($array as $element) {
            $key = ArrayHelper::getValue($element, 'id');
            $value = ArrayHelper::getValue($element, 'name') . ' ' . ArrayHelper::getValue($element, 'surname');

            $result[$key] = $value;
        }

        return $result;
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
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}