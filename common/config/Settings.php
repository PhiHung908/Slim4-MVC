<?php

declare(strict_types=1);

use App\config\settings\Settings;
use App\config\settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;


//define('APP_ROOT', __DIR__ . "\\..\\..");



return function (ContainerBuilder &$containerBuilder, &$psr4PathHelper) {
    // Global Settings Object
	$psr4PathHelper->prependPrs4Alias(__DIR__, $containerBuilder);
	
    $containerBuilder->addDefinitions([
		'useYii' => function($c, $x) {
			if ($c->get('hasUseYii')) return;
			$psr4PathHelper = $c->get('psr4PathHelper');
			require $psr4PathHelper->findFile('yii/Yii.php');
			$psr4PathHelper->prependPrs4Alias('', null, Yii::$classMap, false);
			//Yii::$container = $c;
			$c->set('hasUseYii', true);
		},
		'hasUseYii' => false,
        SettingsInterface::class => function ($DIContainer, $DIfactoryDefinition) {
			$psr4PathHelper = $DIContainer->get('psr4PathHelper');
			$APP_DIR = $psr4PathHelper->aliasToFull("App\\", true);
			$db_default = [
						'driver' => 'mysql',
						'host' => 'localhost',
						'port' => 3306,
						'dbname' => 'slim_db', //'yii2advanced', 
						'user' => 'root',
						'password' => 'root',
						'charset' => 'utf8mb4',
						'collation' => 'utf8mb4_unicode_ci',
						'flags' => [
							// Turn off persistent connections
							\PDO::ATTR_PERSISTENT => false,
							// Enable exceptions
							\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
							// Emulate prepared statements
							\PDO::ATTR_EMULATE_PREPARES => true,
							// Set default fetch mode to array
							\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
						]
					];
					
			$db_default_doctrine = $db_default;
			$db_default_doctrine['driver'] = 'pdo_mysql';
			
			$aValue = [
				'defaultRootModel' => 'product',
				'assetsRoot' => 'WwwRoot', //nếu không set key này hoặc khác với alias WwwRoot thì sẽ cache asset đến thư mục 
										   //của module (ex admin/product/web/asset - 1. chậm vì không thể browser-cache; 2. các depends sẽ nằm ở từng module-private-dir -> không tập trung, tốn dung lượng)
                'displayErrorDetails' => true, // Should be set to false in production
                'logError'            => false,
                'logErrorDetails'     => false,
                'logger' => [
                    'name' => 'slim-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : $APP_DIR . "var\\logs\\app.log",
                    'level' => Logger::DEBUG,
                ],
				'tempDir' => $APP_DIR . "var\\temp",
				'cacheDir' => $APP_DIR . "var\\cache",
				'twigCache' => $APP_DIR . "var\\cache\\twig",
				'components' => [
					'Hwg' => [
						'class' => "Hwg\\app",
						'admin' => "Hwg\\app\\admin",
					],
				],
				'smarty' => [
					'templateDir' 	=> $APP_DIR . "var\\cache\\smarty\\views",
					'cacheDir' 		=> $APP_DIR . "var\\cache\\smarty\\cache",
					'configDir' 	=> $APP_DIR . "var\\cache\\smarty\\config",
					'compileDir' 	=> $APP_DIR . "var\\cache\\smarty\\compile",
					
				],
				'dbdefault' => 'doctrine',
				'db' => $db_default,
				'doctrine' => [
					// Enables or disables Doctrine metadata caching
					// for either performance or convenience during development.
					'dev_mode' => true,

					// Path where Doctrine will cache the processed metadata
					// when 'dev_mode' is false.
					'cache_dir' => $APP_DIR . "var\\cache\\doctrine",

					// List of paths where Doctrine will search for metadata.
					// Metadata can be either YML/XML files or PHP classes annotated
					// with comments or PHP8 attributes.
					'metadata_dirs' => [$APP_DIR . "models"],

					// The parameters Doctrine needs to connect to your database.
					// These parameters depend on the driver (for instance the 'pdo_sqlite' driver
					// needs a 'path' parameter and doesn't use most of the ones shown in this example).
					// Refer to the Doctrine documentation to see the full list
					// of valid parameters: https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html
					'connection' => $db_default_doctrine
				],
            ];
			
            return new Settings($aValue, $psr4PathHelper, $containerBuilder);
        }
    ]);
};
