<?php

namespace app\models\db;

use yii\db\ActiveQuery;

/**
 * This is the model class for table "gremien".
 *
 * @property int $id
 * @property string $name
 *
 * @property Gremium $parentGremium
 * @property Realm $belongingRealm
 * @property Role[] $roles
 */
class Gremium extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gremium';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'name', 'belongingRealm'], 'required'],
            [['id', 'parentGremium'], 'integer'],
            [['name'], 'string', 'max' => 128],
            [['belongingRealm'], 'string', 'max' => 32],
            [['id'], 'unique'],
            [['parentGremium'], 'exist', 'skipOnError' => true, 'targetClass' => Gremium::className(), 'targetAttribute' => ['parentGremium' => 'id']],
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
            'parentGremium' => 'Parent Gremium',
        ];
    }

    /**
     * Gets query for [[ParentGremium0]].
     *
     * @return ActiveQuery
     */
    public function getParentGremium(): ActiveQuery
    {
        return $this->hasOne(__CLASS__, ['id' => 'parentGremium']);
    }

    /**
     * Gets query for [[Gremiens]].
     *
     * @return ActiveQuery
     */
    public function getChildGremien(): ActiveQuery
    {
        return $this->hasMany(__CLASS__, ['parentGremium' => 'id']);
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

    /**
     * Gets query for [[Roles]].
     *
     * @return ActiveQuery
     */
    public function getRoles()
    {
        return $this->hasMany(Role::class, ['belongingGremium' => 'id']);
    }
}
