<?php
namespace hSlim\Views\ExtendingHandle\modifierTagCompiler;

class asset extends \Smarty\Compile\Base
{
	private $smartyAssetFunc;
	
	private $assetSender = [];
	
	public function __construct(private &$c = null, protected ?object &$smarty = null)
	{
		if (empty($c)) return;
		$this->smartyAssetFunc = new \hSlim\Views\ExtendingHandle\functions\asset($c, $smarty);
		
	}
	
	public function compile($args, \Smarty\Compiler\Template $compiler, $parameter = [], $tag = null, $function = null): string
	{
		return "'" . $this->smartyAssetFunc->handle($args, null) . "'";
		
		/*
		$args = explode('?#?',str_replace(chr(39),'', implode('?#?', $args))); //fix [""a"", ""b""]
		return "<?php echo '" . $this->smartyAssetFunc->handle($args, null) . "';?>";
		*/
	}
}
