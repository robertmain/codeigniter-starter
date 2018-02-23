<?php

use App\Core\Migration;

class Migration_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
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
            'forename' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false
            ],
            'surname' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false
            ]
        ]);
        $this->dbforge->add_key('username');
        $this->dbforge->add_field($this->date_stamps);

        $this->dbforge->create_table('users', true);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dbforge->drop_table('users');
    }
}
