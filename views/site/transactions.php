<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;

$this->title = 'Transactions';
?>
<style>
	.no-padding-left {
		padding-left: 0;
	}
	table thead th{
		text-align: center;
	}
</style>

<div class="site-transactions">
    <h1><?= $userModel->nickname ?>'s&nbsp;<?= Html::encode($this->title) ?></h1>
	
	<h3>Balance: <?= $userModel->balance ?></h3>
	
	
	<?php $form = ActiveForm::begin([
        'id' => 'transaction-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

        <?= $form->field($userTransaction, 'receiverNickname')->textInput(['placeholder' => 'Nickname', 'autofocus' => true])->label('Send to:') ?>
        <?= $form->field($userTransaction, 'amount')->textInput(['placeholder' => 'e.g. 1.15'])->label('Amount') ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Send value', ['class' => 'btn btn-primary', 'name' => 'transaction-button', 'value' => 1]) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

    <div class="col-lg-offset-1" style="color:#999;">
        You can't send any amount to yourself (logged user).<br>
        If user with such nickname exists - you will log in.
    </div>
	
	<h3>Transactions history</h3>
	<div class="row">
        <div class="col-lg-12">
			 <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'label' => 'Receiver Nickname',
                        'attribute' => 'users.nickname',
						'value' => function($model) {
							return $model->user->nickname;
						}
                    ],
                    [
                        'label' => 'Amount',
                        'attribute' => 'user_transactions.amount',
						'value' => 'amount',
						'contentOptions' => [
							'style' => 'text-align: right;'
						]
                    ],
					[
                        'label' => 'Date',
                        'attribute' => 'user_transactions.date',
                        'value' => 'date',
						'contentOptions' => [
							'style' => 'text-align: center;'
						]
                    ],
					
                ],
            ]); ?>


        </div>
    </div>
	
</div>
