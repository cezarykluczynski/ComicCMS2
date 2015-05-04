<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20150503201318 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $images = $schema->createTable('images');
        $images->addColumn('id', 'integer', array('autoincrement' => true));
        $images->addColumn('original_name', 'string', array('limit' => 255));
        $images->addColumn('path', 'string', array('limit' => 255));
        $images->setPrimaryKey(array('id'));
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
         $schema->dropTable('images');
    }
}
