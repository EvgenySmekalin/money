<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use app\models\UserTransaction;
use yii\data\ActiveDataProvider;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'transactions'],
                'rules' => [
                    [
                        'actions' => ['logout', 'transactions'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
		$searchModel = new User(/*['scenario' => User::SCENARIO_SEARCH]*/);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
        return $this->render('index', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
	
	/**
     * Transactions action.
     *
     * @return Response
     */
	public function actionTransactions()
    {
		$userModel = User::findOne(Yii::$app->user->id);
		$userTransaction = new UserTransaction();

		if (Yii::$app->request->post('transaction-button') && $userTransaction->load(Yii::$app->request->post()) && $userTransaction->sendAmount($userModel)) {
			$userTransaction->amount           = null;
			$userTransaction->receiverNickname = null;
		}
		
        $dataProvider = new ActiveDataProvider([
            'query' => UserTransaction::find()->where(['sender_id' => $userModel->id])->orderBy(['date' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);

        return $this->render('transactions', [
			'userTransaction' => $userTransaction, 
			'userModel'       => $userModel, 
			'dataProvider'    => $dataProvider
		]);
    }
}
