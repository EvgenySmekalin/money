<?php

namespace tests\models;

use app\models\UserTransaction;
use app\models\User;

class UserTransactionTest extends \Codeception\Test\Unit
{
	public function testValidateTransaction()
    {
        $userTransaction = new UserTransaction();

        $userTransaction->amount = null;
        $this->assertFalse($userTransaction->validate(['amount']));
		
		$userTransaction->amount = -1;
        $this->assertFalse($userTransaction->validate(['amount']));
		
		$userTransaction->amount = 0.01;
        $this->assertTrue($userTransaction->validate(['amount']));

		$userTransaction->receiverNickname = null;
        $this->assertFalse($userTransaction->validate(['receiverNickname']));
		
        $userTransaction->receiverNickname = 'toolooooongnaaaaaaameeeetoolooooongnaaaaaaameeee';
        $this->assertFalse($userTransaction->validate(['receiverNickname']));

        $userTransaction->receiverNickname = 'test2';
        $this->assertTrue($userTransaction->validate(['receiverNickname']));
    }
	
	public function testMakeATransaction()
    {
		$user = User::findIdentity(1);
		$userTransaction = new UserTransaction();

        $userTransaction->receiverNickname = 'test1';
        $userTransaction->amount = 0.01;
	    $this->assertFalse($userTransaction->sendAmount($user));

		$userTransaction->receiverNickname = 'test2';
        $userTransaction->amount = 9E18;
	    $this->assertFalse($userTransaction->sendAmount($user));
		
		$userTransaction->receiverNickname = 'test2';
        $userTransaction->amount = 0.01;
	    $this->assertTrue($userTransaction->sendAmount($user));
    }
}
