<?php

namespace app\models\db;

/**
 * This is the model class for table "roles".
 *
 * @property int $id
 * @property string|null $name
 * @property int $belongingGremium
 *
 * @property Gremium $belongingGremium0
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'belongingGremium'], 'required'],
            [['id', 'belongingGremium'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['id'], 'unique'],
            [['belongingGremium'], 'exist', 'skipOnError' => true, 'targetClass' => Gremium::className(), 'targetAttribute' => ['belongingGremium' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'belongingGremium' => 'Belonging Gremium',
        ];
    }

    /**
     * Gets query for [[BelongingGremium0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBelongingGremium0()
    {
        return $this->hasOne(Gremium::className(), ['id' => 'belongingGremium']);
    }
}
