<?php

namespace app\models\db;

use yii\db\ActiveQuery;

/**
 * This is the model class for table "realm_assertions".
 *
 * @property string|null $user_id
 * @property string|null $realm_id
 *
 * @property Realm $realm
 */
class RealmAssertion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'realm_assertion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['realm_id'], 'string', 'max' => 32],
            [['realm_id'], 'exist', 'skipOnError' => true, 'targetClass' => Realm::class, 'targetAttribute' => ['realm_id' => 'uid']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Realm::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'realm_id' => 'Realm ID',
        ];
    }

    /**
     * Gets query for [[Realm]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRealm()
    {
        return $this->hasOne(Realm::class, ['uid' => 'realm_id']);
    }

    public function getUser() : ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
