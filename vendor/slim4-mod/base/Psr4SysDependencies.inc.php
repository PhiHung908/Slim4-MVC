<?php
declare(strict_types=1);


use hSlim\base\domain\DynamicRepository;


use hSlim\helper\UriHelper;
use hSlim\helper\AssetHelper;
use hSlim\helper\HtmlHelper;

use Laminas\Escaper\Escaper;

use hSlim\base\ExtendPhpRenderer;
use hSlim\Views\Smarty5slim;
use hSlim\Views\Smarty5Extension;

use Smarty\Smarty;

//** change for lazy load
//use Slim\Views\Twig;
//use Slim\Views\TwigMiddleware;
use hSlim\slimTwigView\src\Twig;
use hSlim\slimTwigView\src\TwigMiddleware;

//use Glazilla\TwigAsset\TwigAssetManagement;
use hSlim\base\ExtendTwigAssetMngr;
//**/


use Slim\App;

use Slim\Csrf\Guard;

use App\models\user\AllUserRepository;

use Doctrine\ORM\EntityManager;

use App\config\settings\SettingsInterface;

use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder &$containerBuilder, &$psr4PathHelper) {
	$containerBuilder->addDefinitions([
		'psr4PathHelper' => &$psr4PathHelper,
		'Settings' => function($c) {
			return $c->get(SettingsInterface::class);
			/*$settings = $c->get(SettingsInterface::class);
			$settings->get('assetsRoot') ?? $settings->set(['assetsRoot' => 'WwwRoot']);
			return $settings;*/
		},
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
		'smarty' => function($c) {
			$psr4PathHelper = $c->get('psr4PathHelper');
			require_once  $psr4PathHelper->aliasToFull('hSlim\lazy-load\composer-smarty-smarty\vendor\autoload.php', true);
			$cfg = $c->get(SettingsInterface::class)->get('smarty');
			$renderer = new Smarty($c->get('viewPath'), $cfg);
			return $renderer;
		},
		'smartyRender' => function($c) {
			$psr4PathHelper = $c->get('psr4PathHelper');
			require_once  $psr4PathHelper->aliasToFull('hSlim\lazy-load\composer-smarty-smarty\vendor\autoload.php', true);
			$cfg = $c->get(SettingsInterface::class)->get('smarty');
			$renderer = new Smarty5slim($c->get('viewPath'), $cfg, $c);
			$ext = $renderer->getSmarty()->addExtension(new Smarty5Extension($renderer, $c));
			$renderer->_setExtManager($ext);
			return $renderer;
		},
		'phpRender' => function($c) {
			$renderer = new ExtendPhpRenderer($c->get('viewPath'), c: $c);
			return $renderer;
		},
		'view' => function($c) {
			//lazy load if use twig
			$psr4PathHelper = $c->get('psr4PathHelper');
			require_once  $psr4PathHelper->aliasToFull('hSlim\lazy-load\composer-symfony-asset\vendor\autoload.php', true);
			require_once  $psr4PathHelper->aliasToFull('hSlim\lazy-load\composer-twig-twig\vendor\autoload.php', true);
			
			$view = Twig::create($c->get('viewPath'), ['cache' => $c->get('twigCache')]);
			
			$assetManager = new ExtendTwigAssetMngr([
				'verion' => '1'
			], $c);

			$view->addExtension($assetManager->getAssetExtension());
			return $view;
		},
		'addTwigMiddleware' => function($c) {
			$c->get(App::class)->add(TwigMiddleware::createFromContainer($c->get(App::class)));
		},
		//Register Middleware On Container
		'csrf' => function ($c) {
			$app = $c->get(App::class);
			$responseFactory = $app->getResponseFactory();
			$guard = new Guard($responseFactory);//, prefix: md5(chr(26).md5($app->getRouteCollector()->getBasePath().'csrf'.date("Y-m-d H:i:s"))) );
			$guard->setFailureHandler(function ($request, $handler) {
				$request = $request->withAttribute("csrf_status", false);
				return $handler->handle($request);
			});
			return $guard;
		},
		'obj.cache' => [],
		'laminasEscaper' => function($c) {
			$rs = &$c->get('obj.cache')['laminasEscaper'] ?? null;
			if (!empty($rs)) return $$rs;
			$rs = new Escaper(); //?string $encoding = null
			$c->set('obj.cache',array_merge($c->get('obj.cache'),['laminasEscaper' => &$rs]));
			return $rs; //$c->get('obj.cache')['laminasEscaper'];
		},
		'uriHelper' => function($c) {
			$rs = &$c->get('obj.cache')['uriHelper'] ?? null;
			if (!empty($rs)) return $$rs;
			$rs = $c->get('laminasEscaper');
			$uri = new UriHelper($c, $rs);
			$c->set('obj.cache',array_merge($c->get('obj.cache'),['uriHelper' => &$uri]));
			return $uri; //$c->get('obj.cache')['uriHelper'];
		},
		'assetHelper' => function($c) {
			$rs = &$c->get('obj.cache')['assetHelper'] ?? null;
			if (!empty($rs)) return $$rs;
			$rs = new AssetHelper($c);
			$c->set('obj.cache',array_merge($c->get('obj.cache'),['assetHelper' => &$rs]));
			return $rs; //$c->get('obj.cache')['assetHelper'];
		},
		'htmlHelper' => function($c) {
			$rs = &$c->get('obj.cache')['htmlHelper'] ?? null;
			if (!empty($rs)) return $$rs;
			$rs = new HtmlHelper($c, $c->get('laminasEscaper') ?? null);
			$c->set('obj.cache',array_merge($c->get('obj.cache'),['htmlHelper' => &$rs]));
			return $rs; //$c->get('obj.cache')['htmlHelper'];
		},
		'obj.cache.repository' => [],
		'userRepository' => function($c) {
			$rs = &$c->get('obj.cache.repository')['userRepository'] ?? null;
			if (!empty($rs)) return $$rs;
			$rs = new DynamicRepository($c, 'App\models\user\User');
			$c->set('obj.cache.repository',array_merge($c->get('obj.cache.repository'),['userRepository' => &$rs]));
			return $rs;
		},
	]);
};
