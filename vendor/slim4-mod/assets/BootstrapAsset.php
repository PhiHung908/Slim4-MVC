<?php
declare(strict_types=1);

namespace hSlim\assets;

class BootstrapAsset extends \hSlim\base\AbstractAsset
{
	public $sourcePath = '@bower/bootstrap';
    
    public $depends = [
    ];
	
	public $js = [
		//'dist/js/bootstrap.js',
        //'dist/js/bootstrap.min.js',
		//'dist/js/bootstrap.min.js.map',
		'dist/js/bootstrap.bundle.min.js',
		'dist/js/bootstrap.bundle.min.js.map',
    ];
    public $css = [
		'dist/css/bootstrap.min.css',
		'dist/css/bootstrap.min.css.map',
    ];
}
