<?php
/**
 * Migration. Creates "settings" table.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20150715184951 extends AbstractMigration
{
    /**
     * @param Schema $schema
     * @return void
     */
    public function up(Schema $schema)
    {
        $this->createTableSettings($schema);
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema)
    {
        /** Drop "settings" table. */
        $schema->dropTable('settings');
    }

    /**
     * Create "settings" table.
     *
     * @param Schema $schema
     */
    public function createTableSettings(Schema $schema)
    {
        $settings = $schema->createTable('settings');
        $settings->addColumn('id', 'integer', array('autoincrement' => true));
        $settings->addColumn('name', 'string', array('limit' => 128));
        $settings->addColumn('value', 'text', array('notNull' => false));
        $settings->setPrimaryKey(array('id'));
        $settings->addUniqueIndex(array('name'));
    }
}
