<?php


namespace app\rbac;


class Assignment extends \yii\rbac\Assignment
{
    /**
     * @var ?int UNIX timestamp representing till when the Assignment is valid, if null there is no end date
     */
    public ?int $validUntil;

    /**
     * @var int UNIX timestamp representing from where on the Assignment is valid
     */
    public int $validFrom;


    public function isValid() : bool
    {
        $now = time();
        if($this->validUntil === null){
            return ($now - $this->validFrom) > 0;
        }
        return ($now - $this->validFrom) > 0 && ($this->validUntil - $now) > 0;
    }

}