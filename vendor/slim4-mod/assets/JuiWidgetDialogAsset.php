<?php
declare(strict_types=1);

namespace hSlim\assets;

class JuiAJuiWidgetDialogAssetsset extends \hSlim\base\AbstractAsset
{
	public $sourcePath = '@bower/jquery-ui';
    
    public $depends = [
       'hSlim\assets\JuiAsset',
    ];
	
	public $js = [
        'ui/widgets/dialog.js',
    ];
    public $css = [
    ];
}
