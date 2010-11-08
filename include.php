<?php
/*
 * Dette er en liten sak som brukes for å inkludere alle
 * klassene som kalenderen består av.
*/


use Doctrine\Common\ClassLoader,
Doctrine\ORM\Configuration,
Doctrine\ORM\EntityManager,
Doctrine\Common\Cache\ApcCache,
Entities\User, Entities\Address;

require_once 'vendor/doctrine2/lib/vendor/doctrine-common/lib/Doctrine/Common/ClassLoader.php';

// Set up class loading. You could use different autoloaders, provided by your favorite framework,
// if you want to.
$classLoader = new ClassLoader('Doctrine\ORM', realpath(__DIR__ . '/vendor/doctrine2/lib'));
$classLoader->register();
$classLoader = new ClassLoader('Doctrine\DBAL', realpath(__DIR__ . '/vendor/doctrine2/lib/vendor/doctrine-dbal/lib'));
$classLoader->register();
$classLoader = new ClassLoader('Doctrine\Common', realpath(__DIR__ . '/vendor/doctrine2/lib/vendor/doctrine-common/lib'));
$classLoader->register();
$classLoader = new ClassLoader('Symfony', realpath(__DIR__ . '/vendor/doctrine2/lib/vendor'));
$classLoader->register();
$classLoader = new ClassLoader('Entities', __DIR__);
$classLoader->register();
$classLoader = new ClassLoader('Proxies', __DIR__);
$classLoader->register();
$classLoader = new ClassLoader('Laget',__DIR__.'/lib');
$classLoader->register();

//Disse vil ikke lastes av autoloaderen så jeg inkluderer dem direkte
    require_once 'lib/Laget/Controller/BaseController.php';
    require_once 'lib/Laget/Controller/CalenderViewController.php';
    require_once 'lib/Laget/Controller/CalenderAdminController.php';
    require_once 'lib/Laget/Controller/SpeakerViewController.php';
    require_once 'lib/Laget/Controller/SpeakerAdminController.php';
    require_once 'lib/Laget/Controller/RegistrationController.php';

// Set up caches
$config = new Configuration;
//$cache = new ApcCache;
$cache = new \Doctrine\Common\Cache\ArrayCache();
$config->setMetadataCacheImpl($cache);
$driverImpl = $config->newDefaultAnnotationDriver(array(__DIR__."/Entities"));
$config->setMetadataDriverImpl($driverImpl);
$config->setQueryCacheImpl($cache);

// Proxy configuration
$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);

if(isset($debug)) {
  $logger = new \Laget\Doctrine\DebugStack();
  $config->setSQLLogger($logger);
}
// Database connection information
$connectionOptions = array(
        'dbname' => 'doctrineKalender',
        'user' => 'doctrineKalender',
        'password' => 'uBQtGN22HbBLJuKb',
        'host' => 'localhost',
        'driver' => 'pdo_mysql',
        'charset' => 'UTF8',
        'driverOptions' => array(
             'charset' => 'UTF8'
         )
);
if(!function_exists('__')){
/**
 * oversettelse funksjon Hvis engelsk oversettelse ikke finnes logges stringen
 * slik at den kan vises som trenger oversettelse nederst på siden.
 * @param <type> $string
 * @return <type> $string
 */
function __($string,$trans = array()){
  if(\Laget\Controller\BaseController::$__lang != 'no'){
    if(isset(\Laget\Controller\BaseController::$translations[$string])){
      $string = \Laget\Controller\BaseController::$translations[$string];
    }else{
      \Laget\Controller\BaseController::$notTranslatable[$string] = $string;
    }
  }
  if(empty($trans)){
    return $string;
  }
  return strtr($string,$trans);
}

}

// Create EntityManager
$em = EntityManager::create($connectionOptions, $config);
$em->getEventManager()->addEventSubscriber(new \Doctrine\DBAL\Event\Listeners\MysqlSessionInit('utf8', 'utf8_unicode_ci'));
