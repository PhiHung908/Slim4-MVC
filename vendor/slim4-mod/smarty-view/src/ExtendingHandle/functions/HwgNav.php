<?php
namespace hSlim\Views\FunctionHandler;

use Smarty\FunctionHandler\Base;
use Smarty\Template;


class HwgNav extends Base
{
	public function handle($params, Template $template)
	{
		$_output = '';
		//...
		$_output .= $this->output($key, $val, ...);
		return $_output;
	}

	private function output($key, $value, $selected, $id, $class, &$idx)
	{
	}
}
