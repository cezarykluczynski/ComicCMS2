<?php
/**
 * Migrations bootstrap.
 *
 * @todo Move database config to single file.
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

use Doctrine\ORM\Tools\Setup;
use \Doctrine\ORM\EntityManager;

require_once __DIR__."/../vendor/autoload.php";
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src"), $isDevMode);
// database configuration parameters
$conn = array(
    'driver' => 'pdo_pgsql',
    'path' => __DIR__ . '/db.pgsql',
    'host'     => 'localhost',
    'port'     => '5432',
    'dbname'   => 'comiccms',
    'user'     => getenv('TRAVIS_POSTGRES_USER') ?: 'comiccms',
    'password' => '',
);
// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);