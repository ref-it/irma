<?php

use rmrevin\yii\fontawesome\FAS;
use yii\grid\ActionColumn;
use yii\grid\SerialColumn;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel \app\models\db\search\GremiumSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Gremien Auswahl');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gremien-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(FAS::icon('plus') . ' ' . Yii::t('app', 'Erstelle Gremium'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => SerialColumn::class],
            'name',
            'belongingRealm',
            'parentGremium',

            ['class' => ActionColumn::class],
        ],
    ]); ?>


</div>
