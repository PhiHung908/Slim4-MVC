<?php
declare(strict_types=1);

use hSlim\base\domain\DynamicRepository;

use Doctrine\ORM\EntityManager;
use App\config\settings\SettingsInterface;
use DI\ContainerBuilder;


return function (ContainerBuilder &$containerBuilder) {
    $containerBuilder->addDefinitions([
		// Here we map our Repository interface to its in memory implementation if remove "XXX" in alias name XXXdynamicRepository at bellow
		'dynamicRepository' => function($DIContainer, $FactoryDefinition){
			return new DynamicRepository($DIContainer, null, true);
		},
    ]);
};