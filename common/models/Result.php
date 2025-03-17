<?php

namespace common\models;

use faryshta\base\EnumTrait;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "result".
 *
 * @property int $id
 * @property int|null $examination_id
 * @property string|null $main_comment
 * @property float|null $hip_left
 * @property float|null $hip_right
 * @property float|null $hip_difference
 * @property float|null $leg_left
 * @property float|null $leg_right
 * @property float|null $leg_difference
 * @property float|null $shoulder_left
 * @property float|null $shoulder_right
 * @property float|null $shoulder_difference
 * @property float|null $distance_feet
 * @property float|null $distance_knees
 * @property int|null $suspicion_scoliosis
 * @property int|null $suspicion_knee_varus
 * @property int|null $suspicion_knee_valgus
 * @property int|null $created_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property User $user
 * @property Examination $examination
 */
class Result extends \yii\db\ActiveRecord
{
    use EnumTrait;

    /**
     * PROPERTIES
     */
    const SUSPICION_NO = 0;
    const SUSPICION_YES = 1;

    /**
     * CONFIGURATION
     */

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'result';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false
            ]
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
            [['main_comment'], 'string'],
            [['examination_id', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['suspicion_scoliosis', 'suspicion_knee_varus', 'suspicion_knee_valgus'], 'boolean'],
            [[
                'hip_left',
                'hip_right',
                'hip_difference',
                'leg_left',
                'leg_right',
                'leg_difference',
                'shoulder_left',
                'shoulder_right',
                'shoulder_difference',
                'distance_feet',
                'distance_knees'
            ], 'double'],

            /**
             * Default values
             */
            [['main_comment'], 'default', 'value' => ''],
            [['suspicion_scoliosis', 'suspicion_knee_varus', 'suspicion_knee_valgus'], 'default', 'value' => false],
            [[
                'hip_left',
                'hip_right',
                'hip_difference',
                'leg_left',
                'leg_right',
                'leg_difference',
                'shoulder_left',
                'shoulder_right',
                'shoulder_difference',
                'distance_feet',
                'distance_knees'
            ], 'default', 'value' => 0.0],

            /**
             * Safe attributes
             */
            [['main_comment'], 'safe'],

            /**
             * Other rules
             */
            ['created_by', 'required', 'message' => Yii::t('common-models', 'A result must be assigned to an user.')],
            ['examination_id', 'required', 'message' => Yii::t('common-models', 'A result must be assigned to an examination.')],

            /**
             * Relations
             */
            [['examination_id'], 'exist', 'skipOnError' => true, 'targetClass' => Examination::class, 'targetAttribute' => ['examination_id' => 'id'], 'message' => Yii::t('common-models', 'The relation between a result and an examination is incorrect.')],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id'], 'message' => Yii::t('common-models', 'The relation between a result and an user is incorrect.')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common-models', 'ID'),
            'examination_id' => Yii::t('common-models', 'Examination'),
            'main_comment' => Yii::t('common-models', 'Comment'),
            'hip_left' => Yii::t('common-models', 'Left Hip'),
            'hip_right' => Yii::t('common-models', 'Right Hip'),
            'hip_difference' => Yii::t('common-models', 'Difference Between Hips'),
            'leg_left' => Yii::t('common-models', 'Left Leg'),
            'leg_right' => Yii::t('common-models', 'Right Leg'),
            'leg_difference' => Yii::t('common-models', 'Difference Between Legs'),
            'shoulder_left' => Yii::t('common-models', 'Left Shoulder'),
            'shoulder_right' => Yii::t('common-models', 'Right Shoulder'),
            'shoulder_difference' => Yii::t('common-models', 'Difference Between Shoulders'),
            'distance_feet' => Yii::t('common-models', 'Distance Between Feet'),
            'distance_knees' => Yii::t('common-models', 'Distance Between Knees'),
            'suspicion_scoliosis' => Yii::t('common-models', 'Suspected Scoliosis'),
            'suspicion_knee_varus' => Yii::t('common-models', 'Suspected Knee Varus'),
            'suspicion_knee_valgus' => Yii::t('common-models', 'Suspected Knee Valgus'),
            'created_by' => Yii::t('common-models', 'Created By'),
            'created_at' => Yii::t('common-models', 'Created At'),
            'updated_at' => Yii::t('common-models', 'Updated At')
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
            'suspicion_scoliosis' => [
                self::SUSPICION_NO => Yii::t('common-models', 'No'),
                self::SUSPICION_YES => Yii::t('common-models', 'Yes'),
            ],
            'suspicion_knee_varus' => [
                self::SUSPICION_NO => Yii::t('common-models', 'No'),
                self::SUSPICION_YES => Yii::t('common-models', 'Yes'),
            ],
            'suspicion_knee_valgus' => [
                self::SUSPICION_NO => Yii::t('common-models', 'No'),
                self::SUSPICION_YES => Yii::t('common-models', 'Yes'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function extraFields()
    {
        return ['examination', 'user'];
    }

    /**
     * GETTERS AND SETTERS
     */

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[Examination]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExamination()
    {
        return $this->hasOne(Examination::class, ['id' => 'examination_id']);
    }
}
