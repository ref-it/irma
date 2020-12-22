<?php




class m200915_005000_initTemplateDB extends \yii\db\Migration
{
    public function safeUp() : bool
    {
        // this is example code
        $this->createTable('config', [
            'module' => $this->string(),
            'name' => $this->string(),
            'value' => $this->json(),
        ]);

        $this->addPrimaryKey('pk_config', 'config', ['module', 'name']);
        $this->initConfigValues();

        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'name' => $this->string(64),
            'username' => $this->string(32),
            'email' => $this->string(64),
            'status' => $this->tinyInteger(),
            'lastLogin' => $this->timestamp(),
            'authSource' => $this->string(16),
            'password' => $this->string(128),
        ]);

        $this->createIndex('unique_users_username_source', 'users', ['username', 'authSource'], true);
        return true;
    }

    public  function initConfigValues() : void {
        $this->batchInsert('config', ['module', 'name'], [
            ['module' => 'app', 'name' => 'name'],
            ['module' => 'app', 'name' => 'colorTheme'],
            ['module' => 'app', 'name' => 'brandLogo'],
            ['module' => 'app', 'name' => 'selectableLanguages'],
            ['module' => 'app', 'name' => 'defaultLanguage'],
            ['module' => 'app', 'name' => 'topMenuStructure'],
            ['module' => 'app', 'name' => 'leftMenuStructure'],
        ]);
    }

    public function safeDown() : bool
    {
        // undo
        $this->dropTable('config');
        $this->dropTable('users');
        return true;
    }
}