<?php
/**
 * Migration. Adds "author" column to "comics" table.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20150607103005 extends AbstractMigration
{
    /**
     * @param Schema $schema
     * @return void
     */
    public function up(Schema $schema)
    {
        $this->extendTableComics($schema);
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema)
    {
        $this->pruneTableComics($schema);
    }

    /**
     * Add column "author" to "comics" table.
     *
     * @param Schema $schema
     * @return void
     */
    public function extendTableComics(Schema $schema)
    {
        $comics = $schema->getTable('comics');
        $comics->addColumn('author', 'string', array('limit' => 255, 'notNull' => false));
    }

    /**
     * Remove column "author" from "comics" table.
     *
     * @param Schema $schema
     * @return void
     */
    public function pruneTableComics(Schema $schema)
    {
        $comics = $schema->getTable('comics');
        $comics->dropColumn('author');
    }
}
