<?php
declare(strict_types=1);

use App\config\settings\SettingsInterface;
use DI\Factory\RequestedEntry;
//use DI\Invoker\FactoryParameterResolver;
use DI\Compiler\RequestedEntryHolder;



use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use DI\ContainerBuilder;

use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
 

return function (ContainerBuilder &$containerBuilder) {
	$containerBuilder->addDefinitions([
        EntityManager::class => function (ContainerInterface $c): EntityManager {
			/** @var array $settings */
			$settings = $c->get(SettingsInterface::class)->get();

			$config = ORMSetup::createAttributeMetadataConfiguration(
				$settings['doctrine']['metadata_dirs'],
				$settings['doctrine']['dev_mode'],
				// $proxyDir
				null,
				// CacheItemPoolInterface
				$settings['doctrine']['dev_mode'] ?
					new ArrayAdapter() :
					new FilesystemAdapter(directory: $settings['doctrine']['cache_dir'])
			);

			$connection = DriverManager::getConnection($settings['doctrine']['connection']);
			
			//$c->set('entityManager', new EntityManager($connection, $config));
			return new EntityManager($connection, $config);
		},
		PDO::class => function (ContainerInterface $c) {

            $settings = $c->get(SettingsInterface::class);

            $dbSettings = $settings->get('db');

			$driver = $dbSettings['driver'];
            $host = $dbSettings['host'];
			$port = $dbSettings['port'];
            $dbname = $dbSettings['dbname'];
            $username = $dbSettings['user'];
            $password = $dbSettings['password'];
            $charset = $dbSettings['charset'];
            $flags = $dbSettings['flags'];
            $dsn = "$driver:host=$host;port=$port;dbname=$dbname;charset=$charset";
            return new PDO($dsn, $username, $password);
        }
	]);
};
