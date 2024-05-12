<?php
declare(strict_types=1);

namespace App\controllers\#TPL_PRODUCT#\auto_gen;

use App\Application\Actions\mController;


use hSlim\base\domain\domainException\DomainRecordNotFoundException;

use Psr\Http\Message\ResponseInterface as Response;

#[FastRoute( GET )]
class #U_TPL_PRODUCT#Controller extends \hSlim\base\Controllers
{
	
	protected function Action(): Response
    {
		$this->response->getBody()->write('Body by Default #TPL_PRODUCT# ControllerAction');
		return $this->response;
	}
	
	protected function TestAction(): Response
	{
		$this->response->getBody()->write('Body by Direct TestAction in #TPL_PRODUCT# ControllerAction');
		return $this->response;
	}
}
