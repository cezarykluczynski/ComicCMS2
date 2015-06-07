<?php
/**
 * Migration. Extends table "strips". Creates table "strips_images".
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20150522210521 extends AbstractMigration
{
    /**
     * @param Schema $schema
     * @return void
     */
    public function up(Schema $schema)
    {
        $this->extendTableStrips($schema);
        $this->createTableStripsImages($schema);
    }

    /**
     * Extend "strips" table.
     *
     * @param Schema $schema
     * @return void
     */
    public function extendTableStrips(Schema $schema)
    {
        $strips = $schema->getTable('strips');
        $strips->addColumn('title', 'string', array('limit' => 255, 'notNull' => false));
        $strips->addColumn('caption', 'text', array('notNull' => false));
    }

    /**
     * Create "strips_images" table.
     *
     * @param Schema $schema
     * @return void
     */
    function createTableStripsImages(Schema $schema) {
        /** Create "strips_images" table. */
        $comicsSlugs = $schema->createTable('strips_images');
        $comicsSlugs->addColumn('strip_id', 'integer');
        $comicsSlugs->addColumn('image_id', 'integer');
        $comicsSlugs->addForeignKeyConstraint('strips', array('strip_id'), array('id'));
        $comicsSlugs->addForeignKeyConstraint('images', array('image_id'), array('id'));
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema)
    {
        $this->pruneTableStrips($schema);
        $schema->dropTable('strips_images');
    }

    /**
     * Prune "strips" table.
     *
     * @param Schema $schema
     * @return void
     */
    public function pruneTableStrips(Schema $schema)
    {
        $strips = $schema->getTable('strips');
        $strips->dropColumn('title');
        $strips->dropColumn('caption');
    }

}
