<?php


namespace app\rbac\items;


class Item extends \yii\rbac\Item
{
    /**
     * @var int the type of the item. This should be either [[TYPE_ROLE]], [[TYPE_PERMISSION]] or [[TYPE_GREMIUM]].
     */
    public $type;

    public const TYPE_ROLE = 1;
    public const TYPE_PERMISSION = 2;
    public const TYPE_GREMIUM = 3;
    public const TYPE_REALM = 4;

    /**
     * @var int UNIX timestamp representing till when the item is valid, if null then there is no end date
     */
    public $validUntil;

    /**
     * @var int UNIX timestamp representing from where on the item is valid
     */
    public $validFrom;

    public function isValid() : bool
    {
        $now = time();
        if($this->validUntil === null){
            return ($now - $this->validFrom) > 0;
        }
        return ($now - $this->validFrom) > 0 && ($this->validUntil - $now) > 0;
    }
}