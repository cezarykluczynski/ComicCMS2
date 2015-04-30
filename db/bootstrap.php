<?php
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
    'user'     => 'comiccms',
    'password' => '',
);
// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);