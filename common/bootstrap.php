<?php
// bootstrap.php


use Invoker\CallableResolver as InvokerCallableResolver;

use Doctrine\ORM\EntityManager;
use DI\ContainerBuilder;

use App\Application\Settings\SettingsInterface;


$classLoader = require_once __DIR__ . '/../vendor/autoload.php';
$psr4PathHelper = new hSlim\base\Psr4PathHelper($classLoader);

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

// Set up settings
$settings = require $psr4PathHelper->findFile('/common/config/Settings.php');
$settings($containerBuilder, $psr4PathHelper);

if (false) { // Should be set to true in production
	$containerBuilder->enableCompilation( $psr4PathHelper->aliasToFull('App/var/cache/php-di', true) );
	$containerBuilder->writeProxiesToFile(true, $psr4PathHelper->aliasToFull('App/var/cache/php-di/proxies', true));
}


// Set up dependencies
(require $psr4PathHelper->findFile('/common/config/Dependencies.php'))($containerBuilder);

// Set up repositories
(require $psr4PathHelper->findFile('/common/config/Repositories.php'))($containerBuilder);

// Set up database connect PDO and doctrine-entity
(require $psr4PathHelper->findFile('/common/config/Di-conn.php'))($containerBuilder);

// Build PHP-DI Container instance
$container = $containerBuilder->build();

//hung add: bridge-slim-di
$container->set(CallableResolverInterface::class, new InvokerCallableResolver($container)); //??


