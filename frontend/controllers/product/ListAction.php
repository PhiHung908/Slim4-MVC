<?php
declare(strict_types=1);

namespace App\controllers\product;

use Hwg\hwgNav;


use hSlim\base\domain\domainException\DomainRecordNotFoundException;

use Psr\Http\Message\ResponseInterface as Response;

#[FastRoute('[GET,POST]')]
class ListAction extends \hSlim\base\AbstractAction
{
    protected function action(): Response
    {
		
$routeContext = $this->c->get('routeContext');
$route = $routeContext->getRoute();
$routeParser = $routeContext->getRouteParser();
$uriPath = $routeParser->urlFor($route->getName() ?? 'currentRoute');
$basePath = $routeContext->getBasePath() . '???';
$this->response->getBody()->write("<a href=''>test basepath</a><br>$uriPath <br>$basePath<br>" . print_r($this->args,true));
//return $this->response;

		if (!empty($this->args) && !isset(($this->args ?? [])['Route'])) {
			throw new DomainRecordNotFoundException('Request is invalid.');
		}
		
		$viewData['data'] = $this->model->findAll();
        $this->logger->info("product list was viewed.");
		
		
		//if (!isAPI) {
			$viewData['layout'] = 'layout.php';
			return $this->render('home.php', $viewData);
			
			//return $this->render('Home.tpl', $viewData);
			//return $this->render('home.twig', $viewData);
			//return $this->fetchFromString('<h3>Ten model: <?=$model["modelName"]? ></h3>', $viewData);
			//return $this->fetchFromString('<h3>Ten model: {{ model.modelName }}</h3>', $viewData);
		//}
		
        return $this->respondWithData($viewData['data']);
    }
}
