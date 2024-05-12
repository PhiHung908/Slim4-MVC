<?php
declare(strict_types=1);

namespace hSlim\base;

use App\Application\Actions\mController;

/*
use Psr\Log\LoggerInterface;
use Psr\Container\ContainerInterface;
*/

use hSlim\base\domain\domainException\DomainRecordNotFoundException;

use Psr\Http\Message\ResponseInterface as Response;


/**
 * neu khong vao tham so se tim func theo thu tu Index IndexAction IndexAction.php Action
*/
abstract class Controllers extends \hSlim\base\AbstractAction
{
	
/*
	public function __construct(protected LoggerInterface &$logger, protected ContainerInterface &$c) {
	//$c->set('xyz',3);	
		parent::__construct($logger, $c);
//var_dump($c->get('xyz'));die;
		//
	}
//*/	

	public function controller(): Response
    {
		$detectCls = \Closure::bind(function ($class, &$fName, &$cls, $callClass) {
			$psr4PathHelper = $this->c->get('psr4PathHelper');
			if ($fName = $psr4PathHelper->findFile($callClass.'\\..\\'.$class.'.php',true)) {
				$cls = dirname($callClass)."\\".$class;
			} else if ($fName = $psr4PathHelper->findFile($callClass.'\\..\\..\\'.$class.'.php',true)) {
				$cls = dirname(dirname($callClass))."\\".$class;
			}
		}, $this, $this);
		
		$a = [];
		$_args = $this->args;
		if (isset($this->args['Route'])) {
			$a = explode('/', rtrim($this->args['Route'],'/'));
			$_args = array_slice($a,1);
			$this->args = $_args;
		}
		$callClass = get_called_class();
		if (!empty($a[0])) {
			$class = ucfirst($a[0]) . 'Action';
			if (method_exists(get_called_class(), $class)) {
				return get_called_class()::$class();
			} else {
				$cls = false;
				$fName = false;
				$detectCls($class, $fName, $cls, $callClass);
				if ($cls) {
					require_once $fName;
					return (new $cls($this->logger, $this->c))->__invoke($this->request, $this->response, $_args);
				}
			} 
			throw new DomainRecordNotFoundException('Request is invalid.');
		}
		if (method_exists($callClass, 'Index')) {
			return $callClass::Index();
		}
		$class = 'IndexAction';
		if (method_exists($callClass, $class)) {
			return $callClass::$class();
		}
		if (strrpos($callClass,'Controller')>strrpos($callClass,'\\')) {
			$cls = false;
			$fName = false;
			$detectCls($class, $fName, $cls, $callClass);
			if ($cls) {
				require_once $fName;
				return (new $cls($this->logger, $this->c))->__invoke($this->request, $this->response, $_args);
			}
		}
		return $this->action();
	}
}
