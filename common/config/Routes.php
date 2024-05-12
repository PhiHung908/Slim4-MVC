<?php
declare(strict_types=1);

use Psr\Http\Message\{ServerRequestInterface as Request, ResponseInterface as Response};

use Slim\{App, Interfaces\RouteCollectorProxyInterface as Group};

 
return function (&$c) {
	[$app, $psr4PathHelper] = (require_once __DIR__ . "\\..\\..\\vendor\\slim-mvc\\core\\bootstrapRoutes.php")($c);
	 
	/* 
	$app->options('/{routes:.*}', function (Request $request, Response $response) {
		// CORS Pre-Flight OPTIONS Request Handler
		return $response;
	});
	//*/
	
	  
	$app->get('/hello[/{name}]', function (Request $request, Response $response, $args) 
	{
		$response->getBody()->write('Hello world! ' . 
		(isset($args['name']) ? $args['name'] : '') .
		'<br>');
		
$routeContext = \Slim\Routing\RouteContext::fromRequest($request);
$route = $routeContext->getRoute();
$routeParser = $routeContext->getRouteParser();
$url = $routeParser->urlFor($route->getName() ?? 'hello');
$basePath = $routeContext->getBasePath() . '???';
$response->getBody()->write("<a href=''>test basepath</a><br>url : $url <br>basePath: $basePath");

		
		return $response;
	})->setName('hello'); 
};
