<?php

declare(strict_types=1);

namespace App\controllers\user\auto_gen;

class Asset extends \hSlim\base\AbstractAsset
{
	public $sourcePath = __DIR__ . "\\assets";
	
    public $depends = [
		//'App\controllers\user\auto_gen\Asset',
	];
	
    
    public $js = [
		//'test1.js',
		//'test2.js',
	];
    
    public $css = [
		//'test1.css',
	];
}
