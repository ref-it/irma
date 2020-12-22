<?php


namespace app\models;


use stmswitcher\Yii2LdapAuth\Model\LdapUser;
use yii\base\BaseObject;
use yii\web\IdentityInterface;

/**
 * Class MixedUserIdentity
 * @package app\models
 *
 */
class MixedUserIdentity extends BaseObject implements IdentityInterface
{
    private const LDAP = LdapUser::class;

    /**
     * returns picked ID Provider
     * @return string
     */
    public static function getIdProvider() : string
    {
        // TODO: implement more ID ways and switch them
        return self::LDAP;
    }

    /**
     * @inheritDoc
     */
    public static function findIdentity($id)
    {
        return (self::getIdProvider())::findIdentity($id);
    }

    /**
     * @inheritDoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return (self::getIdProvider())::findIdentityByAccessToken($token, $type = null);
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return (self::getIdProvider())::getId();
    }

    public function getUsername() : string
    {
        return (self::getIdProvider())::getUsername();
    }

    public function getMail() : string
    {
        return (self::getIdProvider())::getEmail();
    }

    public function getAvatarUrl() : ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getAuthKey()
    {
        return (self::getIdProvider())::getAuthKey();
    }

    /**
     * @inheritDoc
     */
    public function validateAuthKey($authKey)
    {
        return (self::getIdProvider())::validateAuthKey($authKey);
    }
}