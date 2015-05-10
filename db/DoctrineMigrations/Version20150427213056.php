<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20150427213056 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        /** Insert roles to DB. The reason for a separate migration is that the
         *  $this->addSql() should bot be mixed with $schema methods. */
        $this->addSql("INSERT INTO roles (role_id, is_default, parent_id) VALUES ('guest', 1, NULL)");
        $this->addSql("INSERT INTO roles (role_id, is_default, parent_id) VALUES ('user', 0, 'guest')");
        $this->addSql("INSERT INTO roles (role_id, is_default, parent_id) VALUES ('admin', 0, 'user')");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM roles WHERE role_id='guest'");
        $this->addSql("DELETE FROM roles WHERE role_id='user'");
        $this->addSql("DELETE FROM roles WHERE role_id='admin'");
    }
}
