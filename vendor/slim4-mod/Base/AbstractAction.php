<?php
declare(strict_types=1);

namespace hSlim\base;

use hSlim\base\domain\DynamicRepository;

use Psr\Log\LoggerInterface;
use Psr\Container\ContainerInterface;

abstract class AbstractAction extends ActionTwig
{
	protected $model; //# is type App\Domain\Dynamic as Product|User|...\Repository;
	
	public function __construct(protected LoggerInterface &$logger, protected ContainerInterface &$c)
    {
		$callClass = get_called_class();
		if (!$c->has('dynamicRepository')) $dyn = new DynamicRepository($c, $callClass);
		else $dyn = $c->get('dynamicRepository');
		
		$this->model = &$dyn->model;
		
		//parent::__construct($logger, $c);
		parent::__construct();
		//$asset = new ("\\" . $dyn->modelClass . "\\" . $dyn->modelName . "\\auto_gen\\Asset")($c);
		$asset = new (dirname($callClass). (strrpos($callClass, "\\auto_gen\\")===false ? "\\auto_gen" : "") . "\\Asset")($c);
		$this->assetSender = $asset->registerTwig();
		//unset($dyn);$dyn = null;
		//unset($asset); $asset = null;
		
		//your code...
	}
}
