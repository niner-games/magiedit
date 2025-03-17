<?php

use yii\db\Migration;
use yii\db\Expression;

/**
 * Handles the creation of table `{{%device}}`.
 */
class m230927_132419_create_device_and_command_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%device}}', [
            'id' => $this->primaryKey(),
            'type' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull(),
            'tid' => $this->string(25)->notNull(),
            'name' => $this->string(125),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%command}}', [
            'id' => $this->primaryKey(),
            'to' => $this->integer(),
            'from' => $this->integer(),
            'type' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull(),
            'body' => $this->text(),
            'result' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('FK_command_device_to', '{{%command}}', 'to', '{{%device}}', 'id');
        $this->addForeignKey('FK_command_device_from', '{{%command}}', 'from', '{{%device}}', 'id');

        $now = time();

        $this->insert('{{%device}}', [
            'name' => 'TC-MED Server',
            'tid' => 'WEBAPP',
            'status' => 0,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $this->insert('{{%device}}', [
            'name' => 'TC-MED Platform',
            'tid' => 'T1A-1',
            'type' => 1,
            'status' => 1,
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('FK_command_device_to', '{{%command}}');
        $this->dropForeignKey('FK_command_device_from', '{{%command}}');

        $this->dropTable('{{%command}}');
        $this->dropTable('{{%device}}');
    }
}
