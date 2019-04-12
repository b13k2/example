<?php

use yii\db\Migration;

/**
 * Class m190404_163548_add_region_info
 */
class m190404_163548_add_region_info extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%module}}', [
            'id'            => 4,
            'name'          => 'Регионы',
            'str_id'        => 'region',
            'icon'          => 'fa fa-map',
            'sorting'       => 4,
        ]);

        $this->insert('{{%menu_item}}', [
            'id'            => 4,
            'pid'           => 0,
            'menu_group_id' => 1,
            'name'          => 'Регионы',
            'module_id'     => 4,
            'sorting'       => 4,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190404_163548_add_region_info cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190404_163548_add_region_info cannot be reverted.\n";

        return false;
    }
    */
}
