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
        $this->addColumn('{{%user}}', 'name', $this->string(15)->notNull()->defaultValue('Jan'));
        $this->addColumn('{{%user}}', 'surname', $this->string(25)->notNull()->defaultValue('Kowalski'));

        $names = ['Antoni', 'Aleksander', 'Franciszek', 'Leon', 'Jakub', 'Mikołaj', 'Nikodem', 'Stanisław', 'Szymon', 'Marcin', 'Łukasz', 'Tomasz'];
        $surnames = ['Wiśniewski', 'Wójcik', 'Kowalczyk', 'Kamiński', 'Zieliński', 'Szymański', 'Woźniak', 'Witoszek', 'Sowa', 'Trejderowski'];

        foreach((new Query)->from('user')->each() as $user) {
            $this->update('user', [
                'type' => rand(1, 3),
                'name' => $names[rand(0, 11)],
                'surname' => $surnames[rand(0, 9)]
            ], ['id' => $user['id']]);
        }

        $this->alterColumn('user', 'status', $this->smallInteger()->notNull()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'type');
        $this->dropColumn('{{%user}}', 'name');
        $this->dropColumn('{{%user}}', 'surname');

        $this->alterColumn('user', 'status', $this->smallInteger()->notNull()->defaultValue(10));
    }
}