<?php

class m131010_223123_user extends DbMigration
{
    public function up()
    {
        $this->createTable('{{user}}', [
            "id" => "pk",
            "username" => "VARCHAR(20)",
            "password" => "VARCHAR(128)",
            "email" => "VARCHAR(128) NOT NULL",
            "create_time" => "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP",
            "last_visit" => "TIMESTAMP",
            "role" => "VARCHAR(20) NOT NULL DEFAULT 'guest'",
            "status" => "TINYINT NOT NULL DEFAULT 0",
            'first_name' => 'VARCHAR(100)',
            'last_name' => 'VARCHAR(100)',
            'gender' => 'VARCHAR(1)',
            'birthday' => 'DATE',
            'activation_code'=>'VARCHAR(128) NULL DEFAULT NULL'
        ]);

        $this->createIndex('user_username', '{{user}}', 'username', true);
        $this->createIndex('user_email', '{{user}}', 'email', true);

        $this->createTable('{{user_oauth}}', [
            'user_id' => 'INT NOT NULL',
            'provider' => 'VARCHAR(45) NOT NULL',
            'identifier' => 'VARCHAR(64) NOT NULL',
            'profile_cache' => 'TEXT',
            'session_data' => 'TEXT',
        ]);

        $this->createIndex('unic_user_id_name', '{{user_oauth}}', 'user_id, provider', true);
        $this->createIndex('oauth_user_id', '{{user_oauth}}', 'user_id');
        if ($this->isMySql()) {
            $this->addPrimaryKey('provider_identifier', '{{user_oauth}}', 'provider, identifier');
        }
        db()->createCommand()->insert('{{user}}', [
            'email'=>'grigach@gmail.com',
            'role'=>'admin',
            'password'=>Bcrypt::hash('1q1q1q'),
        ]);
        db()->createCommand()->insert('{{user}}', [
            'email'=>'evgeniy@avsystems.com.ua',
            'role'=>'admin',
            'password'=>Bcrypt::hash('pr0j3ct0r'),
        ]);


        $this->createTable('{{user_profile}}', [
            'id'=>'pk',
            'user_id'=>'INT NOT NULL',
            'lang'=>'VARCHAR(6) NULL DEFAULT NULL',
            'city'=>'VARCHAR(200) NULL DEFAULT NULL',
            'address'=>'VARCHAR(255) NULL DEFAULT NULL',
            'phone'=>'VARCHAR(100) NULL DEFAULT NULL',
            'subscribe'=>'TINYINT NOT NULL DEFAULT 0',
        ]);
        $this->insert('{{user_profile}}',[
            'user_id'=>'2',
            'lang'=>'ru',
        ]);
        $this->createIndex('up_ui','{{user_profile}}','user_id');
        $this->addForeignKey('ekma_user_profile_ibfk_u','{{user_profile}}','user_id','{{user}}','id','CASCADE','CASCADE');

    }

    public function down()
    {
        $this->dropTable('{{user_profile}}');

        if($this->isMySql())
            $this->dropPrimaryKey('provider_identifier', '{{user_oauth}}');
        $this->dropIndex('unic_user_id_name', '{{user_oauth}}');
        $this->dropIndex('oauth_user_id', '{{user_oauth}}');
        $this->dropTable('{{user_oauth}}');

        $this->dropIndex('user_email', '{{user}}');
        $this->dropIndex('user_username', '{{user}}');
        $this->dropTable('{{user}}');
    }

}