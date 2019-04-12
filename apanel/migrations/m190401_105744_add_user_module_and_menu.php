<?php

use yii\db\Migration;

/**
 * Class m190401_105744_add_user_module_and_menu
 */
class m190401_105744_add_user_module_and_menu extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%module}}', [
            'id'            => 2,
            'name'          => 'Пользователи',
            'str_id'        => 'user',
            'icon'          => 'fa fa-users',
            'sorting'       => 2,
        ]);

        $this->insert('{{%menu_item}}', [
            'id'            => 2,
            'pid'           => 0,
            'menu_group_id' => 1,
            'name'          => 'Пользователи',
            'module_id'     => 2,
            'sorting'       => 2,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190401_105744_add_user_module_and_menu cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190401_105744_add_user_module_and_menu cannot be reverted.\n";

        return false;
    }
    */
}
