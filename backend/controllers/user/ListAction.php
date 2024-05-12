<?php
declare(strict_types=1);

namespace App\controllers\user;

use Hwg\hwgNav;

use hSlim\base\domain\domainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;

#[FastRoute('[GET,POST]')]
class ListAction extends \hSlim\base\AbstractAction
{
	//* Comment if use default asset and module. - View note at Product/ListAction
	public function __construct(protected \Psr\Log\LoggerInterface &$logger, protected \Psr\Container\ContainerInterface &$c) {
		parent::__construct($logger, $c, __NAMESPACE__ . '\ListAsset');
	}
	//*/
	
    protected function action(): Response
    {	
		if (!empty($this->args) && !isset(($this->args ?? [])['Route'])) {
			throw new DomainRecordNotFoundException('Request is invalid.');
		}

		$viewData['data'] = $this->model->findAll();
        $this->logger->info("user list was viewed.");
		
		
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

class ListAsset extends \hSlim\base\AbstractAsset
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
		'js/test2.js',
	];
    
    public $css = [
		'css/test1.css',
	];
}
;