<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "examination".
 *
 * @property int $id
 * @property int|null $patient_id
 * @property int|null $created_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property User $user
 * @property Patient $patient
 * @property Result[] $results
 */
class Examination extends \yii\db\ActiveRecord
{
    /**
     * CONFIGURATION
     */

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'examination';
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
            [['patient_id', 'created_by', 'created_at', 'updated_at'], 'integer'],

            /**
             * Other rules
             */
            ['created_by', 'required', 'message' => Yii::t('common-models', 'An examination must be assigned to an user.')],
            ['patient_id', 'required', 'message' => Yii::t('common-models', 'An examination must be assigned to a patient.')],

            /**
             * Relations
             */
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id'], 'message' => Yii::t('common-models', 'The relation between an examination and an user is incorrect.')],
            [['patient_id'], 'exist', 'skipOnError' => true, 'targetClass' => Patient::class, 'targetAttribute' => ['patient_id' => 'id'], 'message' => Yii::t('common-models', 'The relation between an examination and a patient is incorrect.')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common-models', 'ID'),
            'patient_id' => Yii::t('common-models', 'Patient ID'),
            'created_by' => Yii::t('common-models', 'Created By'),
            'created_at' => Yii::t('common-models', 'Created At'),
            'updated_at' => Yii::t('common-models', 'Updated At'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function extraFields()
    {
        return ['patient', 'user'];
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
     * Gets query for [[Patient]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPatient()
    {
        return $this->hasOne(Patient::class, ['id' => 'patient_id']);
    }

    /**
     * Gets query for [[Results]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResults()
    {
        return $this->hasMany(Result::class, ['examination_id' => 'id']);
    }

    /**
     * QUERY FINDERS
     */

    /**
     * Gets all examinations.
     *
     * @return array
     */
    public static function getAllExaminations(): array
    {
        return self::find()->select(['id', 'created_by', 'updated_at'])->asArray()->all();
    }

    /**
     * Gets all examinations in form of array, needed by dropdown boxes.
     *
     * Since we identifiable examination data (when and by whom created) in separate model's attributes, we have to implement
     * our own version of ArrayHelper::map to map something like 'id' => 'updated_at' . ' (' . 'created_by' . ')'.
     *
     * @return array
     * @see ArrayHelper::map()
     */
    public static function getAllExaminationsAsArray(): array
    {
        $array = self::getAllExaminations();
        $result = [];

        foreach ($array as $element) {
            $key = ArrayHelper::getValue($element, 'id');
            $value = ArrayHelper::getValue($element, 'updated_at') . ' (' . ArrayHelper::getValue($element, 'created_by') . ')';

            $result[$key] = $value;
        }

        return $result;
    }
}
