<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%examination}}`.
 */
class m230824_155054_create_examination_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%examination}}', [
            'id' => $this->primaryKey(),
            'patient_id' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        $this->dropForeignKey('FK_result_patient_patient_id', '{{%result}}');
        $this->dropColumn('{{%result}}', 'patient_id');
        $this->addColumn('{{%result}}', 'examination_id', $this->integer());

        $this->addForeignKey('FK_examination_user_created_by', '{{%examination}}', 'created_by', '{{%user}}', 'id');
        $this->addForeignKey('FK_examination_patient_patient_id', '{{%examination}}', 'patient_id', '{{%patient}}', 'id');
        $this->addForeignKey('FK_result_examination_examination_id', '{{%result}}', 'examination_id', '{{%examination}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('FK_result_examination_examination_id', '{{%result}}');
        $this->dropForeignKey('FK_examination_patient_patient_id', '{{%examination}}');
        $this->dropForeignKey('FK_examination_user_created_by', '{{%examination}}');

        $this->dropColumn('{{%result}}', 'examination_id');
        $this->addColumn('{{%result}}', 'patient_id', $this->integer());
        $this->addForeignKey('FK_result_patient_patient_id', '{{%result}}', 'patient_id', '{{%patient}}', 'id');

        $this->dropTable('{{%examination}}');
    }
}
