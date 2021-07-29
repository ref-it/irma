<?php


namespace app\controllers;


use app\models\db\ServiceUser;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\IdentityInterface;

class SiteController extends Controller
{

    public function behaviors() : array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => ['profile'],
                        'roles' => ['?'], // guests only
                    ],
                    [
                        'allow' => true,
                        'actions' => ['profile'],
                        'roles' => ['@'], // logged in only
                    ],
                    [
                        'allow' => true,
                        'actions' => [],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }

    public function actionHome() : string
    {
        return $this->render('home');
    }

    public function actionProfile() : string
    {


        return $this->render('profile', ['user' => Yii::$app->user->getIdentity()]);
    }

    public function actionImpressum(){
        //TODO fillme
    }

    public function actionDatenschutz(){
        //TODO fillme
    }
}