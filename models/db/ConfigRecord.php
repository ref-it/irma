<?php

namespace app\models\db;

use romi45\yii2jsonvalidator\JsonValidator;
use yii\db\ActiveRecord;

/**
 * This is the model class
 * @property string $module [varchar(255)]
 * @property string $name
 * @property string $value
 *
 */
class ConfigRecord extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName() : string
    {
        return 'config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() : array
    {
        return [
            [['name', 'category', 'value'], 'required'],
            [['name', 'category'], 'string', 'max' => 255],
            ['value', JsonValidator::class ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() : array
    {
        return [
            'name' => 'Name',
            'value' => 'Wert',
        ];
    }

}
