<?php

namespace app\models\db;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "domains".
 *
 * @property string|null $name
 * @property int|null $activeMail
 * @property string|null $belongingRealm
 * @property int|null $forRegistration
 * @property int $id [int(11)]
 *
 */

class Domain extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'domain';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'string', 'max' => 128],
            [['name'], 'unique'],
            [['activeMail', 'forRegistration'], 'boolean'],
            [['belongingRealm'], 'string', 'max' => 32],
            [['belongingRealm'], 'exist', 'skipOnError' => true, 'targetClass' => Realm::class, 'targetAttribute' => ['belongingRealm' => 'uid']],
            [['forRegistration'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'Name',
            'activeMail' => 'Active Mail',
            'belongingRealm' => 'Belonging Realm',
            'forRegistration' => 'For Registration',
        ];
    }

    /**
     * Gets query for [[BelongingRealm0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBelongingRealm()
    {
        return $this->hasOne(Realm::class, ['uid' => 'belongingRealm']);
    }
}
