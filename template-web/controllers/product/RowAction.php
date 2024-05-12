<?php
declare(strict_types=1);

namespace App\controllers\product;


use hSlim\base\domain\domainException\DomainRecordNotFoundException;

use Psr\Http\Message\ResponseInterface as Response;

#[FastRoute('[GET,POST], {id}')]
class RowAction extends \hSlim\base\AbstractAction
{
    protected function action(): Response
    {


$routeContext = $this->c->get('routeContext');
$route = $routeContext->getRoute();
$routeParser = $routeContext->getRouteParser();
$uriPath = $routeParser->urlFor($route->getName() ?? 'currentRoute');
$basePath = $routeContext->getBasePath() . '???';
$this->response->getBody()->write("<a href=''>test basepath</a><br>$uriPath <br>$basePath");

		
		if (empty($this->args)) {
			throw new DomainRecordNotFoundException('Request is invalid.');
		}
	
        $rId = (int) $this->resolveArg('id');		
		$viewData['data'] = $this->model->findById($rId);
        $this->logger->info("product of row id `{$rId}` was viewed.");

		//if (!isAPI) {
			//$viewData['layout'] = 'layout.tpl'; //default
			//return $this->render('home.tpl', $viewData);
			
			return $this->render('home.php', $viewData);
		//}
		
        return $this->respondWithData($viewData['data']);
    }
}
