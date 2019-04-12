<?php

use yii\db\Migration;

/**
 * Class m190401_142302_add_module_to_menu
 */
class m190401_142302_add_module_to_menu extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%module}}', [
            'id'            => 3,
            'name'          => 'Модули',
            'str_id'        => 'module',
            'icon'          => 'fa fa-object-group',
            'sorting'       => 3,
        ]);

        $this->insert('{{%menu_item}}', [
            'id'            => 3,
            'pid'           => 0,
            'menu_group_id' => 1,
            'name'          => 'Модули',
            'module_id'     => 3,
            'sorting'       => 3,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190401_142302_add_module_to_menu cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190401_142302_add_module_to_menu cannot be reverted.\n";

        return false;
    }
    */
}
