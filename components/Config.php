<?php


namespace app\components;


use app\models\db\ConfigRecord;
use DirectoryIterator;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class Config
 * @method app($name)
 * @package app\components
 */
class Config extends \yii\base\Component
{
    /**
     * @var array
     */
    public $_mapping = [

    ];

    /**
     * @var array
     */
    public $_config = [];

    public function init() : void
    {
        $res = ConfigRecord::find()->all();
        $config = [];
        foreach ($res as $row){
            /* @var ConfigRecord $row */
            $config[$row->module][$row->name] = json_decode($row->value, true);
        }
        if(isset($config['app']['language'])){
            Yii::$app->language = $config['app']['language'];
            unset($config['app']['language']);
        }
        if(isset($config['app']['name'])){
            Yii::$app->name = $config['app']['name'][Yii::$app->language];
            unset($config['app']['name']);
        }
        $this->_config = $config;
    }

    public function getSupportedLanguages() : array
    {
        $translations = [];
        $dirs = new DirectoryIterator(Yii::getAlias('@locale'));
        foreach($dirs as $dir) {
            if($dir->isDir() && !$dir->isDot()) {
                $translations[$dir->getFilename()] = $dir->getFilename();
            }
        }
        return $translations;
    }

    public function __call($name, $params)
    {
        if(ArrayHelper::keyExists($name, $this->_config)){
            $attributeName = $params[0];
            return $this->_config[$name][$attributeName] ?? [];
        }

        return parent::__call($name, $params);
    }

}