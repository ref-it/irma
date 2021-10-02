<?php

namespace app\models\db;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "groups".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $belongingRealm
 *
 * @property Realm $belongingRealm0
 */
class Group extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'string', 'max' => 64],
            [['belongingRealm'], 'string', 'max' => 32],
            [['belongingRealm'], 'exist', 'skipOnError' => true, 'targetClass' => Realm::className(), 'targetAttribute' => ['belongingRealm' => 'uid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'belongingRealm' => 'Belonging Realm',
        ];
    }

    /**
     * Gets query for [[BelongingRealm0]].
     *
     * @return ActiveQuery
     */
    public function getBelongingRealm(): ActiveQuery
    {
        return $this->hasOne(Realm::class, ['uid' => 'belongingRealm']);
    }
}
