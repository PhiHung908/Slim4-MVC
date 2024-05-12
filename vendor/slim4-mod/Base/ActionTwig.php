<?php

declare(strict_types=1);

namespace hSlim\base;

use App\config\settings\SettingsInterface;

use App\config\models\domain\domainException\DomainRecordNotFoundException;
	
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Psr\Container\ContainerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

use Slim\App;

abstract class ActionTwig extends \hSlim\base\actions\Action
{
	private $modelName;
    private $_isPhpRender = false;
	
	protected array $assetSender = [];
	
	protected ContainerInterface $c;
	//public function __construct(&$logger, protected ContainerInterface &$c)
    public function __construct()
	{
		parent::__construct();
		
		$this->modelName = array_slice(explode('\\',get_called_class()),-2,1)[0];
		if ($this->modelName === 'auto_gen') $this->modelName = array_slice(explode('\\',get_called_class()),-3,1)[0];
		$this->c->set('viewPath', $this->viewPath());
		//$c->get('addTwigMiddleware');
	}
	
	
	protected function getCsrf()
	{
		if (!in_array($this->request->getMethod(), ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'])) {
			return [
				'csrf'   => [
					'keys' => [
						'name'  => 'dmy_csrf_name',
						'value' => 'dmy_csrf_value'
					],
					'name'  => 'dmy_rand_name...',
					'value' => 'dmy_rand_hash...'
				]
			];
		}
		
		// CSRF token name and value
		$csrf = $this->c->get('csrf');		
		
		if (false === $this->request->getAttribute('csrf_status')) {
			die ('Failed CSRF check!'); //TODO: can write set window.current = new body content or redirect to msg_page
		}
		
		$nameKey = $csrf->getTokenNameKey();
		$valueKey = $csrf->getTokenValueKey();
		$name = $this->request->getAttribute($nameKey);
		$value = $this->request->getAttribute($valueKey);
		
		return [
            'csrf'   => [
                'keys' => [
                    'name'  => $nameKey,
                    'value' => $valueKey
                ],
                'name'  => $name,
                'value' => $value
            ]
        ];
	}	
		

	/**
	  * @return string
	  */
	protected function baseDir($append = null): string
	{
		$psr4PathHelper = $this->c->get('psr4PathHelper');
		return dirname($psr4PathHelper->getVendorDir()) . (!empty($append) ? "\\" . $append : '');
	}

	/**
	  * @return string
	  */
	protected function viewPath($modelName = null): string
	{
		$psr4PathHelper = $this->c->get('psr4PathHelper');
		$_modelName = $modelName;
		if (empty($_modelName)) $_modelName = $this->modelName;
		return $psr4PathHelper->aliasToFull("App\\views" . (!empty($_modelName) ? "\\" . $_modelName : ''), true) ;
	}
	
	private function _emptyDir($cacheDir, $prefix = '') {
			$files = glob($cacheDir .'/' . $prefix . '*'); // get all file names
		foreach($files as $file){ // iterate files
		  if(is_file($file)) {
			unlink($file); // delete file
		  }
		}
	}
	
	private function _getView(&$tplViewFile, &$_args, $fetchStr = false)
	{
		foreach ( array_merge(/*['requestUri' => $this->request->getUri()],*/ ['params' => $this->args], ['model' => ['modelName' => $this->modelName]], $this->getCsrf(), ['assetSender' => $this->assetSender])
					as $k => $v) {
			if (is_array($k)) $_args[] = $k;
			else $_args[$k] = $v;
		}
	
		$refArgs = &$_args;
		$this->c->set('viewData', $refArgs);

		$ext = array_slice(explode('.', '.' . $tplViewFile),-1)[0];
		if ($ext === 'tpl'
				/*&& !($fetchStr && strpos($tplViewFile, '*}')!==false)*/) {
			
			//$d = $c->get('Settings')->get('cacheDir') . '\\smarty';
			//if (!is_dir($d)) mkdir($d);

			$this->c->get(App::class)->add('smartyRender'); //add middlewave
		
			$view = $this->c->get('smartyRender');
			//$view->getSmarty()->setCacheDir($d);
			
			//$view->smarty->clearAllCache();
			$view->smarty->clearAllCache($view->smarty::CLEAR_EXPIRED);
			//$view->smarty->clearCache(null, str_replace('. /\\','-',$tplViewFile));
			
			
			//*
			$view->smarty->setCaching($view->smarty::CACHING_LIFETIME_CURRENT); //CACHING_OFF CACHING_LIFETIME_SAVED   CACHING_LIFETIME_SAVED   // //
			$view->smarty->setCacheLifetime(10);
			//$view->smarty->setCompileCheck($view->smarty::COMPILECHECK_OFF);
			//*/
			
			
			return $view;
		} else if ($ext !== 'twig' 
					&& !($fetchStr && strpos($tplViewFile, '<?')===false) ) /* => khong duoc dat ten twig.twig */ {
						
			
			$view = $this->c->get('phpRender');
			
			$this->c->get(App::class)->add($view::class); //middlewave ???
			
			$view->setLayout($_args['layout'] ?? '');
			$this->_isPhpRender = true;
			
			if ($fetchStr) {
				$m = md5($tplViewFile);
				$d = $this->c->get('Settings')->get('cacheDir') . '\\php-render';
				if (!is_dir($d)) mkdir($d);
				$nm =  $d . '\\~' . $m . '.php';
				if (!file_exists($nm)) {
					$this->_emptyDir($d, '~');
					$h = fopen($nm,'w');
					fwrite($h,'<?php
?>
'.$tplViewFile);
					fclose($h);
				}
				$view->setTemplatePath($d);
				$tplViewFile = '~' . $m . '.php';
			}
			//if (isset($_args['data']['layout']) && !empty($_args['data']['layout'])) $view->setLayout($_args['data']['layout']);
			return $view;
		}
		$this->c->get('addTwigMiddleware');
		return $this->c->get('view');
	}
	
	/**
	  * @return Response
	  */
	protected function render($tplViewFile, &$args = []): Response {
		return $this->_getView($tplViewFile, $args)->render($this->response, $tplViewFile, $args);
	}
	
	/**
	  * @return Response
	  */
	protected function fetchFromString($strTpl, &$args = []): Response {		
		$view = $this->_getView($strTpl, $args, true);
		if ($this->_isPhpRender) {
			return $view->render($this->response, $strTpl, $args);
		}
		
		$this->response->getBody()->write( $this->c->get('view')->fetchFromString($strTpl, $args));
		return $this->response;
		
	}
	
	/**
	  * @return Response
	  */
	protected function renderString($strTpl, &$args = []): Response {
		return $this->fetchFromString($strTpl, $args);
	}
}
