<?php

use yii\db\Migration;
use yii\db\Schema;
/**
 * Handles the creation of table `users`.
 */
class m180301_061752_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->createTable('users', [
            'id'          => $this->primaryKey(),
			'nickname'    => $this->string(40)->notNull(),
			'balance'     => $this->decimal(18, 2)->notNull()->defaultValue(0.00),
			'date_create' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT CURRENT_TIMESTAMP' ,
			'UNIQUE (nickname)'
        ], 'DEFAULT CHARSET=utf8');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		echo "m180301_061752_create_users_table cannot be reverted.\n";
		return false;
    }
}
