<?php


namespace app\controllers;


use app\models\config\AdminConfigModel;

class ConfigController extends \yii\web\Controller
{

    public function actionAdmin() : string
    {
        $model = new AdminConfigModel('app');
        $allLanguages = \Yii::$app->config->getSupportedLanguages();
        return $this->render('admin', [
            'allLang' => $allLanguages,
            'model' => $model,
        ]);
    }



}