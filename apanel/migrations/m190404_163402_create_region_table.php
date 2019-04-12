<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%region}}`.
 */
class m190404_163402_create_region_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%region}}', [
            'id'            => $this->integer()->unsigned()->notNull() . ' AUTO_INCREMENT PRIMARY KEY',
            'name'          => $this->string(50)->notNull(),
            'public_name'   => $this->string(50)->notNull(),
            'latitude'      => $this->decimal(9,7)->notNull(),
            'longitude'     => $this->decimal(10,7)->notNull(),
            //
            'updated'       => 'timestamp null ON UPDATE CURRENT_TIMESTAMP',
            'created'       => $this->timestamp()->notNull() . ' DEFAULT CURRENT_TIMESTAMP',
            'sorting'       => $this->integer()->unsigned()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%region}}');
    }
}
