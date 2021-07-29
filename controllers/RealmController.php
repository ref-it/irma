<?php


namespace app\controllers;


use app\models\realm\Realm;
use app\rbac\rules\CreateRealmRule;
use yii\filters\AccessControl;
use yii\filters\AccessRule;

class RealmController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['new'],
                        'rule' => CreateRealmRule::class,
                    ],
                ],
            ],
        ];
    }

    public function actionNew() : string
    {
        return $this->render('new', ['model' => $model]);
    }

}