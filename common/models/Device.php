<?php

namespace common\models;

use faryshta\base\EnumTrait;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "device".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $status
 * @property string $tid
 * @property string|null $name
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Command[] $received_commands
 * @property Command[] $sent_commands
 */
class Device extends \yii\db\ActiveRecord
{
    use EnumTrait;

    /**
     * PROPERTIES
     */
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const TYPE_TCMEDPLATFORM = 1;

    /**
     * CONFIGURATION
     */

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'device';
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
            [['tid'], 'string', 'max' => 25],
            [['name'], 'string', 'max' => 125],
            [['created_at', 'updated_at', 'status', 'type'], 'integer'],

            /**
             * Default values
             */
            ['name', 'default', 'value' => ''],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],

            /**
             * Other rules
             */
            [['type', 'tid'], 'required'],
            ['type', 'in', 'range' => [self::TYPE_TCMEDPLATFORM]],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
            ['tid', 'match', 'pattern' => '/^\S+$/', 'message' => Yii::t('common-models', 'Provided device TID is invalid.')],
            ['tid', 'unique', 'message' => Yii::t('common-models', 'A device with given device TID already exists in the database.')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common-models', 'ID'),
            'type' => Yii::t('common-models', 'Type'),
            'name' => Yii::t('common-models', 'Device Name'),
            'tid' => Yii::t('common-models', 'Unique Identifier'),
            'status' => Yii::t('common-models', 'Status'),
            'created_at' => Yii::t('common-models', 'Created At'),
            'updated_at' => Yii::t('common-models', 'Updated At'),
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
                self::STATUS_INACTIVE => Yii::t('common-models', 'Disabled'),
                self::STATUS_ACTIVE => Yii::t('common-models', 'Enabled'),
            ],
            'type' => [
                self::TYPE_TCMEDPLATFORM => Yii::t('common-models', 'TC-MED Platform'),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function extraFields()
    {
        return ['received_commands', 'sent_commands'];
    }

    /**
     * GETTERS AND SETTERS
     */

    /**
     * Gets query for [[Command]].
     *
     * Warning! Relation names are case sensitive. Method name MUST be "getreceived_commands"!
     *
     * @return \yii\db\ActiveQuery
     */
    public function getreceived_commands()
    {
        return $this->hasMany(Command::class, ['to' => 'id']);
    }

    /**
     * Gets query for [[Command]].
     *
     * Warning! Relation names are case sensitive. Method name MUST be "getsent_commands"!
     *
     * @return \yii\db\ActiveQuery
     */
    public function getsent_commands()
    {
        return $this->hasMany(Command::class, ['from' => 'id']);
    }

    /**
     * Gets all devices except self (server).
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getAllDevices()
    {
        return
            self::find()->asArray()->all();
    }

    /**
     * Gets server's name.
     *
     * @return string server name.
     */
    public static function getServerName($id = 1): string
    {
        return self::findOne($id)->name;
    }
}
