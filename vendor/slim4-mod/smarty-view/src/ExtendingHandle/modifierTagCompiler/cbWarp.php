<?php
namespace hSlim\Views\ExtendingHandle\modifierTagCompiler;

use Smarty\Exception;

class cbWarp extends \Smarty\Compile\Base
{
	private $extSender;
	
	
	public function __construct(private string $modifierName, private &$smarty, &$extSender, private &$c = null) {
		if (is_string($extSender) && !isset($this->extSender)) {
			$this->extSender = new $extSender($this->smarty, $this->c);
		} else {
			$this->extSender = $extSender;
		}
	}


	public function compile($args, \Smarty\Compiler\Template $compiler, $parameter = [], $tag = null, $function = null): string
	{
		$_smarty_tpl = &$this->smarty;
		if (empty($args)) $args = null;
		try {
			if (is_string($args)) $args = rtrim(ltrim($args,"'"),"'");
						
			if (is_array($args)) {
				foreach($args as $k=>$v) {
					if (is_string($v)) {			
						$v = rtrim(ltrim($v,"'"),"'"); 
						$args[$k] = $v;
						if (strpos($v,'array(')!==false) {
							eval('$v = ' . rtrim(ltrim($v, '"'), '"') . ';');
							$args[$k] = $v;
							$v = var_export($v, true);
						}
						if (strpos($v, '$_smarty_tpl') !== false) eval('$args[$k] = ' . $v . ';');
					}
				}
			}
			
			//return "'" . call_user_func_array([$this->extSender, $this->modifierName], [$args]) . "'";
			//*
			return "<?php echo '" . $this->extSender->{$this->modifierName}($args) . "'; ?>";
			//*/
		} catch (\ArgumentCountError $e) {
			throw new Exception("Invalid number of arguments to modifier " . $this->modifierName);
		}
	}

}
