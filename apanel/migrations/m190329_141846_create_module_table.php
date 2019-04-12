<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%module}}`.
 */
class m190329_141846_create_module_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%module}}', [
            'id'            => $this->smallInteger()->unsigned()->notNull() . ' AUTO_INCREMENT PRIMARY KEY',
            'name'          => $this->string(45)->notNull(),
            'str_id'        => $this->string(45)->notNull() . ' UNIQUE',
            'icon'          => $this->string(45)->notNull(),
            //
            'updated'       => 'timestamp null ON UPDATE CURRENT_TIMESTAMP',
            'created'       => $this->timestamp()->notNull() . ' DEFAULT CURRENT_TIMESTAMP',
            'sorting'       => $this->integer()->unsigned()->notNull(),
        ], 'AUTO_INCREMENT = 1000');

        $this->insert('{{%module}}', [
            'id'            => 1,
            'name'          => 'Меню',
            'str_id'        => 'menu-group',
            'icon'          => 'fa fa-info-circle',
            'sorting'       => 1,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%module}}');
    }
}
