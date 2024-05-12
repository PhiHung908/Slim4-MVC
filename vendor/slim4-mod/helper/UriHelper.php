<?php
namespace hSlim\helper;


use Slim\App;

use Slim\Routing\Dispatcher;

use Laminas\Escaper\Escaper;
use Psr\Container\ContainerInterface;

use Slim\Interfaces\RouteCollectorInterface;
use Slim\Interfaces\RouteParserInterface;
use Psr\Http\Message\UriInterface;
use RuntimeException;

class emuUri
{
	private $fastRoute;
	
	public function __construct($routeCollector, private $currentRoute)
	{
		$this->fastRoute = new Dispatcher($routeCollector);
	}
	public function getPath()
	{
		$pth = $this->currentRoute->getPattern();
		if (strpos($pth, '[') !== false) $pth = dirname(substr($pth, 0, strpos($pth, '[')+1));
		if (strpos($pth, '{') !== false) $pth = dirname(substr($pth, 0, strpos($pth, '{')+1));
		return $this->fastRoute->dispatch('GET', $pth)->getUri();
	}
}

class UriHelper
{
	protected $viewData;
	
	protected RouteCollectorInterface $routeCollector;
	protected RouteParserInterface $routeParser;
	
	
	protected $currUriPath;
	
	public function __construct(protected ?ContainerInterface &$c = null, protected ?Escaper &$escaper = null) 
	{
		$app = $this->c->get(App::class);
		
		$this->routeCollector = $app->getRouteCollector();
		$this->routeParser = $this->routeCollector->getRouteParser();
		
		$this->currUriPath = (new emuUri($this->routeCollector, $this->routeCollector->getNamedRoute('currentRoute')))->getPath();
	}
	
	
	public function urlFor(array $params, $template = null): string
    {
		if (!isset($params['name'])) $params['name'] = 'currentRoute';
		$rs = $this->routeParser->urlFor($params['name'], $params['data'] ?? [], $params['queryParams'] ?? []);
		if (strpos($rs, '?')!==false) {
			$a = explode('?',$rs);
			$a[1] = implode('&',array_slice($a,1));
			return $a[0] . '?' . $this->escaper->escapeUrl($a[1]);
		}
		return $rs;
    }

	public function fullUrlFor(array $params, $template = null): string
    {
		if (!isset($params['name'])) $params['name'] = 'currentRoute';
        $rs = $this->routeParser->fullUrlFor($this->uri, $params['name'], $params['data'] ?? [], $params['queryParams'] ?? []);
		if (strpos($rs, '?')!==false) {
			$a = explode('?',$rs);
			$a[1] = implode('&',array_slice($a,1));
			return $a[0] . '?' . $this->escaper->escapeUrl($a[1]);
		}
		return $rs;
    }
	
	private function _hasHttpDotDot($params)
	{
		return strpos($params['data'], '://') !== false && strpos($params['data'], '://') <= 6;
	}
	
	private function isRoot(&$params)
	{ 
		return substr($params['data'],0,1) == '/' || $this->_hasHttpDotDot($params);
	}
	
	private function _verifyCurrUri(&$params) {
		if ($params['data'] === './' || $params['data'] === '.' || empty($params['data'])) {
			$params['data'] = $this->currUriPath;
		}
	}
	
	private function _verifyParams(&$params)
	{
		$this->_verifyCurrUri($params);
		
		if (!is_string($params['data'])) return; 
		
		if (!isset($params['queryParams'])) $params['queryParams'] = $params[1]['queryParams'] ?? $params[1] ?? [];

		if (str_contains($params['data'],'?')) {
			$a = explode('?', $params['data']);
			$av = array_values(explode('&',$a[1]));
			$az = [];
			foreach ($av as $akv) {$x = explode('=', $akv); $az[$x[0]] = $x[1];}
			$params['queryParams'] = array_merge($az, $params['queryParams']);
			$params['data'] = $a[0];
		}
		if (!$this->isRoot($params)) {
			$uriPath = $this->currUriPath;
			$params['data'] = dirname($uriPath) . '/' . $params['data']; 
		}
	}
	
	private function _checkRouteName(&$params)
	{
		try {
			if (empty($params) || is_string($params) || is_array($params) && isset($params[0]) && is_string($params[0])) {
				$params = [is_string($params) ? $params : $params[0]];
			}
		} catch(RuntimeException $e) {
			$params = [is_string($params) ? $params : $params[0]];
		}
		if(count($params)==1 && is_array($params[0])) $params = $params[0];
		
		if (!isset($params['name']) && !isset($params['data']) ) {
			$params['name'] = md5(var_export($params, true));
			$methods = $this->routeCollector->getNamedRoute('currentRoute')->getMethods();
			try {
				$params['data'] = $params[0] ?? $params['url'] ?? null;
				$this->_verifyParams($params);
				$r = $this->routeCollector->getNamedRoute($params['name']);
			} catch(RuntimeException $e) {
				$this->c->get(App::class)->map($methods, $params['data'], function($request, $response, array $args){
					//$response->getBody()->write("Hello, " . $args['name']);
					return $response;
				})->setName($params['name']);
			}
			$params['data'] = [$params['data']];
		} else $this->_verifyCurrUri($params);
	}

	public function urlTo($params, $template = null)
	{
		$this->_checkRouteName($params);
		return $this->urlFor($params, $template);
	}

	
	public function fullUrlTo($params, $template = null)
	{
		$this->_checkRouteName($params);
		if ($this->_hasHttpDotDot(['data' => $params['data'][0] ?? ''])) return $this->urlFor($params, $template);
		return $this->fullUrlFor($params, $template);
	}
	
	public function urlGo($params, $template = null)
	{
		return $this->urlTo($params, $template);
	}
	
	public function redirect($params, $template = null)
	{
		return $this->urlTo($params, $template);
	}
	
	public function uriPath()
	{
		return $this->currUriPath;
	}
	
	public function routePattern()
	{
		return $this->routeCollector->getNamedRoute('currentRoute')->getPattern();
	}
}
