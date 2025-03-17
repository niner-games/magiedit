<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%patient}}`.
 */
class m230722_102134_create_patient_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%patient}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(15),
            'surname' => $this->string(25),
            'pesel' => $this->string(11),
            'sex' => $this->boolean(),
            'birth_date' => $this->date(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        $this->addForeignKey('FK_patient_user_created_by', '{{%patient}}', 'created_by', '{{%user}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('FK_patient_user_created_by', '{{%patient}}');

        $this->dropTable('{{%patient}}');
    }
}
