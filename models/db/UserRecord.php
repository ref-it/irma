<?php


namespace app\models\db;


use app\models\User\AppUser;

class UserRecord extends \yii\db\ActiveRecord
{
public static function tableName()
{
    return 'user';
}