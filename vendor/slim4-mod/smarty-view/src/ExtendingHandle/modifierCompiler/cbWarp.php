<?php
namespace hSlim\Views\ExtendingHandle\modifierCompiler;


use Smarty\Exception;

class cbWarp extends \Smarty\Compile\Modifier\Base
{
	private $extSender;
	
	
	public function __construct(private string $modifierName, private &$smarty, &$extSender, private &$c = null, private $isModifierTag = false) {
		if (is_string($extSender) && !isset($this->extSender)) {
			$this->extSender = new $extSender($this->smarty, $this->c);
		} else {
			$this->extSender = $extSender;
		}
	}

	public function compile($params, \Smarty\Compiler\Template $template) {
		$_smarty_tpl = &$this->smarty;
//var_dump($params);
//die;
		if (empty($params)) $params = null;
		try {
			//if (!empty($params[0]) && strpos($params[0],'array(')!==false) eval('$params = ' . rtrim(ltrim($params[0], '"'), '"') . ';');
			//if (is_string($params) && strpos($params,'array(')!==false) eval('$params = ' . rtrim(ltrim($params, '"'), '"') . ';');
			
			
			//if (is_string($params))$params = str_replace(chr(39).'"', chr(39), str_replace('"'.chr(39), chr(39), $params)); 
			
			if (is_string($params)) $params = rtrim(ltrim($params,"'"),"'");
						
			if (is_array($params)) {
				foreach($params as $k=>$v) {
					if (is_string($v)) {
						//$v = str_replace(chr(39).'"', chr(39), str_replace('"'.chr(39), chr(39), $v)); 
						//$params[$k] = $v;
						
						$v = rtrim(ltrim($v,"'"),"'"); 
						$params[$k] = $v;
						
						//*
						if (strpos($v,'array(')!==false) {
							//$params[$k] = null;
							eval('$v = ' . rtrim(ltrim($v, '"'), '"') . ';');
							$params[$k] = $v;
							$v = var_export($v, true);
						}
						//*/
						if (strpos($v, '$_smarty_tpl') !== false) eval('$params[$k] = ' . $v . ';');
					}
				}
			}
			//return "'" . call_user_func_array([$this->extSender, $this->modifierName], [$params]) . "'";
			//*
			//if ($this->isModifierTag) return $this->extSender->{$this->modifierName}($params);
			return "'" . $this->extSender->{$this->modifierName}($params) . "'";
			//*/
		} catch (\ArgumentCountError $e) {
			throw new Exception("Invalid number of arguments to modifier " . $this->modifierName);
		}
	}

}
