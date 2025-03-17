<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use faryshta\base\EnumTrait;
use yii\validators\CompareValidator;

/**
 * This is the model class for table "command".
 *
 * @property int $id
 * @property int|null $to
 * @property int|null $from
 * @property int $type
 * @property int $status
 * @property string|null $body
 * @property string|null $result
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Device $source
 * @property Device $destination
 */
class Command extends \yii\db\ActiveRecord
{
    use EnumTrait;

    /**
     * PROPERTIES
     */
    const STATUS_SENT = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_DONE = 3;
    const STATUS_ABORTED = 7;
    const STATUS_REJECTED = 8;
    const STATUS_ERROR = 9;
    const TYPE_RESET = 0;
    const TYPE_SET = 1;
    const TYPE_MOVE = 2;

    /**
     * CONFIGURATION
     */

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'command';
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
            [['to', 'from', 'type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['body', 'result'], 'string'],

            /**
             * Default values
             */
            ['status', 'default', 'value' => self::STATUS_SENT],
            ['status', 'default', 'value' => self::STATUS_DONE],
            ['to', 'default', 'value' => 2],
            ['from', 'default', 'value' => 1],
            [['body', 'result'], 'default', 'value' => ""],

            /**
             * Other rules
             */
            [['type'], 'required'],
            ['from', 'compare', 'compareAttribute' => 'to', 'operator' => '!==', 'type' => CompareValidator::TYPE_NUMBER, 'message' => Yii::t('common-models', 'Sending and receiving device must be different.')],
            ['from', 'required', 'when' => function($model) {
                return !is_numeric($model->to);
            }, 'message' => Yii::t('common-models', 'A command must be assigned to either a sending or a receiving device.')],
            ['status', 'in', 'range' => [
                self::STATUS_SENT,
                self::STATUS_PROCESSING,
                self::STATUS_DONE,
                self::STATUS_ABORTED,
                self::STATUS_REJECTED,
                self::STATUS_ERROR
            ]],
            ['type', 'in', 'range' => [self::TYPE_RESET, self::TYPE_SET, self::TYPE_MOVE]],

            /**
             * Relations
             */
            [['to'], 'exist', 'skipOnError' => true, 'targetClass' => Device::class, 'targetAttribute' => ['to' => 'id'], 'message' => Yii::t('common-models', 'The relation between a command and a receiving device is incorrect.')],
            [['from'], 'exist', 'skipOnError' => true, 'targetClass' => Device::class, 'targetAttribute' => ['from' => 'id'], 'message' => Yii::t('common-models', 'The relation between a command and a sending device is incorrect.')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common-models', 'ID'),
            'to' => Yii::t('common-models', 'To'),
            'from' => Yii::t('common-models', 'From'),
            'type' => Yii::t('common-models', 'Type'),
            'status' => Yii::t('common-models', 'Status'),
            'body' => Yii::t('common-models', 'Content'),
            'result' => Yii::t('common-models', 'Result'),
            'created_at' => Yii::t('common-models', 'Sent At'),
            'updated_at' => Yii::t('common-models', 'Responded At'),
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
                self::STATUS_SENT => Yii::t('common-models', 'Sent'),
                self::STATUS_DONE => Yii::t('common-models', 'Done'),
            ],
            'type' => [
                self::TYPE_SET => Yii::t('common-models', 'Set position'),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function extraFields()
    {
        return ['source', 'destination'];
    }

    /**
     * GETTERS AND SETTERS
     */

    /**
     * Gets query for [[Device]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSource(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Device::class, ['id' => 'from']);
    }

    /**
     * Gets query for [[Device]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDestination(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Device::class, ['id' => 'to']);
    }

    /**
     * Gets source device's name.
     *
     * @return string
     */
    public function getSourceName(): string
    {
        return Device::getServerName($this->from);
    }

    /**
     * Gets destination device's name.
     *
     * @return string
     */
    public function getDestinationName(): string
    {
        return Device::getServerName($this->to);
    }

    /**
     * Gets command's path, i.e. source and destination device.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->getSourceName() . ' ⇢ ' . $this->getDestinationName();
    }

    /**
     * Gets command's response in more human-readable way.
     */
    public function getParsedData($scope): array|string
    {
        if ($scope === '' || !str_contains($scope, ';')) return $scope;

        $responseParts = [];
        $responseArray = explode(';', rtrim($scope, ';'));

        if (!is_array($responseArray) || count($responseArray) === 0) return $scope;

        foreach ($responseArray as $item) {
            $parts = explode('=', $item);

            if (isset($parts[1])) {
                if (is_numeric($parts[1])) {
                    $value = floatval($parts[1]);
                    $value = ($this->type === self::TYPE_SET) ? number_format($value / 100, 2) . ' ' . Yii::t('common-models', 'mm') : $value;

                    $responseParts[trim($parts[0])] = $value;
                }
                else $responseParts[trim($parts[0])] = $parts[1];
            } else {
                $responseParts[] = trim($parts[0]);
            }
        }

        if (!is_array($responseParts) || count($responseParts) === 0) return $scope;

        return $responseParts;
    }
}