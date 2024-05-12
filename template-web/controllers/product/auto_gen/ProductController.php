<?php
declare(strict_types=1);

namespace App\controllers\product\auto_gen;

use App\Application\Actions\mController;


use hSlim\base\domain\domainException\DomainRecordNotFoundException;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Psr\Container\ContainerInterface;

use Slim\App;

#[FastRoute( GET )]
class ProductController extends  \hSlim\base\Controllers
{
	
	protected function Action(): Response
    {
		$this->response->getBody()->write('Body by Default product ControllerAction');
		return $this->response;
	}
	
	protected function TestAction(): Response
	{
		$this->response->getBody()->write('Body by Direct TestAction in product ControllerAction');
		return $this->response;
	}
}
