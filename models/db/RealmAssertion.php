<?php

namespace app\models\db;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "realm_assertions".
 *
 * @property string|null $user_id
 * @property string|null $realm_id
 *
 * @property-read User $user
 * @property-read Realm $realm
 */
class RealmAssertion extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'realm_assertion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id'], 'integer'],
            [['realm_id'], 'string', 'max' => 32],
            [['realm_id'], 'exist', 'skipOnError' => true, 'targetClass' => Realm::class, 'targetAttribute' => ['realm_id' => 'uid']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'user_id' => 'User ID',
            'realm_id' => 'Realm ID',
        ];
    }

    /**
     * Gets query for [[Realm]].
     *
     * @return ActiveQuery
     */
    public function getRealm(): ActiveQuery
    {
        return $this->hasOne(Realm::class, ['uid' => 'realm_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser() : ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
