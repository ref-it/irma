<?php

namespace app\models\db;

/**
 * This is the model class for table "realm_admin".
 *
 * @property int $user_id
 * @property string $realm_id
 *
 * @property Realm $realm
 * @property User $user
 */
class RealmAdmin extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'realm_admin';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'realm_id'], 'required'],
            [['user_id'], 'integer'],
            [['realm_id'], 'string', 'max' => 16],
            [['user_id', 'realm_id'], 'unique', 'targetAttribute' => ['user_id', 'realm_id']],
            [['realm_id'], 'exist', 'skipOnError' => true, 'targetClass' => Realm::class, 'targetAttribute' => ['realm_id' => 'uid']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
        return $this->hasOne(Realm::className(), ['uid' => 'realm_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
