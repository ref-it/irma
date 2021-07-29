<?php

use yii\db\Migration;

/**
 * Class m210507_133858_updateRbacSchema
 */
class m210507_133858_updateRbacSchema extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp() : void
    {
        $this->addColumn('auth_assignment', 'valid_from', $this->integer()->notNull());
        $this->addColumn('auth_assignment', 'valid_until', $this->integer());
        $this->addColumn('auth_item', 'valid_from', $this->integer()->notNull());
        $this->addColumn('auth_item', 'valid_until', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() : bool
    {
        $this->dropColumn('auth_assignment', 'valid_until');
        $this->dropColumn('auth_assignment', 'valid_from');
        $this->dropColumn('auth_item', 'valid_until');
        $this->dropColumn('auth_item', 'valid_from');

        return true;
    }
}
