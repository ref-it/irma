<?php


namespace app\widgets;

use app\models\MixedUserIdentity;
use cetver\LanguageSelector\items\DropDownLanguageItem;
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
        $lang = new DropDownLanguageItem([
            'languages' => [
                'en-US' => '<span class="flag-icon flag-icon-us"></span> English',
                'de-DE' => '<span class="flag-icon flag-icon-de"></span> Deutsch',
            ],
            'options' => ['encode' => false],
        ]);

        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            //'brandImage' => FAS::icon('chevron-left'),
            'options' => [
                'class' => 'navbar-dark bg-dark navbar-expand-md navbar-fixed-top',
            ],
            'renderInnerContainer' => false,
        ]);

        $navItems = Yii::$app->config->app('topMenuStructure');

        /** @var User $user */
        $isLoggedIn = !Yii::$app->user->isGuest;
        /** @var MixedUserIdentity $id */
        $id = Yii::$app->user->getIdentity();
        $username = !is_null($id) ? $id->getUsername() : '';

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav mr-auto'],
            'items' => $navItems,
        ]);

        // align on the right side
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav'],
            'items' => [
                [
                    'label' => FAS::icon('sign-in-alt') . ' Login',
                    'url' => ['site/login'],
                    'visible' => !$isLoggedIn,
                    'encode' => false
                ],
                [
                    'label' => Html::img('/img/dummyPerson.svg', ['height' => 30, 'class' => 'pr-1']) . ' ' . $username . ' ',
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
                            'url' => ['site/logout'],
                            'encode' => false,
                        ],
                    ],
                ],
                $lang->toArray(),

            ],
        ]);

        NavBar::end();
    }
}