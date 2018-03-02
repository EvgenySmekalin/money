<?php

namespace app\models;
use yii\db\ActiveRecord;

class UserTransaction extends ActiveRecord
{
	public $receiverNickname;
    public static function tableName()
    {
        return 'user_transactions';
    }

    public function rules()
    {
        return [
            [['sender_id', 'receiver_id', 'amount'], 'required'],
			['amount', 'number'],
			['receiverNickname', 'string', 'max' => 40],
			['receiverNickname', 'trim'],
            [['sender_id', 'receiver_id', 'amount', 'receiverNickname'], 'safe'],
        ];
    }
	
	public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'receiver_id']);
    }
	
	public function sendAmount(User $user) 
	{
		if ($user->nickname === $this->receiverNickname) {
			$this->addError('receiverNickname', "Sending any amount to yourself is forbidden.");
			return;
		}
		
		if (($user->balance - $this->amount) < -1000) {
			$this->addError('amount', "You can't have balance less then -1000. Chose different amount.");
			return;
		}
		
		$receiver = User::findOrCreateByNickname($this->receiverNickname);
		$receiver->addAmount($this->amount);
		$user->takeAwayAmount($this->amount);
		$this->sender_id   = $user->id;
		$this->receiver_id = $receiver->id;
		return $this->save();
	}
}