<?php

declare(strict_types=1);

namespace hSlim\base;

//use hSlim\Views\ExtendingHandle\functions\Asset;
use hSlim\Views\AssetHelper;

//use Glazilla\TwigAsset\TwigAssetManagement;
use hSlim\glazillaTwigAsset\src\TwigAssetManagement;

/**
 * PhiHung Note: phai sua file TwigAssetManagement cho nó extend TwigAssetExtension
 * mod func nhu duoi
	public function getAssetExtension() : AbstractExtension
    {
        $this->loadDefaultPackage();
        $this->loadNamedPackages();
    -    //return new TwigAssetExtension(new Packages($this->defaultPackage, $this->namedPackages));
	+	parent::__construct(new Packages($this->defaultPackage, $this->namedPackages));
	+	return $this;

    }
 */
class ExtendTwigAssetMngr extends TwigAssetManagement
{
	public function __construct(array $userSettings = [],private &$c = null)
    {
		parent::__construct($userSettings);
	}
	
	public function asset(?string $path = '', string $packageName = ''): string
	{
		if (empty($path) || strpos(',.js,.css,.img,.*,', ','.$packageName.',')!==false ) {
			echo $this->c->get('assetHelper')->renderAsset($path);
			return '';
		} else return parent::asset($path, $packageName);
	}
	
}
