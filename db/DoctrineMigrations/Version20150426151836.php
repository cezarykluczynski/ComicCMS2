<?php
/**
 * Migration. Creates tables "users", "roles", and "user_role_linker".
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

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
        $this->createTableUsers($schema);
        $this->createTableRoles($schema);
        $this->createTableUserRoleLinker($schema);
    }

    /**
     * Create "users" table.
     *
     * @param Schema $schema
     * @return void
     */
    public function createTableUsers(Schema $schema)
    {
        $users = $schema->createTable('users');
        $users->addColumn('id', 'integer', array('autoincrement' => true));
        $users->addColumn('email', 'string', array('limit' => 255));
        $users->addColumn('password', 'string', array('limit' => 60));
        $users->setPrimaryKey(array('id'));
        $users->addUniqueIndex(array('email'));
    }

    /**
     * Create "roles" table.
     *
     * @param Schema $schema
     * @return void
     */
    public function createTableRoles(Schema $schema)
    {
        $roles = $schema->createTable('roles');
        $roles->addColumn('id', 'integer', array('autoincrement' => true));
        $roles->addColumn('role_id', 'string', array('length'=> 255));
        $roles->addColumn('is_default', 'smallint', array('null'=> false));
        $roles->addColumn('parent_id', 'string', array('length'=> 255, 'notNull' => false));
        $roles->setPrimaryKey(array('id'));
    }

    /**
     * Create "user_role_linker" table.
     *
     * @param Schema $schema
     * @return void
     */
    public function createTableUserRoleLinker(Schema $schema)
    {
        $usersRoles = $schema->createTable('user_role_linker');
        $usersRoles->addColumn('user_id', 'integer');
        $usersRoles->addColumn('role_id', 'integer');
        $usersRoles->addForeignKeyConstraint('users', array('user_id'), array('id'), array(
            'onUpdate' => 'CASCADE',
            'onDelete' => 'CASCADE'
        ));
        $usersRoles->addForeignKeyConstraint('roles', array('role_id'), array('id'), array(
            'onUpdate' => 'CASCADE',
            'onDelete' => 'CASCADE'
        ));
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('users');
        $schema->dropTable('roles');
        $schema->dropTable('user_role_linker');
    }
}
