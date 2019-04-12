<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%menu_item}}`.
 */
class m190329_133525_create_menu_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%menu_item}}', [
            'id'            => $this->smallInteger()->unsigned()->notNull() . ' AUTO_INCREMENT PRIMARY KEY',
            'pid'           => $this->smallInteger()->unsigned()->notNull(),
            'menu_group_id' => $this->smallInteger()->unsigned()->notNull(),
            'name'          => $this->string(45)->notNull(),
            'module_id'     => $this->smallInteger()->unsigned()->notNull(),
            //
            'updated'       => 'timestamp null ON UPDATE CURRENT_TIMESTAMP',
            'created'       => $this->timestamp()->notNull() . ' DEFAULT CURRENT_TIMESTAMP',
            'sorting'       => $this->integer()->unsigned()->notNull(),
        ], 'AUTO_INCREMENT = 1000');

        $this->insert('{{%menu_item}}', [
            'id'            => 1,
            'pid'           => 0,
            'menu_group_id' => 1,
            'name'          => 'Меню',
            'module_id'     => 1,
            'sorting'       => 1,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%menu_item}}');
    }
}
