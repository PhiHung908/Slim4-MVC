<?php
namespace hSlim\Views;


use Psr\Http\Message\UriInterface;
use RuntimeException;
use FastRoute;

use Smarty\Template;
use Smarty\Extension\Base;


class Smarty5Extension extends Base
{
	protected $hFuncClassPath = __NAMESPACE__ .'\\ExtendingHandle\\';
	
	protected $smarty;
	
	protected $viewData;
	
    private $modifiers = [];

	private $functionHandlers = [];

	private $blockHandlers = [];
	
	protected $uriHelper;
	
	protected $escaper;
	
	protected $htmlHelper;
	
	public function __construct(&$smarty5ViewSender, protected &$c)
    {
		$this->smarty = &$smarty5ViewSender->smarty; 
	}
	
	
	private function _mkClassPath(&$functionName, $type) {
		switch ($type) {
			case $this->smarty::PLUGIN_MODIFIERCOMPILER : $_type = 'modifierCompiler'; break;
			case $this->smarty::PLUGIN_FUNCTION : $_type = 'functions'; break;
			case $this->smarty::PLUGIN_COMPILER /*'m_tag'*/ : $_type = 'modifierTagCompiler'; break;
			case $this->smarty::PLUGIN_MODIFIER : $_type = 'modifierCallback'; break;
			case $this->smarty::PLUGIN_BLOCK : $_type = 'blockHandler'; break;
			case $this->smarty::FILTER_POST : $_type = 'filterPost'; break;
			case $this->smarty::FILTER_PRE : $_type = 'filterPre'; break;
			case $this->smarty::FILTER_OUTPUT : $_type = 'filterOutput'; break;
			case $this->smarty::FILTER_VARIABLE : $_type = 'filterVar'; break;
			default: $_type = 'otherExtensions';
		}
		return $this->hFuncClassPath.$_type.'\\'.$functionName;
	}
	

	private function _retHandle($functionName, $type, &$arrConstructorParams = null) {
		if (!isset($this->escaper)) {
			$this->escaper = $this->c->get('laminasEscaper');
		}
		$cls = $this->_mkClassPath($functionName, $type);
		
		if (!empty($this->functionHandlers[$functionName])) {
			return $this->functionHandlers[$functionName];
		}
		
		$this->functionHandlers[$functionName] = empty($arrConstructorParams) ? new $cls($this->c, $this->smarty) : new $cls(array_slice(func_get_args(),2));
		return $this->functionHandlers[$functionName] ?? null;
	}
	
	
	//tham so la array... ex: {urlTo(['data' => ['id' => 'hung?x=5'], 'queryParams' => ['k' => 'aaa', 'z' => 'vvv'] ])}
    public function getModifierCompiler(string $modifier, &$arrConstructorParams = null) : ?\Smarty\Compile\Modifier\ModifierCompilerInterface
	{
		if (!isset($this->escaper)) {
			$this->escaper = $this->c->get('laminasEscaper');
		}
		switch ($modifier) {
			case 'urlTo' :
			case 'fullUrlTo' :
			case 'urlFor' : 
			case 'fullUrlFor' :
			case 'urlGo' :
			case 'redirect' :
				{
					if (!isset($this->uriHelper)) {
						$this->uriHelper = $this->c->get('uriHelper');
					}
					return new \hSlim\Views\ExtendingHandle\modifierCompiler\cbWarp($modifier, $this->smarty, $this->uriHelper, $this->c);
					break;
				}
			case 'csrfEscXXX' :
			case 'esc_x' :
				{
					if (!isset($this->htmlHelper)) {
						$this->htmlHelper = $this->c->get('htmlHelper');
					}
					return new \hSlim\Views\ExtendingHandle\modifierCompiler\cbWarp($modifier, $this->smarty, $this->htmlHelper, $this->c, true);
					break;
				}
			default: 
				break;
		}
		return $this->_retHandle($modifier, $this->smarty::PLUGIN_MODIFIERCOMPILER, $arrConstructorParams);
    }
	
	//ex: {asset('','.*')}
	public function getFunctionHandler(string $functionName, &$arrConstructorParams = null): ?\Smarty\FunctionHandler\FunctionHandlerInterface
	{
		return $this->_retHandle($functionName, $this->smarty::PLUGIN_FUNCTION, $arrConstructorParams);
	}

	//ex: {asset}, {asset '' '.js'}
	public function getTagCompiler(string $tag): ?\Smarty\Compile\CompilerInterface {
		if (!isset($this->escaper)) {
			$this->escaper = $this->c->get('laminasEscaper');
		}
		switch ($tag) {
			case 'esc_x' :
			case 'csrf_esc' :
				{
					if (!isset($this->htmlHelper)) {
						$this->htmlHelper = $this->c->get('htmlHelper');
					}
					return new \hSlim\Views\ExtendingHandle\modifierTagCompiler\cbWarp($tag, $this->smarty, $this->htmlHelper, $this->c);
					break;
				}
			default:
				break;
		}
		return $this->_retHandle($tag, $this->smarty::PLUGIN_COMPILER); // 'm_tag'
	}

	public function getBlockHandler(string $blockTagName): ?\Smarty\BlockHandler\BlockHandlerInterface {
		return $this->_retHandle($blockTagName, $this->smarty::PLUGIN_BLOCK);
	}

	public function getModifierCallback(string $modifierName) {
		return $this->_retHandle($modifierName, $this->smarty::PLUGIN_MODIFIER);
	}
	
	/*
	public function getPreFilters(): array {
		return $this->_retHandle('', 'm_filters');
	}

	public function getPostFilters(): array {
		return $this->_retHandle('', 'm_filters');
	}

	public function getOutputFilters(): array {
		return $this->_retHandle('', 'm_filters');
	}
	*/
	public function registerPlugin($type, $name, $cacheable = true, $arrConstructorParams = null) {
		switch ($type) {
			case $this->smarty::PLUGIN_MODIFIERCOMPILER: return $this->getModifierCompiler($name, $arrConstructorParams); break;
			case $this->smarty::PLUGIN_FUNCTION: return $this->getFunctionHandler($name, $arrConstructorParams); break;
			case $this->smarty::PLUGIN_COMPILER: return $this->getTagCompiler($name, $arrConstructorParams); break;
			case $this->smarty::PLUGIN_MODIFIER: return $this->getModifierCallback($name, $arrConstructorParams); break;
			case $this->smarty::PLUGIN_BLOCK: return $this->getBlockHandler($name, $arrConstructorParams); break;
			/*
			case $this->smarty::FILTER_POST: return $this->getPostFilters(); break;
			case $this->smarty::FILTER_PRE: return $this->getPreFilters(); break;
			case $this->smarty::FILTER_OUTPUT: return getOutputFilters(); break;
			//case $this->smarty::FILTER_VARIABLE = 'variable'; break;
			*/
			default: return null;
		}
	}
}
