<?php
declare(strict_types=1);

namespace App\controllers\user\auto_gen;

class ListAsset extends \hSlim\base\AbstractAsset
{
	public function __construct(&$c)
    {
		parent::__construct($c, false, true);
	}
	
	public $sourcePath = __DIR__ . "\\assets";
	
    public $depends = [
		'hSlim\assets\BootstrapAsset',
		'hSlim\assets\JqueryAsset',
	];
	
    public $js = [
		'js/test2.js',
	];
    
    public $css = [
		'css/test1.css',
	];
}
;