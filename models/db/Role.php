<?php

namespace app\models\db;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "roles".
 *
 * @property int $id
 * @property string|null $name
 * @property Gremium $belongingGremium
 *
 * @property-read ActiveQuery $roleAssertions
 * @property User[] $assertedUsers
 */
class Role extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName() : string
    {
        return 'role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() : array
    {
        return [
            [['belongingGremium'], 'required'],
            [['id', 'belongingGremium'], 'integer'],
            [['name'], 'string', 'max' => 64, 'min' => 2],
            [['id'], 'unique'],
            [['belongingGremium'], 'exist', 'skipOnError' => true, 'targetClass' => Gremium::class, 'targetAttribute' => ['belongingGremium' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() : array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'belongingGremium' => 'Zugehöriges Gremium',
        ];
    }

    /**
     * Gets query for [[BelongingGremium0]].
     *
     * @return ActiveQuery
     */
    public function getBelongingGremium() : ActiveQuery
    {
        return $this->hasOne(Gremium::class, ['id' => 'belongingGremium']);
    }

    public function getRoleAssertions() : ActiveQuery
    {
        return $this->hasMany(RoleAssertion::class, ['role_id' => 'id']);
    }

    public function getAssertedUsers() : ActiveQuery
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])->via('roleAssertions');
    }
}
