<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%menu_group}}`.
 */
class m190329_132341_create_menu_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%menu_group}}', [
            'id'            => $this->smallInteger()->unsigned()->notNull() . ' AUTO_INCREMENT PRIMARY KEY',
            'name'          => $this->string(45)->notNull(),
            'str_id'        => $this->string(45)->notNull() . ' UNIQUE',
            //
            'updated'       => 'timestamp null ON UPDATE CURRENT_TIMESTAMP',
            'created'       => $this->timestamp()->notNull() . ' DEFAULT CURRENT_TIMESTAMP',
            'sorting'       => $this->integer()->unsigned()->notNull(),
        ], 'AUTO_INCREMENT = 1000');

        $this->insert('{{%menu_group}}', [
            'id'            => 1,
            'name'          => 'Меню быстрого доступа',
            'str_id'        => 'quick',
            'sorting'       => 1,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%menu_group}}');
    }
}

