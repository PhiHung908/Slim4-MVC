<?php
declare(strict_types=1);

namespace hSlim\assets;

class JuiAsset extends \hSlim\base\AbstractAsset
{
	public $sourcePath = '@bower/jquery-ui';
    
    public $depends = [
       'hSlim\assets\JqueryAsset',
    ];
	
	public $js = [
        //'jquery-ui.min.js',
		'jquery-ui.js',
    ];
    public $css = [
        //'themes/smoothness/jquery-ui.min.css',
		'themes/smoothness/jquery-ui.css',
		'themes/smoothness/theme.css',
    ];
}
