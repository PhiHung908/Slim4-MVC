<?php
namespace hSlim\base;

abstract class AbstractAsset
{
    public $sourcePath;
	public $dependsAllSrcFiles = false; //true để chép tất cả src depends vào cache cho các js trong depends có thể cần dùng lẫn nhau.
    
    public $basePath;
    
    public $baseUrl;
    
    public $depends = [];
    
    public $js = [];
    
    public $css = [];
    
    public $jsOptions = [];
    
    public $cssOptions = [];
    
    public $publishOptions = [];
		
	public $storageLast = ['priv_params' => ['classModTime' => 0]];
	
	private $classDir;
	private $markDepends;
	
	private $classModTime = 0;
	
	public function __construct(protected &$c, private $internal = false)
    {		
		$this->markDepends = $this->storageLast;
		
		$psr4PathHelper = $c->get('psr4PathHelper');
		
		$this->basePath =  $c->get('Settings')->get('assetsRoot') ?? "App\\web";
				
		$s = get_called_class();
		$ReflectionClass = new \ReflectionClass($s); 
		
		if (!$internal) {	
			$clsFName = $ReflectionClass->getFilename();
			$this->classModTime = filemtime($clsFName);
			//$this->classDir = dirname($clsFName);
			$this->classDir = $psr4PathHelper->aliasToFull($this->basePath . "\\assets\\_cacheDepends\\", true);
			$this->classDir .= str_replace(':','.',str_replace("\\",".",$psr4PathHelper->aliasToFull(ltrim($s,"/\\ "), -1)));
			if (!is_dir($this->classDir)) {
				$a = explode("\\",$this->classDir);
				for ($i = 1; $i<count($a); $i++) {
					$pth = implode("\\", array_slice($a,0,$i+1));
					if (!is_dir($pth)) mkdir($pth);
				}
			}
			if (file_exists($this->classDir . '\\MarkDepends.php')) {
				$this->markDepends = require ($this->classDir . '\\MarkDepends.php');
				if ($this->markDepends['priv_params']['classModTime'] == $this->classModTime) {
					$this->storageLast = $this->markDepends;
					return $this;
				}
			}
		}
	
		$storage = [];
		
		$A = $ReflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC);
		foreach ($A as $prop) {
			if ($prop->class === $s) {
				$storage[str_replace('\\','.',$s)][$prop->name] = $prop->getValue($this);
			}
		}
		$this->storageLast = array_merge($this->storageLast, $storage);
		$this->cacheAsset($this->basePath, $this->sourcePath, $storage, false, $psr4PathHelper);
	}

	private function cacheAsset($basePath, $sourcePath, &$storage, $internal = false, &$psr4PathHelper = null)
	{
		$basePath = $psr4PathHelper->aliasToFull($basePath . "\\", true);	
		$sourcePath = str_replace("\\", "/", $sourcePath);
		$sourcePath = $psr4PathHelper->aliasToFull($sourcePath . "\\", true);
		
		$pth = $basePath . 'assets';
		if (!is_dir($pth)) {
			mkdir($pth);
			$h = fopen($pth . '\\.gitignore','w');
			fwrite($h, '!**/.*
*.*.*
!.*
!.gitignore
* - Copy*
_**
');
			fclose($h);
		}
		foreach ($storage as $sDir => &$aProp) {
			if (!isset($aProp['sourcePath'])) continue;
			/*$gMod = !$this->internal && !$internal ? str_replace("\\","-",$psr4PathHelper->aliasToFull(str_replace("-","\\",$sDir), -1)) : '';
			if (!empty($gMod)) $pth .= '\\' . $gMod;
			else $pth .= '\\' . $sDir;
			*/
			
			$gMod = str_replace("\\",".",$psr4PathHelper->aliasToFull(str_replace(".","\\",$sDir), -1));
			if (strpos($gMod,':') !== false) {$pth .= "\\".$sDir; $gMod = '';}
			else $pth .= '\\' . $gMod;
			
			if (!empty($gMod)) {
				if (!in_array($gMod, array_keys($storage))) {
					unset($storage[$sDir]);
					$storage[$gMod] = &$aProp;
					unset($this->storageLast[$sDir]);
					$this->storageLast[$gMod] = $aProp;
				}
			}
			
			foreach($aProp as $k => $v) {
				if ( strpos(',js,css,img,' ,','.$k.',') !== false) {
					foreach($v as $file) {
						if (empty($file)) continue;
						$src = null;
						$des = null;
						$file = str_replace("\\","/",$file);
						if (strpos($file, "/")!==false) {
							$kPth = explode("/",$file);
							$kF = array_pop($kPth);
							$sPth = implode("\\", $kPth);
							if (!empty($sPth)) {
								$sPth .= "\\";
							}
							if (file_exists($sourcePath . $sPth . $kF)) {
								if (!is_dir($pth)) mkdir($pth);
								$d = $pth;
								foreach($kPth as $p) {
									$d .= "\\" . $p;
									if (!is_dir($d)) mkdir($d);
								}
								$src = $sourcePath . $sPth . $kF;
								$des = $d . "\\" . $kF;
							}
						}
						
						if (empty($src)) {
							$src = $sourcePath . $file;
							if (!file_exists($src)) {
								$src = $sourcePath . $k . '\\' . $file;
							} else {
								if (!is_dir($pth)) mkdir($pth);
								$des = $pth . "\\" . $file;
							}
						}

						if (!file_exists($src)) continue;

						if (empty($des)) {
							if (!is_dir($pth)) mkdir($pth);
							$d = $pth . '\\' . $k;
							if (!is_dir($d)) mkdir($d);
							$des = $d . '\\' . $file;
						}
						if (!file_exists($des) || filemtime($src) > filemtime($des)) {
							copy($src, $des);
						}
					}
				} else if ($k == 'depends' /*&& !$internal*/&& !$this->internal) {
					$hasChg = false;
					foreach($v as $dep) {
						$dep = ltrim($dep,"/\\ ");
						if (empty($dep)) continue;
						if (!in_array($dep, array_keys($this->markDepends))) {
							$this->markDepends['depends'][] = $dep;
							$hasChg = true;
						
							$tmp = new $dep($this->c, true);
							$asset = $tmp->storageLast;
							$this->storageLast = array_merge($asset, $this->storageLast);

							$this->cacheAsset($tmp->basePath, $tmp->sourcePath, $asset, true, $psr4PathHelper);
						}
					}
					if (!$this->internal && $hasChg) {
						$h = fopen($this->classDir . '\\MarkDepends.php', 'w');
						$this->storageLast['priv_params']['classModTime'] = $this->classModTime;
						fwrite($h, '<?php
declare(strict_types=1);
//autoGen để đánh dấu đã gọi depends - FOR-FAST
return ' . var_export($this->storageLast, true) . '
;'
);
						fclose($h);
					}
				}
			}
		}
		$this->storageLast;
	}
	
	
	public function registerTwig()
	{
		return $this->storageLast;
	}
	
    public static function register($view)
    {
       return 1;
    }
}
;
