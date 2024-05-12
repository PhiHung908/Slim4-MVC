<?php

declare(strict_types=1);

//use App\Application\Actions\wControllers;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;


return function (&$c) {
	$app = $c->get(App::class);
	$psr4PathHelper = $c->get('psr4PathHelper');
		
	/*
	//#[Route("{/routes:.*}")]
	$app->options('/{routes:.*}', function (Request $request, Response $response) {
		// CORS Pre-Flight OPTIONS Request Handler
		return $response;
	});
	*/
	
	/*
	//#[Route("/")]
	$app->group('/', function (Group $group) {
		$group->get('{Route:.*}', wControllers::class);
	});
	//*/
	//*
	
	
	
	(require_once $psr4PathHelper->findFile('hSlim/base/AutoController.php', true))($c);
	
	
	//#[Route("/")]
	$app->get('/hello/{name}', function (Request $request, Response $response, $args) {
		$response->getBody()->write('Hello world! ' . $args['name'] );
		return $response;
	});
	
	
	/*
	$app->group("/product", function (Group $group) {
    $group->map(["GET"], "", "App\controllers\product\auto_gen\ProductController");
    $group->map(["GET"], "/{Route:[^(list/)].*}", "App\controllers\product\auto_gen\ProductController");
    $group->map(["GET", "POST"], "/list", "App\controllers\product\ListAction");
    $group->map(["GET", "POST"], "/list/", "App\controllers\product\ListAction")->setName("currentRoute");
});
	*/
};
