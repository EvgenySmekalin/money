<?php

class TransactionFormCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('site/login');
        $I->submitForm('#login-form', [
            'LoginForm[nickname]' => 'test1',
        ]);
        $I->amOnRoute('site/transactions');
    }

    public function openTransactionsPage(\FunctionalTester $I)
    {
        $I->see('Transactions history', 'h3');
    }

    public function transactionWithEmptyNickname(\FunctionalTester $I)
    {
        $I->submitForm('#transaction-form', [
            'UserTransaction[receiverNickname]' => '',
            'UserTransaction[amount]' => 0.01,
            'transaction-button' => 1,
        ]);
        $I->expectTo('see validations errors');
        $I->see('Nickname cannot be blank.');
    }

    public function transactionWithLongNickname(\FunctionalTester $I)
    {
        $I->submitForm('#transaction-form', [
            'UserTransaction[receiverNickname]' => 'adminadminadminadminadminadminadminadmina',
            'UserTransaction[amount]' => 0.01,
            'transaction-button' => 1,
        ]);
        $I->expectTo('see validations errors');
        $I->see('Receiver Nickname should contain at most 40 characters.');
    }
	
	public function transactionToYourSelf(\FunctionalTester $I)
    {
        $I->submitForm('#transaction-form', [
            'UserTransaction[receiverNickname]' => 'test1',
            'UserTransaction[amount]' => 0.01,
            'transaction-button' => 1,
        ]);
        $I->expectTo('see validations errors');
        $I->see('Sending any amount to yourself is forbidden.');
    }
	
	public function transactionWithNullAmount(\FunctionalTester $I)
    {
        $I->submitForm('#transaction-form', [
            'UserTransaction[receiverNickname]' => 'test2',
            'UserTransaction[amount]' => null,
            'transaction-button' => 1,
        ]);
        $I->expectTo('see validations errors');
        $I->see('Amount cannot be blank.');
    }
	
	public function transactionWithZeroAmount(\FunctionalTester $I)
    {
        $I->submitForm('#transaction-form', [
            'UserTransaction[receiverNickname]' => 'test2',
            'UserTransaction[amount]' => 0,
            'transaction-button' => 1,
        ]);
        $I->expectTo('see validations errors');
        $I->see('Amount must be no less than 0.01.');
    }
	
	public function transactionWithNegativeAmount(\FunctionalTester $I)
    {
        $I->submitForm('#transaction-form', [
            'UserTransaction[receiverNickname]' => 'test2',
            'UserTransaction[amount]' => -10,
            'transaction-button' => 1,
        ]);
        $I->expectTo('see validations errors');
        $I->see('Amount must be no less than 0.01.');
    }
	
	public function transactionWithTooBigAmount(\FunctionalTester $I)
    {
        $I->submitForm('#transaction-form', [
            'UserTransaction[receiverNickname]' => 'test2',
            'UserTransaction[amount]' => 999999999999999999,
            'transaction-button' => 1,
        ]);
        $I->expectTo('see validations errors');
        $I->see('You can not have balance less then -1000. Chose different amount.');
    }

    public function transactionSuccessfull(\FunctionalTester $I)
    {
		$I->submitForm('#transaction-form', [
            'UserTransaction[receiverNickname]' => 'test2',
            'UserTransaction[amount]' => 0.01,
            'transaction-button' => 1,
        ]);
		$I->dontSee('test2','input#usertransaction-receivernickname');
		$I->dontSee('0.01','input#usertransaction-amount');
    }
}