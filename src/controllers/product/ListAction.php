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
