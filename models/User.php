<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
	const SCENARIO_LOGIN  = 'login';
    const SCENARIO_SEARCH = 'search';
	
    public static function tableName()
    {
        return 'users';
    }

    public function rules()
    {
        return [
            [['balance'],  'number'],	
			[['nickname'], 'trim'],
        ];
    }
	
    public function scenarios()
    {
		$scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_LOGIN]  = ['nickname'];
        $scenarios[self::SCENARIO_SEARCH] = ['nickname', 'balance'];
        return $scenarios;
    }
	
	public function transactions()
    {
        return [
            'login' => self::OP_INSERT,
        ];
    }
	
	public function search($params)
    {
		$query = self::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['nickname' => SORT_ASC],
                'attributes' => [
                    'nickname' => [
                        'asc'  => ['nickname' => SORT_ASC],
                        'desc' => ['nickname' => SORT_DESC],
                    ],
                    'balance' => [
                        'asc'  => ['balance' => SORT_ASC],
                        'desc' => ['balance' => SORT_DESC],
                    ],
                ],
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
		
		$query->filterWhere(['like', 'nickname', $this->nickname])->andfilterWhere(['like','balance', $this->balance]);

        return $dataProvider;
    }
	
	public static function findOrCreateByNickname($nickname)
	{
		$user = self::find()->where(['nickname' => $nickname])->one();
		if (!$user) {
			$user = new User(['scenario' => User::SCENARIO_LOGIN]);
			$user->nickname = $nickname;
			$user->save();
		}
		
		return $user;
	}
	
	public function addAmount($amount) 
	{
		$transaction = self::getDb()->beginTransaction();
		try {
			$this->balance += $amount;
			$this->save();
			$transaction->commit();
		} catch(\Throwable $e) {
			$transaction->rollBack();
			throw $e;
		}
		return true;
	}
	
	public function takeAwayAmount($amount)  
	{
		$transaction = self::getDb()->beginTransaction();
		try {
			$this->balance -= $amount;
			$this->save();
			return $transaction->commit();
		} catch(\Throwable $e) {
			$transaction->rollBack();
			throw $e;
		}
		return true;
	}
	
	/**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }
	
    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        //return $this->authKey === $authKey;
    }

}