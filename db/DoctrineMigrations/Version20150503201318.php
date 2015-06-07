<?php
/**
 * Migration. Creates table "images".
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20150503201318 extends AbstractMigration
{
    /**
     * @param Schema $schema
     * @return void
     */
    public function up(Schema $schema)
    {
        $this->createTableImages($schema);
    }

    /**
     * Create "images" table.
     *
     * @param Schema $schema
     * @return void
     */
    public function createTableImages(Schema $schema) {
        $images = $schema->createTable('images');
        $images->addColumn('id', 'integer', array('autoincrement' => true));
        $images->addColumn('original_name', 'string', array('limit' => 255));
        $images->addColumn('path', 'string', array('limit' => 255));
        $images->setPrimaryKey(array('id'));
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema)
    {
         $schema->dropTable('images');
    }
}
