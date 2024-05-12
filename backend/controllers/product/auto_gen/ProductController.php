<?php
declare(strict_types=1);

namespace App\controllers\product\auto_gen;

use App\Application\Actions\mController;


use hSlim\base\domain\domainException\DomainRecordNotFoundException;

use Psr\Http\Message\ResponseInterface as Response;

#[FastRoute( GET )]
class ProductController extends \hSlim\base\Controllers
{
	/*index() uu tien 1, ../IndexAction.php uu tien 2, action() uu tien 3, co the viet truc  tiep cac action (ex: TestAction) vao file nay hoac tung action vao thu muc ../
	protected function Index(): Response
    {
		$this->response->getBody()->write('Index... u tien 2 doi voi filexxxController');
		return $this->response;
	}
	*/
	
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
