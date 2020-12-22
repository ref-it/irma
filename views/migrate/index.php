<?php



/* @var $this \yii\web\View */
/* @var $error string  */
/* @var $output string  */
/* @var $ob string  */

use yii\bootstrap4\Accordion;
use yii\bootstrap4\Html;
use yii\helpers\Url;

$this->title = "Migrate DB";
$this->params['breadcrumps'] = ['label' => 'Admin' , 'url' => Url::to('config/index')];

$logs = [];
if(!empty($ob)){
    $logs[] = Html::tag('pre', $ob);
}
if(!empty($error)){
    $logs[] = Html::tag('pre', $error);
}

echo Accordion::widget([
    'items' => [
        // equivalent to the above
        [
            'label' => 'Results',
            'content' => Html::tag('pre', $output),
        ],
        [
            'label' => 'Log',
            'content' => $logs,
            'visible' => !empty($logs),
        ]
    ]
]);