<?php
namespace hSlim\Views\ExtendingHandle\functions;

//use hSlim\Views\AssetHelper;

use Smarty\Exception;

class asset extends \Smarty\FunctionHandler\Base
{
	private $assetSender = [];
	
	public function __construct(private &$c = null, protected &$smarty = null)
	{
		if (empty($c)) return;
		$this->assetSender = &$c->get('viewData')['assetSender'];
	}
	
	public function handle($params = null, ?\Smarty\Template $template = null): string
	{
		$_smarty_tpl = &$this->smarty;
		if (empty($params)) $params = null;
		if (is_string($params)) $params = rtrim(ltrim($params,"'"),"'");
		try {
			if (is_array($params)) {
				foreach($params as $k=>$v) {
					if (is_string($v)) {
						$v = rtrim(ltrim($v,"'"),"'"); 
						$params[$k] = $v;
						if (strpos($v,'array(')!==false) {
							eval('$v = ' . rtrim(ltrim($v, '"'), '"') . ';');
							$params[$k] = $v;
							$v = var_export($v, true);
						}
						if (strpos($v, '$_smarty_tpl') !== false) eval('$params[$k] = ' . $v . ';');
					}
				}
			}
			return $this->c->get('assetHelper')->renderAsset($params[1]??null);
			
		} catch (\ArgumentCountError $e) {
			throw new Exception("Invalid number of arguments to modifier " . $this->modifierName);
		}
	}
}
