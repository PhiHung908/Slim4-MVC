<?php
namespace hSlim\helper;


use Slim\App;

use RuntimeException;

class HtmlHelper
{
	protected $viewData;
	
	public function __construct(protected &$c, protected $escaper)
    {
		$app = $c->get(App::class);
		$this->viewData = $c->get('viewData') ?? null;
	}
	
	
	public function csrf_esc(array $params = [], $template = null) {
		if (!empty($params) && is_array($params) && (isset($params['csrf']) || isset($params['keys'])&&isset($params['keys']['name']) )) {
			if (isset($params['csrf'])) $csrf = $params['csrf'];
			else $csrf = $params;
		}
		else $csrf = &$this->viewData['csrf'];
		return '<input type="hidden" name="'. $this->escaper->escapeHtmlAttr($csrf['keys']['name']) . '" value="' . $this->escaper->escapeHtmlAttr($csrf['name']) . '">
<input type="hidden" name="' . $this->escaper->escapeHtmlAttr($csrf['keys']['value']) . '" value="' . $this->escaper->escapeHtmlAttr($csrf['value']) . '">
';
	}

	
	//public function eschtml(array $params = [], $template = null) {
	public function esc_x($args, /* \Smarty\Compiler\Template*/ $compiler = null, $parameter = [], $tag = null, $function = null): string
	{
//var_dump($args); die;
		if (is_string($args)) $args = [$args,'html'];
		$esc_type = $args[1] ?? 'html';
		$rs;
		switch ($esc_type) {
			case 'html': 
				$rs = $this->escaper->escapeHtml($args[0]);
				break;
			case 'attr';
			case 'htmlattr';
			case 'htmlAttr';
			case 'HtmlAttr';
				$rs = $this->escaper->escapeHtmlAttr($args[0]);
				break;
			case 'url': 
				$rs = $this->escaper->escapeUrl($args[0]);
				break;
			case 'js': 
				$rs = $this->escaper->escapeJs($args[0]);
				break;
			case 'css': 
				$rs = $this->escaper->escapeCss($args[0]);
				break;
			default:
				$rs = $args[0];
				break;
		}
		return $rs;
	}
}
