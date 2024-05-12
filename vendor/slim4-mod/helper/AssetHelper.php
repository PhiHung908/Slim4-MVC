<?php
namespace hSlim\helper;

class AssetHelper
{
	private $assetSender = [];
	private $viewData = [];
	private $isUseCssWwwRoot = false;
	
	public function __construct(protected &$c)
	{
		$this->viewData = $c->get('viewData') ?? null;
		if (empty($this->viewData)) return;
		
		$this->assetSender = &$this->viewData['assetSender'];
		
		$psr4PathHelper = $c->get('psr4PathHelper');
		
		$basePath = $psr4PathHelper->aliasToFull($c->get('Settings')->get('assetsRoot')."\\", true);
		$WwwRoot = $psr4PathHelper->aliasToFull('WwwRoot\\', true);

		/*
		$rs = $psr4PathHelper->detectModule();
		$gModule = ltrim($rs['gModule'],'/');
		if (!empty($gModule)) $gModule .= '-';
		*/
		
		if (strpos($basePath, $WwwRoot) === 0) {
			$this->isUseCssWwwRoot = true;
			//$srcPath = $WwwRoot;
		} //else $srcPath = $psr4PathHelper->aliasToFull("App\\", true, -1);


		foreach ($this->assetSender as $dir => $aVal) {
			foreach($aVal as $k => $file) {
				if (in_array($k,['js','css','img'])) {
					$aa = [];
					foreach($file as $z => $fname) {
						$rDot = strrpos($fname,'.')+1;
						switch($k) {
							case 'js' : $rDot = 'js' === substr($fname,$rDot); break; 
							case 'css' : $rDot = 'css' === substr($fname,$rDot); break;
							case 'img' : $rDot = in_array(substr($fname,$rDot),["img","jpg","jpeg","gif","png","tga","tiff","bmp","ico"]); break;
							default: break;
						}
						if (true !== $rDot) continue;
						$aa[] = '/assets/' . $dir . '/' . $fname;
					}
					$this->addArrayAsset($dir, [$k => $aa]);
				}
			}
		}
	}
	

	private function addArrayAsset($dir, array $k_v)
	{
		if (!isset($this->assetSender[$dir])) $this->assetSender[$dir] = [];
		$this->assetSender[$dir] = array_merge($this->assetSender[$dir], $k_v);
	}
	
	
	public function renderAsset($xKey = null)
	{
		if (!$this->isUseCssWwwRoot) 
			$routePath = $this->c->get('routeCurrentModule');
		else $routePath = '';
		
		$s = '';
		if ($xKey === '.*') $xKey = null;
		foreach ($this->assetSender as $dir) {
			if (!isset($dir['sourcePath'])) continue;
			foreach ($dir as $jscss => $aFile) { 
				if (!is_array($aFile)) continue;
				$xK = $xKey ? substr($xKey,1): $jscss;
				foreach ($aFile as $n => $file) { //TODO: còn phần muốn đặt các file ở head hoặc body hoặc footer..
					if ($xK === 'js' && $jscss == 'js') {
						$s .= '<script type="text/javascript" src="' . $routePath.$file . '"></script>';
					} else if ($xK === 'css' && $jscss == 'css') {
						$s .= '<link rel="stylesheet" href="' . $routePath.$file . '">';
					} if ($xK === 'img' && $jscss == 'img') {
						$s .= '<link rel="image" href="' . $routePath.$file . '">';
					}
				}
			}
		}
		return $s;
	}
}
