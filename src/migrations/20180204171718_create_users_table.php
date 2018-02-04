<?php

use Core\Migration;

class Migration_create_users_table extends Migration
{
    public function up()
    {
        $this->dbforge->add_field('id');
        $this->dbforge->add_field([
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false
            ]
        ]);
        $this->dbforge->add_key('username');
        $this->dbforge->add_field($this->metaData);

        $this->dbforge->create_table('users', true);
    }

    public function down()
    {
        $this->dbforge->drop_table('users');
    }
}
