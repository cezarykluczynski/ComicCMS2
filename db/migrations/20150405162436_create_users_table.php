<?php

use Phinx\Migration\AbstractMigration;

class CreateUsersTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $users = $this->table('users');
        $users
            ->addColumn('email', 'string', array('limit' => 255))
            ->addColumn('password', 'string', array('limit' => 60))
            ->addIndex(array('email'), array('unique' => true))
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}