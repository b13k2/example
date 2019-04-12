<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m190401_105313_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id'            => $this->integer()->unsigned()->notNull() . ' AUTO_INCREMENT PRIMARY KEY',
            'login'         => $this->string(30)->notNull() . ' UNIQUE',
            'passwd'        => $this->string(60)->notNull(),
            'auth_key'      => $this->string(32)->notNull(),
            //
            'updated'       => 'timestamp null ON UPDATE CURRENT_TIMESTAMP',
            'created'       => $this->timestamp()->notNull() . ' DEFAULT CURRENT_TIMESTAMP',
            'sorting'       => $this->integer()->unsigned()->notNull(),
        ]);

        $this->insert('{{%user}}', [
            'id'            => 1,
            'login'         => 'root',
            'passwd'        => '$2y$13$F61I0Sz9GBz9Jo10qj5fzOzJmQ9ZBoGvHVrwpyea5rVoKXHCyvqy6',
            'auth_key'      => '',
            'sorting'       => 1,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
