<?php

namespace app\models\db;

use yii\db\ActiveQuery;

/**
 * This is the model class for table "realm".
 *
 * @property string|null $uid
 * @property string|null $long_name
 *
 * @property Domain[] $domains
 * @property Gremium[] $gremien
 * @property-read ActiveQuery $assertedUsers
 * @property-read ActiveQuery $realmAssertions
 * @property Group[] $groups
 */
class Realm extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'realm';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['uid'], 'string', 'max' => 32],
            [['long_name'], 'string', 'max' => 128],
            [['uid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Identifier',
            'long_name' => 'Vollständiger Name',
        ];
    }

    /**
     * Gets query for [[Domains]].
     *
     * @return ActiveQuery
     */
    public function getDomains()
    {
        return $this->hasMany(Domain::class, ['realmUid' => 'uid']);
    }

    /**
     * Gets query for [[Gremiens]].
     *
     * @return ActiveQuery
     */
    public function getGremien(): ActiveQuery
    {
        return $this->hasMany(Gremium::class, ['belongingRealm' => 'uid']);
    }

    /**
     * Gets query for [[Groups]].
     *
     * @return ActiveQuery
     */
    public function getGroups(): ActiveQuery
    {
        return $this->hasMany(Group::class, ['belongingRealm' => 'uid']);
    }

    /**
     * Gets query for [[RealmAssertions]].
     *
     * @return ActiveQuery
     */
    public function getRealmAssertions(): ActiveQuery
    {
        return $this->hasMany(RealmAssertion::class, ['realm_id' => 'uid']);
    }

    public function getAssertedUsers() : ActiveQuery
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])->via('realmAssertions');
    }

}
