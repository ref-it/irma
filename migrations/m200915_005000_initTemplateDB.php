<?php


class m200915_005000_initTemplateDB extends \yii\db\Migration
{
    public function safeUp() : bool
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'name' => $this->string(64),
            'username' => $this->string(32)->notNull(),
            'email' => $this->string(64)->notNull(),
            'status' => $this->tinyInteger()->notNull(),
            //'lastLogin' => $this->timestamp(),
            'authSource' => $this->string(16)->notNull(),
            'password' => $this->string(60)->notNull(),
            'phone' => $this->string(32),
            'iban' => $this->string(50),
            'adresse' => $this->string(256),
            'token' => $this->string(64),
            'authKey' => $this->string(32)->unique()->notNull(),
            'profilePic' => $this->string(32)->unique(),
            //'salt' => $this->string(5),
        ]);

        $this->createIndex('unique_users_username_source', 'user', ['username', 'authSource'], true);

        return true;
    }

    public function safeDown() : bool
    {
        // undo
        $this->dropTable('user');
        return true;
    }
}