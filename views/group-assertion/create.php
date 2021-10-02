<?php

/* @var $this \yii\web\View */
/* @var $model \app\models\db\GroupAssertion */
/* @var $users array */
/* @var $groups array */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$form = ActiveForm::begin();

echo $form->field($model, 'user_id')->dropdownList($users);
echo $form->field($model, 'group_id')->dropdownList($groups);

echo Html::submitButton('Speichern', ['class' =>['btn', 'btn-primary']]);

ActiveForm::end();