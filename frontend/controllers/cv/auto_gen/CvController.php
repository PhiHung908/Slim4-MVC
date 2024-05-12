<?php
declare(strict_types=1);

namespace App\controllers\cv\auto_gen;

use App\Application\Actions\mController;

use hSlim\base\domain\domainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;

#[FastRoute( GET )]
class CvController extends \hSlim\base\Controllers
{
	/**
	  * @var $this->model, $this->model->emRepository,  $this->model->em
	  */
	  
	/*View note at Product/ListAction
	public function __construct(protected \Psr\Log\LoggerInterface &$logger, protected \Psr\Container\ContainerInterface &$c) {
		parent::__construct($logger, $c, __NAMESPACE__ . '\CvAsset');
	}
	//*/
	
	protected function Action(): Response
    {
		$this->response->getBody()->write('Body by Default cv ControllerAction');
		return $this->response;
	}
	
	protected function TestAction(): Response
	{
		$this->response->getBody()->write('Body by Direct TestAction in cv ControllerAction');
		return $this->response;
	}
}

class CvAsset extends \hSlim\base\AbstractAsset
{
	public function __construct(&$c)
    {
		parent::__construct($c, false, true);
	}
	
	public $sourcePath = __DIR__ . "\\auto_gen\\assets";
	
    public $depends = [
		'hSlim\assets\BootstrapAsset',
		'hSlim\assets\JqueryAsset',
	];
	
    public $js = [
	];
    
    public $css = [
	];
}