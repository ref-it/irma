<?php

namespace app\models\db;

use yii\db\ActiveQuery;

/**
 * This is the model class for table "roles".
 *
 * @property int $id
 * @property string|null $name
 * @property int $belongingGremium
 *
 * @property User[] $assertedUsers
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
            'belongingGremium' => 'Belonging Gremium',
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
        return $this->hasOne(RoleAssertion::class, ['role_id' => 'id']);
    }

    public function getAssertedUsers() : ActiveQuery
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])->via('roleAssertions');
    }
}
