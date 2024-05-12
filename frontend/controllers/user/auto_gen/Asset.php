<?php

declare(strict_types=1);

namespace App\controllers\user\auto_gen;

class Asset extends \hSlim\base\AbstractAsset
{
	public $sourcePath = __DIR__ . "\\assets";
	
    public $depends = [
		'hSlim\assets\BootstrapAsset',
		'hSlim\assets\JqueryAsset',
		//'hSlim\assets\JuiAsset',
	];
	
    
    public $js = [
		'js/test1.js',
		//'js/test2.js',
	];
    
    public $css = [
		'css/test1.css',
	];
}
