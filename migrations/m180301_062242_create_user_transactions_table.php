<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `user_transactions`.
 */
class m180301_062242_create_user_transactions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_transactions', [
            'sender_id'   => $this->integer(11)->notNull(),
            'receiver_id' => $this->integer(11)->notNull(),
			'amount'      => $this->decimal(18, 2)->notNull()->defaultValue(0.00),
			'date'        => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ], 'DEFAULT CHARSET=utf8');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180301_062242_create_user_transactions_table cannot be reverted.\n";
		return false;
    }
}
