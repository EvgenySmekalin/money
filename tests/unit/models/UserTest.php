<?php

namespace tests\models;

use app\models\User;

class UserTest extends \Codeception\Test\Unit
{
    public function testFindUserById()
    {
        expect_that($user = User::findIdentity(1));
        expect($user->nickname)->equals('test1');

        expect_not(User::findIdentity(9999999999));
    }

    public function testFindUserByNickname()
    {
        expect_that($user = User::findOrCreateByNickname('test1'));
    }

	public function testCreateNewUserByNickname()
    {
        expect_that($user = User::findOrCreateByNickname('test' . time()));
		expect($user->balance)->equals(0.00);
    }
	
	public function testValidateUser()
    {
        $user = new User(['scenario' => User::SCENARIO_LOGIN]);

        $user->nickname = null;
        $this->assertFalse($user->validate(['nickname']));

        $user->nickname = 'toolooooongnaaaaaaameeeetoolooooongnaaaaaaameeee';
        $this->assertFalse($user->validate(['nickname']));

        $user->nickname = 'test1';
        $this->assertTrue($user->validate(['nickname']));
		
		$user->balance = null;
        $this->assertTrue($user->validate(['balance']));

        $user->balance = -1001;
        $this->assertFalse($user->validate(['balance']));

        $user->balance = 10;
        $this->assertTrue($user->validate(['balance']));
    }
}
