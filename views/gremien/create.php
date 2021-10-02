<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\db\Gremium */

$this->title = Yii::t('app', 'Create Gremien');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Gremiens'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gremien-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
