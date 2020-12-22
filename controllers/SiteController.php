<?php


namespace app\controllers;


use app\models\site\LoginForm;
use yii\web\ErrorAction;
use yii\web\Response;

class SiteController extends \yii\web\Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }

    public function actionHome() : string {
        return $this->render('home');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $model->scenario = LoginForm::SCENARIO_LOGIN;
        if ($model->load(\Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
        }
        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout() : Response {
        \Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionProfile() : string
    {
        return $this->render('profile', ['identity' => \Yii::$app->user->getIdentity()]);
    }

    public function actionImpressum(){
        //TODO fillme
    }

    public function actionDatenschutz(){
        //TODO fillme
    }


}