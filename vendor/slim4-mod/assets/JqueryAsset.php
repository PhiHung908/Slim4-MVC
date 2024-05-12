<?php
declare(strict_types=1);

namespace hSlim\assets;

class JqueryAsset extends \hSlim\base\AbstractAsset
{
	public $sourcePath = '@bower/jquery';
    
    public $depends = [
    ];
	
	public $js = [
		'dist/jquery.js',
        //'dist/jquery.min.js',
		//'dist/jquery.min.map',
    ];
    public $css = [
    ];
}
