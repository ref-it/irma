<?php



/* @var $this View */
/* @var $identity MixedUserIdentity */

use app\models\MixedUserIdentity;
use yii\web\View;


var_dump($identity);

echo \yii\bootstrap4\Html::ul([
    $identity->getId(),
    $identity->getUsername(),
    // $identity->getMail(),
    // $identity->getAvatarUrl()
]);


