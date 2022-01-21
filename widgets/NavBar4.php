<?php


namespace app\widgets;

use app\models\db\User;
use rmrevin\yii\fontawesome\FAS;
use Yii;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\helpers\Html;


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


        $user = Yii::$app->user;
        $isLoggedIn = !$user->isGuest;
        /* @var User $id */
        $id = $user->getIdentity();
        $nameTag = $id->fullName ?? $id->username ?? 'Anonymous?!';
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav mr-auto'],
            'items' => [
                [
                    'label' => FAS::icon('sitemap') . ' Gremien',
                    'url' => ['gremien/'],
                    'visible' => $isLoggedIn,
                    'encode' => false,
                ],
                [
                    'label' => FAS::icon('users') . ' Personen',
                    'url' => ['user/'],
                    'visible' => $isLoggedIn,
                    'encode' => false,
                ],
            ]
        ]);
        $roleLabel = 'unset';
        if($isLoggedIn){
            // parse the mayor role of the user
            $adminRealms = $id->adminRealms;
            if(!empty($adminRealms)){
                $adminRealms = array_map(static fn($realm) => $realm->uid, $adminRealms);
                if(in_array('oa', $adminRealms, true)){
                    $roleLabel = FAS::icon('dragon') . ' Superadmin';
                }else{
                    $roleLabel = FAS::icon('user-cog') . ' Admin: ' . implode(', ' , $adminRealms);
                }
            }else{
                $memberRealms = $id->realms;
                if(!empty($memberRealms)){
                    $memberRealms = array_map(static fn($realm) => $realm->uid, $memberRealms);
                    $roleLabel = FAS::icon('user-tag') . ' Member: ' . implode(', ' , $adminRealms);
                }
            }
        }
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
                    'label' => $roleLabel,
                    'visible' => $isLoggedIn,
                    'encode' => false,
                    'active' => true,
                    //'disabled' => true,
                ],
                [
                    'label' => Html::img(Yii::$app->request->baseUrl . '/img/dummyPerson.svg', ['height' => 30, 'class' => 'pr-1']) . ' ' . $nameTag,
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