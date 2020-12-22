<?php

/* @var $this \yii\web\View */

use app\widgets\CardLayout;

echo CardLayout::widget([
    'containerOptions' => ['class' => [CardLayout::CARD_DECK]],
    'defaultCardConfig' => [
        // 'containerOptions' => ['class' => ['col-lg-3']],
        'header' => Faker\Provider\de_DE\Person::firstNameFemale(),
        'headerOptions' => ['class' => []],
        'body' =>  \Faker\Provider\Person::firstNameMale(),
    ],
    'items' => [
        [],
        [],
        [],
        [],
        [],
        [],
        [],
    ],
]);

