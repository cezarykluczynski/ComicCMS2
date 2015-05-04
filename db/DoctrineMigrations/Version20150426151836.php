<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20150426151836 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        /** Create "users" table. */
        $users = $schema->createTable('users');
        $users->addColumn('id', 'integer', array('autoincrement' => true));
        $users->addColumn('email', 'string', array('limit' => 255));
        $users->addColumn('password', 'string', array('limit' => 60));
        $users->setPrimaryKey(array('id'));
        $users->addUniqueIndex(array('email'));

        /** Create "roles" table. */
        $roles = $schema->createTable('roles');
        $roles->addColumn('id', 'integer', array('autoincrement' => true));
        $roles->addColumn('role_id', 'string', array('length'=> 255));
        $roles->addColumn('is_default', 'smallint', array('null'=> false));
        $roles->addColumn('parent_id', 'string', array('length'=> 255, 'notNull' => false));
        $roles->setPrimaryKey(array('id'));

        /** Create "users_roles" table. */
        $usersRoles = $schema->createTable('user_role_linker');
        $usersRoles->addColumn('user_id', 'integer');
        $usersRoles->addColumn('role_id', 'integer');
        $usersRoles->addForeignKeyConstraint('users', array('user_id'), array('id'));
        $usersRoles->addForeignKeyConstraint('roles', array('role_id'), array('id'));
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('users');
        $schema->dropTable('roles');
        $schema->dropTable('user_role_linker');
    }
}
