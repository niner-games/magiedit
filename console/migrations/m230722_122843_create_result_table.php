<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%result}}`.
 */
class m230722_122843_create_result_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%result}}', [
            'id' => $this->primaryKey(),
            'patient_id' => $this->integer(),
            'main_comment' => $this->text(),
            'hip_left' => $this->decimal(5,2),
            'hip_right' => $this->decimal(5,2),
            'hip_difference' => $this->decimal(5,2),
            'leg_left' => $this->decimal(5,2),
            'leg_right' => $this->decimal(5,2),
            'leg_difference' => $this->decimal(5,2),
            'shoulder_left' => $this->decimal(5,2),
            'shoulder_right' => $this->decimal(5,2),
            'shoulder_difference' => $this->decimal(5,2),
            'distance_feet' => $this->decimal(5,2),
            'distance_knees' => $this->decimal(5,2),
            'suspicion_scoliosis' => $this->boolean(),
            'suspicion_knee_varus' => $this->boolean(),
            'suspicion_knee_valgus' => $this->boolean(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        $this->addForeignKey('FK_result_user_created_by', '{{%result}}', 'created_by', '{{%user}}', 'id');
        $this->addForeignKey('FK_result_patient_patient_id', '{{%result}}', 'patient_id', '{{%patient}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('FK_result_user_created_by', '{{%result}}');
        $this->dropForeignKey('FK_result_patient_patient_id', '{{%result}}');

        $this->dropTable('{{%result}}');
    }
}