<?php
namespace hSlim\Views\ExtendingHandle\modifierCompiler;


class asset extends \Smarty\Compile\Modifier\Base
{
	private $smartyAssetFunc;
	
	private $assetSender = [];
	
	public function __construct(private &$c = null, protected ?object &$smarty = null)
	{
		if (empty($c)) return;
		$this->smartyAssetFunc = new \hSlim\Views\ExtendingHandle\functions\asset($c, $smarty);
		
	}
	
	public function compile($params, \Smarty\Compiler\Template $template)
	{
		return "'" . $this->smartyAssetFunc->handle($params, null) . "'";
		
		//$params = explode('?#?',str_replace(chr(39),'', implode('?#?', $params))); //fix [""a"", ""b""]
		//return "'" . $this->smartyAssetFunc->handle($params, null) . "';";
		
	}
}
