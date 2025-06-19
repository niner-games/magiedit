<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m231025_115710_add_type_to_user_table
 */
class m231024_211711_change_default_value_for_user_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('user', 'status', $this->smallInteger()->notNull()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('user', 'status', $this->smallInteger()->notNull()->defaultValue(10));
    }
}