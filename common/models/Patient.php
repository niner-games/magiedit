<?php

namespace common\models;

use faryshta\base\EnumTrait;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "patient".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $surname
 * @property string|null $pesel
 * @property int|null $sex
 * @property string|null $birth_date
 * @property int|null $created_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property User $user
 * @property Examination[] $examinations
 */
class Patient extends \yii\db\ActiveRecord
{
    use EnumTrait;

    /**
     * PROPERTIES
     */
    const SEX_MAN = 0;
    const SEX_WOMAN = 1;

    /**
     * CONFIGURATION
     */

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'patient';
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
            [['sex'], 'boolean'],
            [['name'], 'string', 'max' => 15],
            [['surname'], 'string', 'max' => 25],
            [['pesel', 'created_by', 'created_at', 'updated_at'], 'integer'],

            /**
             * Safe attributes
             */
            [['birth_date'], 'safe'],

            /**
             * Other rules
             */
            [['name', 'surname'], 'trim'],
            ['created_by', 'required', 'message' => Yii::t('common-models', 'A patient must be assigned to an user.')],
            ['pesel', 'unique', 'message' => Yii::t('common-models', 'A patient with given PESEL number already exists in the database.')],

            /**
             * Relations
             */
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id'], 'message' => Yii::t('common-models', 'The relation between a patient and an user is incorrect.')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common-models', 'ID'),
            'name' => Yii::t('common-models', 'Name'),
            'surname' => Yii::t('common-models', 'Surname'),
            'pesel' => Yii::t('common-models', 'PESEL Number'),
            'sex' => Yii::t('common-models', 'Sex'),
            'birth_date' => Yii::t('common-models', 'Birth Date'),
            'created_by' => Yii::t('common-models', 'Created By'),
            'created_at' => Yii::t('common-models', 'Updated By'),
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
            'sex' => [
                self::SEX_MAN => Yii::t('common-models', 'Man'),
                self::SEX_WOMAN => Yii::t('common-models', 'Woman'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function extraFields()
    {
        return ['examinations', 'user'];
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
     * Gets query for [[Examinations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExaminations()
    {
        return $this->hasMany(Examination::class, ['patient_id' => 'id']);
    }

    /**
     * Gets full name of given patient (compositing of first and/or last name) or a "N/A" string instead.
     *
     * @return string user's full name or "N/A" string
     */
    public function getFullName(): string
    {
        return isset($this->name) || isset($this->surname) ? trim(trim($this->name) . ' ' . trim($this->surname)) : Yii::t('common-models', 'N/A');
    }

    /**
     * QUERY FINDERS
     */

    /**
     * Gets all patients.
     *
     * @return array
     */
    public static function getAllPatients(): array
    {
        return self::find()->select(['id', 'name', 'surname'])->asArray()->all();
    }

    /**
     * Gets all patients in form of array, needed by dropdown boxes.
     *
     * Since we keep patient name and surname in separate model's attributes, we have to implement our own version of
     * ArrayHelper::map to map something like 'id' => 'name' . ' ' . 'surname'.
     *
     * @return array
     * @see ArrayHelper::map()
     */
    public static function getAllPatientsAsArray(): array
    {
        $array = self::getAllPatients();
        $result = [];

        foreach ($array as $element) {
            $key = ArrayHelper::getValue($element, 'id');
            $value = ArrayHelper::getValue($element, 'name') . ' ' . ArrayHelper::getValue($element, 'surname');

            $result[$key] = $value;
        }

        return $result;
    }
}
