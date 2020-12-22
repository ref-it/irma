<?php

use app\models\config\AdminConfigModel;
use yii\bootstrap4\ActiveForm;
use yii\web\View;
use kdn\yii2\JsonEditor;

/* @var $this View */
/* @var $model AdminConfigModel */
/* @var $allLang array */
?>

<h1><?= Yii::t('app', 'Configuration')  ?></h1>

<?php $form = ActiveForm::begin() ?>
    <?= $form->field($model,'name')->textInput() ?>
    <?= $form->field($model, 'defaultLanguage')->dropDownList($allLang) ?>
    <?= $form->field($model, 'selectableLanguages')->checkboxList($allLang, ['options' => []]) ?>
    <?php // $form->field($model, 'brandLogo')->fileInput() ?>
    <?= $form->field($model, 'topMenuStructure')->widget(JsonEditor::class, [
        // JSON editor options
        'clientOptions' => [
            'modes' => ['code', 'form', 'preview', 'text', 'tree', 'view'], // all available modes
            'mode' => 'tree', // default mode
            'onModeChange' => 'function (newMode, oldMode) {
                console.log(this, newMode, oldMode);
            }',
            //'schema' => '[]',
        ],
        'collapseAll' => ['view'], // collapse all fields in "view" mode
        'containerOptions' => ['class' => 'container'], // HTML options for JSON editor container tag
        'expandAll' => ['tree', 'form'], // expand all fields in "tree" and "form" modes
        'name' => 'menuStructureTop', // hidden input name
        'options' => ['id' => 'data'], // HTML options for hidden input
        'value' => $model->leftMenuStructure, // JSON which should be shown in editor

    ]) ?>
    <?= $form->field($model, 'brandLogo')->widget(\dosamigos\fileinput\BootstrapFileInput::class, []) ?>

<?php ActiveForm::end() ?>
