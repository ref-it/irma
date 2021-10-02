<?php


namespace app\controllers;


use app\models\db\GroupAssertion;
use app\models\db\Realm;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;

class GroupAssertionController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [],
                        'roles' => ['realm-management'],
                        'roleParams' => ['realm' => Yii::$app->request->get('realm')]

                    ],
                    [
                        'allow' => false,
                        'actions' => [],
                    ],
                ],
            ],
        ];
    }

    /**
     * @throws HttpException
     */
    public function actionCreate($realm) : string
    {
        $realmObject = Realm::findOne(['uid' => $realm]);
        if($realmObject === null){
            throw new HttpException(400, 'Realm unbekannt');
        }
        $model = new GroupAssertion();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->addFlash('success', 'Zuordnung wurde erfolgreich gespeichert');
        }

        $users = ArrayHelper::map($realmObject->getAssertedUsers()->select(['id', "CONCAT(`username`, ' - ' ,`fullName`) AS name"])->asArray()->all(), 'id', 'name');
        $groups = ArrayHelper::map($realmObject->getGroups()->select(['id', 'name'])->asArray()->all(), 'id', 'name');

        return $this->render('create', ['model' => $model, 'users' => $users, 'groups' => $groups]);
    }
}