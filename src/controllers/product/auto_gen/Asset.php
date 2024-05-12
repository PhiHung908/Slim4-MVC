<?php

declare(strict_types=1);

namespace App\controllers\product\auto_gen;

class Asset extends \hSlim\base\AbstractAsset
{
	public $sourcePath = __DIR__ . "\\assets";
	
    public $depends = [
		//'hSlim\assets\JqueryAsset',
		//'hSlim\assets\JuiAsset',
		//ex: 'App\controllers\user\auto_gen\Asset',
	];
	
    
    public $js = [
		//'js/test1.js',
		//'js/test2.js',
	];
    
    public $css = [
		//'css/test1.css',
	];
}
