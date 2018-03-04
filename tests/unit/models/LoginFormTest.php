<?php

namespace tests\models;

use app\models\LoginForm;

class LoginFormTest extends \Codeception\Test\Unit
{
    private $model;

    protected function _after()
    {
        \Yii::$app->user->logout();
    }

    public function testLoginEmptyNickname()
    {
        $this->model = new LoginForm([
            'nickname' => null,
        ]);

        expect_not($this->model->login());
        expect_that(\Yii::$app->user->isGuest);
		expect($this->model->errors)->hasKey('nickname');
    }

    public function testLoginLongNickname()
    {
        $this->model = new LoginForm([
            'nickname' => 'stringstringstringstringstringstringstring',
        ]);

        expect_not($this->model->login());
        expect_that(\Yii::$app->user->isGuest);
        expect($this->model->errors)->hasKey('nickname');
    }

    public function testLoginCorrect()
    {
        $this->model = new LoginForm([
            'nickname' => 'test2',
        ]);

        expect_that($this->model->login());
        expect_not(\Yii::$app->user->isGuest);
        expect($this->model->errors)->hasntKey('nickname');
    }

}
