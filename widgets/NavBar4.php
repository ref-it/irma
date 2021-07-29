<?php


namespace app\widgets;

use app\models\db\ServiceUser;
use rmrevin\yii\fontawesome\FAS;
use Yii;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\helpers\Html;
use yii\web\User;


/**
 * Class NavBar4
 * @package app\widgets
 */
class NavBar4 extends \yii\bootstrap4\Widget
{
    public function run()
    {
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            //'brandImage' => FAS::icon('chevron-left'),
            'options' => [
                'class' => 'navbar-dark bg-dark navbar-expand-md navbar-fixed-top',
            ],
            'renderInnerContainer' => false,
        ]);


        /** @var User $user */
        $isLoggedIn = !Yii::$app->user->isGuest;
        /** @var ServiceUser $id */
        $id = Yii::$app->user->getIdentity();
        $nameTag = $id->name ?? $id->username ?? '';

        // align on the right side with ml-auto
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav ml-auto'],
            'items' => [
                [
                    'label' => FAS::icon('sign-in-alt') . ' Login',
                    'url' => ['auth/wayfinder'],
                    'visible' => !$isLoggedIn,
                    'encode' => false,
                ],
                [
                    'label' => FAS::icon('user-plus') . ' Registrieren',
                    'url' => ['auth/register'],
                    'visible' => !$isLoggedIn,
                    'encode' => false,
                ],
                [
                    'label' => Html::img('/img/dummyPerson.svg', ['height' => 30, 'class' => 'pr-1']) . ' ' . $nameTag,
                    'visible' => $isLoggedIn,
                    'dropdownOptions' => ['class' => 'dropdown-menu-right'],
                    'encode' => false,
                    'items' => [
                        [
                            'label' => FAS::icon('user-circle') . ' ' . Yii::t('user', 'action_showProfile_label'),
                            'url' => ['site/profile'],
                            'encode' => false,
                        ],
                        [
                            'label' => FAS::icon('sign-out-alt') . ' ' . Yii::t('user', 'action_logout_label'),
                            'url' => ['auth/logout'],
                            'encode' => false,
                        ],
                    ],
                ],
            ],
        ]);

        NavBar::end();
    }
}