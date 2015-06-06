<?php
/**
 * Migration. Creates tables "comics", "slugs", "strips", and "comics_slugs", and "comics_strips".
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20150502182949 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->createTableComics($schema);
        $this->createTableSlugs($schema);
        $this->createTableStrips($schema);
        $this->createTableComicsSlugs($schema);
        $this->createTableComicsStrips($schema);
    }

    /**
     * Create "comics" table.
     *
     * @param Schema $schema
     * @return void
     */
    public function createTableComics(Schema $schema) {
        $comics = $schema->createTable('comics');
        $comics->addColumn('id', 'integer', array('autoincrement' => true));
        $comics->addColumn('title', 'string', array('limit' => 255));
        $comics->addColumn('tagline', 'string', array('limit' => 255, 'notNull' => false));
        $comics->addColumn('description', 'string', array('limit' => 255, 'notNull' => false));
        $comics->addColumn('logo_id', 'integer', array('notNull' => false));
        $comics->addColumn('slug_id', 'integer', array('notNull' => false));
        $comics->addColumn('role_id', 'integer', array('notNull' => false));
        $comics->setPrimaryKey(array('id'));
    }

    /**
     * Create "slugs" table.
     *
     * @param Schema $schema
     * @return void
     */
    public function createTableSlugs(Schema $schema) {
        /** Create "comic_slugs" table. */
        $comicSlugs = $schema->createTable('slugs');
        $comicSlugs->addColumn('id', 'integer', array('autoincrement' => true));
        $comicSlugs->addColumn('slug', 'string', array('limit' => 255));
        $comicSlugs->addColumn('parent_id', 'integer', array('notNull' => false));
        $comicSlugs->setPrimaryKey(array('id'));
    }

    /**
     * Create "strips" table.
     *
     * @param Schema $schema
     * @return void
     */
    public function createTableStrips(Schema $schema) {
        /** Create "strips" table. */
        $strips = $schema->createTable('strips');
        $strips->addColumn('id', 'integer', array('autoincrement' => true));
        $strips->setPrimaryKey(array('id'));
    }

    /**
     * Create "comics_slugs" table.
     *
     * @param Schema $schema
     * @return void
     */
    public function createTableComicsSlugs(Schema $schema) {
        /** Create "comics_slugs" table. */
        $comicsSlugs = $schema->createTable('comics_slugs');
        $comicsSlugs->addColumn('comic_id', 'integer');
        $comicsSlugs->addColumn('slug_id', 'integer');
        $comicsSlugs->addForeignKeyConstraint('comics', array('comic_id'), array('id'));
        $comicsSlugs->addForeignKeyConstraint('slugs', array('slug_id'), array('id'));
    }

    /**
     * Create "comics_strips" table.
     *
     * @param Schema $schema
     * @return void
     */
    public function createTableComicsStrips(Schema $schema) {
        $comicsSlugs = $schema->createTable('comics_strips');
        $comicsSlugs->addColumn('comic_id', 'integer');
        $comicsSlugs->addColumn('strip_id', 'integer');
        $comicsSlugs->addForeignKeyConstraint('comics', array('comic_id'), array('id'));
        $comicsSlugs->addForeignKeyConstraint('strips', array('strip_id'), array('id'));
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('comics');
        $schema->dropTable('slugs');
        $schema->dropTable('strips');
        $schema->dropTable('comics_slugs');
        $schema->dropTable('comics_strips');
    }
}
