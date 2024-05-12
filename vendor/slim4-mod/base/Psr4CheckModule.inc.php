<?php
declare(strict_types=1);
// /vendor/slim4-mod/base/Psr4CheckModule.inc.php

use Slim\App;

/**
 * detect and route module for bootstrap change alias
 * return 1: arrayForAutoRoute, 2: $c->set('routeCurrentModule', hasUrlModule), 3: Void-setup-Route-Module-assets
 */
return  function (&$containerBuilder, &$psr4PathHelper)
	{
		$sysSettings = [
			'@bower\\' => 'hSlim/../bower-asset',
		];
		
		$cFake = null;
		$psr4PathHelper->prependPrs4Alias(null, $cFake, $sysSettings);
			
		$gModule = '';
		$containerBuilder->addDefinitions([
			'routeCurrentModule' => ''
		]);
		
		$m = '';
		
		$u = explode('/',ltrim($_SERVER['REQUEST_URI'],'/'));
		$u[0] = strtolower($u[0] ?? ''); $u[1] = strtolower($u[1] ?? '');
		$d = $psr4PathHelper->aliasToFull($u[0]. "\\");
		if (file_exists($d . "controllers\\$u[1]\\auto_gen\\" . ucfirst($u[1]) . "Controller.php")) {
			$gModule = '/' . $u[0];
			$m = $gModule . '/' . $u[1];
			$containerBuilder->addDefinitions([
				'routeCurrentModule' => $m
			]);
			$psr4PathHelper->addPsr4("App\\", $d, true);
			$u = array_slice($u,1);
		} else $d = $psr4PathHelper->aliasToFull("App\\", true);
		
		return ['u' => $u, 'gModule' => $gModule, 'd' => str_replace('\\','/', $d . 'web/assets/'), 'm' => $m];
	}
;