<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m231025_115710_add_name_and_type_to_user_table
 */
class m231025_115710_add_name_and_type_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'type', $this->integer()->notNull()->defaultValue(1));
        
        $this->alterColumn('user', 'status', $this->smallInteger()->notNull()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'type');
        
        $this->alterColumn('user', 'status', $this->smallInteger()->notNull()->defaultValue(10));
    }
}