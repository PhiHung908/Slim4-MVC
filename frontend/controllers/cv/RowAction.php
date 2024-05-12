<?php
declare(strict_types=1);

namespace App\controllers\cv;

use hSlim\base\domain\domainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;

#[FastRoute('[GET,POST], {id}')]
class RowAction extends \hSlim\base\AbstractAction
{
	/*Comment if use default asset and module. - View note at Product/ListAction
	public function __construct(protected \Psr\Log\LoggerInterface &$logger, protected \Psr\Container\ContainerInterface &$c) {
		parent::__construct($logger, $c, __NAMESPACE__ . '\RowAsset');
	}
	//*/
	
    protected function action(): Response
    {
		if (empty($this->args)) {
			throw new DomainRecordNotFoundException('Request is invalid.');
		}
	
        $rId = (int) $this->resolveArg('id');		
		$viewData['data'] = $this->model->findById($rId);
        $this->logger->info("cv of row id `{$rId}` was viewed.");

		return $this->render('home.php', $viewData);
		
        return $this->respondWithData($viewData['data']);
    }
}

/*
class RowAsset extends \hSlim\base\AbstractAsset
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
*/
