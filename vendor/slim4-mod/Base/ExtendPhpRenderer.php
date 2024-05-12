<?php
declare(strict_types=1);

namespace hSlim\base;

use Slim\Views\PhpRenderer; 

class ExtendPhpRenderer extends PhpRenderer
{
	private $assetHelper;
		
	//protected $uriHelper;
	
	//protected $escaper;
	
	//protected $htmlHelper;
	
	public function __construct(string $templatePath = '', array $attributes = [], string $layout = '', protected &$c = null)
    {
		parent::__construct($templatePath, $attributes, $layout);
	}
	
	
	public function __call($func, $args) {
		switch ($func) {
			case 'esc_x' :
				{
					/*if (!isset($this->escaper)) {
						$this->escaper = $this->c->get('laminasEscaper'); //->make('laminasEscaper', ['encoding' => $args['encoding'] ?? null]);
					}*/
					if (empty($args)) return null;
					switch ($args[1] ?? null) {
						case 'html';
						case 'url';
						case 'js';
						case 'css';
							return call_user_func([$this->c->get('laminasEscaper'), 'escape' . ucfirst($args[1])],$args[0]);
						case 'attr';
						case 'htmlattr';
						case 'htmlAttr';
						case 'HtmlAttr';
							return call_user_func([$this->c->get('laminasEscaper'), 'escapeHtmlAttr'],$args[0]);
						default:
							return $args[0];
					}
				}
			case 'urlTo';
			case 'fullUrlTo';
			case 'urlFor';
			case 'fullUrlFor';
			case 'redirect';
			case 'urlGo';
				{
					/*if (!isset($this->escaper)) {
						$this->escaper = $this->c->get('laminasEscaper');
					}*/
					if (empty($args) || is_string($args[0])) $args[0] = [$args[0] ?? null];
					/*if (!isset($this->uriHelper)) {
						$this->uriHelper = $this->c->get('uriHelper');
					}*/
//return $this->c->get('uriHelper')->routePattern();
//var_dump($func,111); die;
					return $this->c->get('uriHelper')->$func($args[0]);
					//return call_user_func_array([$this->uriHelper,$func],$args);
				}
			case 'csrf_esc':
				{
					/*if (!isset($this->escaper)) {
						$this->escaper = $this->c->get('laminasEscaper');
					}*/
					if (empty($args) || is_string($args[0])) $args[0] = [$args[0] ?? null];
					/*if (!isset($this->htmlHelper)) {
						$this->htmlHelper = $this->c->get('htmlHelper');
					}*/
					return $this->c->get('htmlHelper')->$func($args[0]);
				}
			default: 
				break;
				
		}
		return null;
	}
	
	
	public function asset($path = '', $packageName = ''): string
	{
		/*if (!isset($this->assetHelper)) {
			$this->viewData = $this->c->get('viewData');
			$this->assetHelper = new AssetHelper($this->viewData);
		}*/
		/*
		if (!isset($this->viewData)) {
			$this->viewData = $this->c->get('viewData');
		}*/
		return $this->c->get('assetHelper')->renderAsset($packageName);
	}
}
