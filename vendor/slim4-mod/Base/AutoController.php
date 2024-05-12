<?php
declare(strict_types=1);
// /vendor/slim4-mod/helper/autoController.php


use Slim\App;

/**
 * use: khai bao truoc 'class': #[FastRoute('GET', 'parten', callabled)] hoac #[FastRoute([GET,POST], 'parten', callabled)] - giong kieu tham so cua FastRoute\RouteCollector
 */
return  function (&$c)
	{
		$app = $c->get(App::class);
		$psr4PathHelper = $c->get('psr4PathHelper');
		
		$rs = $psr4PathHelper->detectModule();

		$basePath = $psr4PathHelper->aliasToFull($c->get('Settings')->get('assetsRoot')."\\", true);
		$WwwRoot  = $psr4PathHelper->aliasToFull('WwwRoot\\', true);
		
		if (strpos($basePath, $WwwRoot) !== 0)
		eval( '$app->map([\'GET\'],\'' . $rs['m'] . '/assets/{Allow:.*}\', function (\Psr\Http\Message\ServerRequestInterface $request
, \Psr\Http\Message\ResponseInterface $response, $args) 
{
	$fname = "' . $rs['d'] . '" . $args["Allow"]; /* $args["cacheDir"] . "/" . $args["jscss"] . "/" . $args["fname"]; */
	/* $ext = substr($args["fname"], strrpos($args["fname"],".")+1); */
	$ext = substr($args["Allow"], strrpos($args["Allow"],".")+1);
	$mime = $ext == "js" ? "text/javascript"
					: ($ext == "css" ? "text/css"
					: (in_array($ext,["img","jpg","jpeg","gif","png","tga","tiff","bmp","ico"]) ? "images/images"
					: (in_array($ext,["json", "map"]) ? "application/json"
					: "text/html")));
	if (file_exists( $fname )) {
		$newStream = new \GuzzleHttp\Psr7\LazyOpenStream($fname, "r");
		return $response->withHeader("Content-type", $mime)
						->withHeader("Cache-Control", "max-age=604800, immutable")
						->withBody($newStream);
	}
	$response->getBody()->write("console.log(\'khong tim thay file... " . $fname . " " . (file_exists( $fname )?1:0) . "\');");
	return $response->withHeader("Content-type", $mime);
})->setName("assetsRoute");');
		
		
		$gModule = $rs['gModule'];
		$onlyThisModel = $rs['u'][0];
		$andThisAction = ucfirst($rs['u'][1] ?? '');
		

		$dir = $psr4PathHelper->aliasToFull("App\\controllers",true);
		$s1 = '';
		$s2 = '';
		$x = 0;
		$not = '';
		function addRoute($gModule, $dir, $name, &$x, &$s1, &$s2, &$not, $andThisAction) {
			$namespace = '';
			$m = null;
			$parten = '';
			$dirName = null;
			$afunc = null;
			$filename = str_replace('/','\\',$dir . $name);
			if (!file_exists($filename)) return;
			$handle = fopen($filename, "rb");
			while (($ln = fgets($handle, 4096)) !== false) {
				if (empty($namespace) && strpos($ln,'namespace') !== false) {
					$namespace = trim(explode(';',explode('namespace',$ln)[1])[0]);
				}
				$ln = $m ?? str_replace(';'.chr(10), chr(10) ,str_replace(chr(13).chr(10), chr(10), str_replace(' ','',$ln)));

				if (empty($m) && strpos($ln,'#[FastRoute(') !== false) {
					$opts = str_replace('"','', str_replace('\'','', rtrim(trim(explode(']'.chr(10),explode('#[FastRoute(', $ln)[1])[0]), ')') ));
					if (strpos($opts, ',[') !== false) {
						$dirName = '[' . explode(',[', $opts)[1];
					}
					$k = strpos($opts,']');
					$n = 1;
					if (empty($k)) {
						$n = 0;
						$opts .= ','; 
						$k = strpos($opts ,',');
						$opts = '[' . $opts;
						if (empty($k)) {
							$k = strlen($opts)+1;
						}
					}
					$m = str_replace(' ','', substr($opts,1, $k-$n));
					$opts = explode(',', substr($opts, $k+1) . ',,');
					$parten = $opts[1];
				}
				if (!empty($m) && !empty($namespace)) break;
				if (!empty($namespace) && (
							strpos($ln, chr(10).'class') !== false 
						||  strpos($ln, chr(10).'abstract') !== false 
						||  strpos($ln, chr(10).'interface') !== false
						||  strpos($ln, chr(10).'final') !== false
						||  strpos($ln, chr(10).'trait') !== false
					)) break; //for fast
			}
			fclose($handle);

			if (empty($m)) {
				$m = 'GET'; //$_SERVER['REQUEST_METHOD'];
				$parten = '{Route:.*}';
			} else {
				$m = str_replace(',', '","',$m);
			}
			
			$dirName = $dirName ?? $namespace . '\\' . explode('.',$name)[0];
			
			if ($x == 1) $not = strtolower(explode('Action',$name)[0]);
			
			$s2 = str_replace(']"', ']' , str_replace('"[', '[' , str_replace('#PARTEN#', $parten, str_replace('#ACTION#', strtolower(explode('Action',$name)[0]), str_replace('#MODEL#', strtolower(explode('Action.php',$name)[0]), str_replace('#METHOD#', strtoupper($m), str_replace('#DIR##NAME#', $dirName, 
				(!empty($andThisAction) && $x==1 ? 
					'$group->map(["#METHOD#"], "/#ACTION#", "#DIR##NAME#");
					 $group->map(["#METHOD#"], "/#ACTION#/#PARTEN#", "#DIR##NAME#")->setName("currentRoute");
					' : '$group->map(["#METHOD#"], "/#PARTEN#", "#DIR##NAME#")->setName("currentRoute");'
				)
			)))))));
			
			if ($x==0) $s1 = str_replace(']"', ']' , str_replace('"[', '[' , str_replace('#MODULE#', $gModule, str_replace('#MODEL#', strtolower(explode('Controller.php',$name)[0]), str_replace('#METHOD#', strtoupper($m), str_replace('#DIR##NAME#', $dirName, 'use Slim\Interfaces\RouteCollectorProxyInterface as Group;
				$app->group("#MODULE#/#MODEL#", function (Group $group) {
						$group->map(["#METHOD#"], "", "#DIR##NAME#");
						$group->map(["#METHOD#"], "/{Route:[^(#NOT#/)].*}", "#DIR##NAME#");
						#SUBACTION#
					});
				'))))));
			$x++;
		}
		
		function scasFile(&$x, &$s1, &$s2, &$not, $gModule, $dir, $prefix = 'Controller.php', $onlyThisModel = null, $andThisAction = null)
		{
			$gBreak = false;
			$m0 = $onlyThisModel[0] ?? null; //for fast
			$d = $dir. (empty($onlyThisModel) ? "" : $onlyThisModel . "\\") . ($x==0 ? 'auto_gen\\' : '');
			foreach(glob($d . "*.*") as $fe) {
				$e = substr($fe,strrpos($fe,"\\")+1);
				if (($m0 ?? $e[0] === $e[0]) && $e !== '.' && $e !== '..' && strpos($e, $prefix)>0 && (empty($onlyThisModel) || ($x==0 && $e === ucfirst($onlyThisModel).$prefix) || ($x==1 && $e = ucfirst($andThisAction).$prefix ) ) ) {								
					addRoute($gModule, $d, $e, $x, $s1, $s2, $not, $andThisAction);
					if (!empty($onlyThisModel)) {
						$gBreak = true;
						break;
					}
				}
			}
		}
		
		if (!empty($onlyThisModel)) {
			scasFile($x, $s1, $s2, $not, $gModule, $dir, 'Controller.php', $onlyThisModel);
			scasFile($x, $s1, $s2, $not, $gModule, $dir, 'Action.php'	   , $onlyThisModel, $andThisAction);
		} else {
			scasFile($x, $s1, $s2, $not, $gModule, $dir, 'Controller.php');
		}
		eval(str_replace('#NOT#', $not, str_replace('#SUBACTION#', $s2, $s1)));
	}
;