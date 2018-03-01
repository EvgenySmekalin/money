<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class MoneyUser extends ActiveRecord implements \yii\web\IdentityInterface
{
    public static function tableName()
    {
        return 'users';
    }

    public function rules()
    {
        return [
            /*['nickname', 'required'],
            ['nickname', 'length' => [3, 40]],*/
            ['nickname', 'trim'],
            /*['nickname', 'unique'],*/
            ['balance', 'safe'],	
            ['balance', 'number'],	
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
	
	/**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return null;
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
        //return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        //return $this->authKey === $authKey;
    }

}