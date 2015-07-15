<?php
/**
 * Migration. Extends "images" table by "canonical_relative_path", "width", and "height" columns.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20150621170619 extends AbstractMigration
{
    /**
     * @param Schema $schema
     * @return void
     */
    public function up(Schema $schema)
    {
        $this->extendTableImages($schema);
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema)
    {
        $this->pruneTableImages($schema);
    }

    /**
     * Add columns "canonical_relative_path", "width", and "height" to "images" table.
     *
     * @param Schema $schema
     * @return void
     */
    public function extendTableImages(Schema $schema)
    {
        $images = $schema->getTable('images');
        /** Those two column were never used and don't require down() equivalents. */
        $images->dropColumn('original_name');
        $images->dropColumn('path');

        $images->addColumn('canonical_relative_path', 'string', array('limit' => 80, 'notNull' => false));
        $images->addColumn('width', 'integer', array('notNull' => false));
        $images->addColumn('height', 'integer', array('notNull' => false));
    }

    /**
     * Remove columns "canonical_relative_path", "width", and "height" from "images" table.
     *
     * @param Schema $schema
     * @return void
     */
    public function pruneTableImages(Schema $schema)
    {
        $images = $schema->getTable('images');
        $images->dropColumn('canonical_relative_path');
        $images->dropColumn('width');
        $images->dropColumn('height');
    }
}
