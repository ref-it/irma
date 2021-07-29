<?php


namespace app\rbac\items;


/**
 * For more details and usage information on Permission, see the [guide article on security authorization](guide:security-authorization).
 */
class Realm extends Item
{
    /**
     * {@inheritdoc}
     */
    public $type = self::TYPE_REALM;
}