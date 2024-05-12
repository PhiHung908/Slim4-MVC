<?php
declare(strict_types=1);

use App\config\settings\SettingsInterface;

use hSlim\base\actions\handlers\HttpErrorHandler;
use hSlim\base\actions\handlers\ShutdownHandler;
use hSlim\base\actions\responseEmitter\ResponseEmitter;

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;

use Slim\App;

use Invoker\CallableResolver as InvokerCallableResolver;
use Slim\Interfaces\CallableResolverInterface;

/* for main Index require
//$classLoader = require __DIR__ . '/../../../vendor/autoload.php';
//$psr4PathHelper = new hSlim\base\Psr4PathHelper($classLoader);

//if (isset($classLoader)) */
	unset($classLoader);

// Start PHP session
session_start();

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();
//khong tac dung $containerBuilder->useAttributes(true);

// Set up settings
(require $psr4PathHelper->findFile('/common/config/Settings.php'))($containerBuilder, $psr4PathHelper);

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
unset($containerBuilder);

// Instantiate the app
//from... AppFactory::setContainer($container);
//from... $app = AppFactory::create();
//* as php-di bridge
$appClass = App::class;
$container->set(CallableResolverInterface::class, new InvokerCallableResolver($container)); //??
$container->set($appClass, AppFactory::createFromContainer($container));

/*embed Yii2
require $psr4PathHelper->findFile('yii/Yii.php',true);
//unset(Yii::$container);
Yii::$container = $container;
//*/


//*/
//$app = \DI\Bridge\Slim\Bridge::create($container);
$callableResolver = $container->get($appClass)->getCallableResolver();

// Register middleware
(require $psr4PathHelper->findFile('/common/config/Middleware.php'))($container->get($appClass));   

// Register Middleware To Be Executed On All Routes
//$container->get($appClass)->add($container->get('csrf'));
$container->get($appClass)->addMiddleware($container->get('csrf'));

// Register routes
(require $psr4PathHelper->findFile('/common/config/Routes.php'))($container);
unset($psr4PathHelper);

/** @var SettingsInterface $settings */
$settings = $container->get(SettingsInterface::class);

$displayErrorDetails = $settings->get('displayErrorDetails');
$logError = $settings->get('logError');
$logErrorDetails = $settings->get('logErrorDetails');
unset($settings);

// Create Request object from globals
$request = ServerRequestCreatorFactory::create()->createServerRequestFromGlobals();

// Create Error Handler
$errorHandler = new HttpErrorHandler($callableResolver, $container->get($appClass)->getResponseFactory());
unset($callableResolver);

// Create Shutdown Handler
register_shutdown_function(new ShutdownHandler($request, $errorHandler, $displayErrorDetails));

// Add Routing Middleware
$container->get($appClass)->addRoutingMiddleware();

// Add Body Parsing Middleware
$container->get($appClass)->addBodyParsingMiddleware();

// Add Error Middleware
$container->get($appClass)->addErrorMiddleware($displayErrorDetails, $logError, $logErrorDetails)->setDefaultErrorHandler($errorHandler);
unset($displayErrorDetails);
unset($logError);
unset($logErrorDetails);
unset($errorHandler);


// Run App & Emit Response
$response = $container->get($appClass)->handle($request);
unset($request);
unset($container);
(new ResponseEmitter())->emit($response);

