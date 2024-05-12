<?php
namespace hSlim\base;

class emuClassLoader
{
	protected static $aAlias = [];
	protected static $vendorDir;
	
	public function __construct($vendorDir)
	{
		static::$vendorDir = $vendorDir;
		$this->getPrefixesPsr4();
	}
	 
	public function getPrefixesPsr4()
	{
		if (0<count(static::$aAlias)) return static::$aAlias;
		if (file_exists(static::$vendorDir . "\\composer\\autoload_psr4.php"))
		static::$aAlias = require_once static::$vendorDir . "\\composer\\autoload_psr4.php";
		else static::$aAlias = [];
//static::$aAlias = [];
		return static::$aAlias;
	}
	
	public function addPsr4($prefix, $paths, $prepend = false)
	{
		if ($prepend) static::$aAlias = array_merge([$prefix => $paths]??[], static::$aAlias);
		else static::$aAlias = array_merge(static::$aAlias, [$prefix => $paths]);
	}
	
	public function setPsr4($prefix, $paths)
	{
		static::$aAlias[$prefix] =  $paths;
	}
}

class Psr4PathHelper
{
	
    /** @var \Closure(string):void */
    //Chua can dung private static $includeFile;
	
	protected static $vendorDir;
	protected static $hasComposerLoader = true;
	protected static $lastPrefix = '';
	protected static $aAlias;
	
	protected static $pauseRefreshAlias = false;
	
	protected static ?array $u = null;
	protected static $gModule = '';
	protected static $d = '';
	protected static $m = '';
	
	protected static $dict = [];
	protected static $dictK = [];
	
	public function __construct(private &$classLoader = null)
	{
		$this->getVendorDir();
		
        //Chua can dung self::initializeIncludeClosure();
		
		if (empty($classLoader)) {
			static::$hasComposerLoader = false;
			$this->classLoader = new emuClassLoader(static::$vendorDir);
		}
		$this->_refreshAlias();
		if (!array_key_exists('hSlim\\',static::$aAlias)) {
			$this->setPsr4("hSlim\\", $this->tripDotDot(__DIR__ . '\..\\'));
		}
		//Chua can dung spl_autoload_register(['hSlim\base\Psr4PathHelper', 'autoload'], true, true);
	}
	
	private function _refreshAlias() {
		if (static::$pauseRefreshAlias) return;
		static::$aAlias = $this->classLoader->getPrefixesPsr4();
		uksort(static::$aAlias, function ($a, $b) {
			return strcasecmp($b, $a);
			/*
			if (strlen($a)>strlen($b)) return -1;
			else if (strlen($a)<strlen($b)) return 1;
			else {
				for ($i=0; $i<strlen($a); $i++) {
					if ($a[$i] > $b[$i]) return -1;
					else if ($a[$i] < $b[$i]) return 1;
				}
				return 0;
			}*/
		});
	}
	
	public function getVendorDir()
	{
		if (!empty(static::$vendorDir)) return static::$vendorDir;
		$oldDir = getcwd();
		chdir(__DIR__ . '\\..\\..');
		static::$vendorDir = getcwd();
		chdir($oldDir);
		return static::$vendorDir;
	}
	
	private function tripDotDot($s) {
		if (strpos($s, "\\..")===false) return str_replace("\\\\", "\\", $s);
		return $this->tripDotDot(preg_replace('(\\\[^\\\]+\\\\.\.)', '', $s));
	}
	
	
	public function findFile($class, $internal = false)
	{
		//*
		if (static::$hasComposerLoader) {
			if ($nm = $this->classLoader->findFile($class))  //str_replace("\\", DIRECTORY_SEPARATOR, str_replace('/', DIRECTORY_SEPARATOR, $this->classLoader->findFile($class)));
				return $nm;
			$cls = $class;
		}
		//*/
		
		$class = str_replace('/', "\\", $class);
		//$class = strtr($class, '\\', DIRECTORY_SEPARATOR);
		
		if (0 === strncmp($class,"\\",1)) $class = dirname(static::$vendorDir) . $class;
		
		//if (file_exists($class) && !is_dir($class)) return $this->tripDotDot(str_replace('/', DIRECTORY_SEPARATOR, $class));
		//if (file_exists($class . '.php')/* && !is_dir($class . '.php')*/) return $this->tripDotDot(str_replace('/', DIRECTORY_SEPARATOR, $class . '.php'));
		if (file_exists($class)) return $class;
		if (file_exists($class . '.php')) return  $class . '.php';


		$cls = $this->aliasToFull($cls, true, $internal);
		if ($cls) {
			if (file_exists($cls)) return $cls;
			if (file_exists($cls . '.php')) return $cls . '.php';
		}
/*
		if (static::$lastPrefix && strpos($class, static::$lastPrefix) === 0) {
			$nm = $cls . substr($class, strlen(static::$lastPrefix)-1);
			if (file_exists($nm) && !is_dir($nm)) return $nm;
			if (file_exists($nm . '.php')) return $nm . '.php';
		}
//*/		
		
		
		
		/*
		$nm = false;
		if (static::$hasComposerLoader) $nm = $this->classLoader->findFile($class); //str_replace("\\", DIRECTORY_SEPARATOR, str_replace('/', DIRECTORY_SEPARATOR, $this->classLoader->findFile($class)));
		if (file_exists($nm)) return $nm;
		*/
		$nm = (strpos($class, ":\\") !== 0 ? "" : static::$vendorDir) . $class ;//str_replace("\\", DIRECTORY_SEPARATOR, str_replace('/', DIRECTORY_SEPARATOR, (strpos($class, ":\\") !== 0 ? "" : static::$vendorDir) . $cls));
		if (file_exists($nm . '.php') ) return $nm . '.php';
		if (file_exists($nm) ) return $nm;

		return false;
		return $nm;
	}
	
	public function addPsr4($prefix, $paths, $prepend = false)
	{
		$this->classLoader->addPsr4($prefix, $paths, $prepend);
		$this->_refreshAlias();
	}
	
	public function setPsr4($prefix, $paths)
	{
		$this->classLoader->setPsr4($prefix, is_string($paths) ? [$paths] : $paths);
		$this->_refreshAlias();
	}
	
	public function getPrefixesPsr4() {
		return $this->classLoader->getPrefixesPsr4();
	}
	
	public function aliasToFull($pth, $addRootDir = false, $internal = false)
	{
		$p = str_replace('/', "\\", $pth); //for fast
		
		$p0 = $p[0]; //for fast
		
		$addRoot = '';
		if ($addRootDir || "\\" === $p0) {
			if ("\\" === $p0) $p0 = $p[1] ?? '';
			$addRoot = dirname(static::$vendorDir);
			if (strpos($p, ":\\") !== false) {
				$p = substr($p, strlen($addRoot));
				//$p0 = $p[1] ?? $p[0];
				$p0 = $p[0];
				$addRoot .= "\\";
			}
			$p = ltrim($p, "/\\ ");
			if (empty($p)) $p = $pth;
		}
		
		//cache dict
		//*
		if (!$internal && (in_array($p0, static::$dictK) || in_array($p, static::$dictK))) {
			if (in_array($p0, static::$dictK)) {
				$aSeach = &static::$dict[$p0];
			} else
			return static::$dict[$p][0];
		} else $aSeach = &static::$aAlias;
		//*/
		/*
		if (!$internal && in_array($p0, static::$dictK)) {
			$aSeach = &static::$dict[$p0];
		} else $aSeach = &static::$aAlias;
		*/
		//check if err $aSeach = &static::$aAlias;
		
		$hasRs = false;
		foreach($aSeach as $k => &$v) {
			if ($p0===$k[0] && 0===strpos($p, $k)) {
				if (!in_array($k[0], static::$dictK)) {
					static::$dictK[] = $k[0];
				};
				//static::$dict[$k[0]][$k] = &$v;
				//array_push(static::$dict[$k[0]][$k], $v);
				//*
				if (!isset(static::$dict[$k[0]]) || !array_key_exists($k,static::$dict[$k[0]])) {
					static::$dict[$k[0]][$k] = &$v;
					//krsort(static::$dict[$k[0]],SORT_STRING);
				}
				//*/
				if ($hasRs) continue;
				foreach ($v as $ff) {
					if (is_string($ff)) $ff = [$ff];
					foreach($ff as &$fx) {
						$f = $addRoot . substr($fx ,strlen(dirname(static::$vendorDir))) . substr($p, strlen($k)-0);
						$z = false;
						if (file_exists($f)
							// && !is_dir($f)
							) {
							static::$lastPrefix = $k;
							$z = $f;
						}
						/*
						$f .= '.php';
						if (!$z && file_exists($f) 
							// && !is_dir($f)
							) {
							static::$lastPrefix = $k;
							$z = $f;
						}
						*/
						
						if (!$z && file_exists($fx)
							// && !is_dir($fx)
							) {
							static::$lastPrefix = $k;
							if (strpos($fx, static::$vendorDir) !== 0) {
								$z = rtrim($fx,"/\\ ") . (strlen($k) == strlen($pth) ? "" : "\\" . ltrim(substr($pth,strlen($k)),"/\\ ") );
							} else {
								$z = $addRoot . str_replace ('/', "\\", substr($fx ,strlen(dirname(static::$vendorDir))) . '/' . ltrim(substr($p,strlen($k)),"/\\ ") );
							}
						}
						/*
						if (!$z && file_exists($fx . '.php')
								// && !is_dir($fx)
								) {
							static::$lastPrefix = $k;
							if (strpos($fx, static::$vendorDir) !== 0) {
								$z = rtrim($fx,"/\\ ") . (strlen($k) == strlen($pth) ? "" : "\\" . ltrim(substr($pth,strlen($k)),"/\\ ") );
							} else {
								$z = $addRoot . str_replace ('/', "\\", substr($fx ,strlen(dirname(static::$vendorDir))) . '/' . ltrim(substr($p,strlen($k)),"/\\ ") );
							}
							$z .= '.php';
						}
						*/
						
						if ($z) {
							$z = $this->tripDotDot($z);
							if (is_dir($z)) $z = rtrim($z, "/\\ ") . "\\";
							//return $z;
							$hasRs = $z; break;
						}
					}
					if ($hasRs) break;
				}
			}
		}
		if (!$internal && !$hasRs) {
			$hasRs = $this->aliasToFull($pth, $addRootDir, true);
		}

		if ($hasRs) {
			if (-1 === $addRootDir) { //lay vendor/...
				if (strpos($hasRs, dirname(static::$vendorDir)) === 0) $hasRs = substr($hasRs, strlen(dirname(static::$vendorDir))+1);
				else if (strpos($hasRs, static::$vendorDir) === 0) $hasRs = substr($hasRs, strlen(static::$vendorDir)+1);
			} else if (-2 === $addRootDir) { //lay tu sau (vendor/)aaa
				if (strpos($hasRs, static::$vendorDir) === 0) $hasRs = substr($hasRs, strlen(static::$vendorDir)+1);
				else if (strpos($hasRs, dirname(static::$vendorDir)) === 0) $hasRs = substr($hasRs, strlen(dirname(static::$vendorDir))+1);
			}
			return $hasRs;
		}
		if ($internal) return false;
		
		static::$lastPrefix = null;
			
		$z = ltrim($this->tripDotDot($addRoot . "\\" . $p),"/\\ ");
		if (is_dir($z)) $z = rtrim($z, "/\\ ") . "\\";
		
		if (!in_array($p, static::$dictK)) {
			static::$dictK[] = $p;
		}
		static::$dict[$p][] = $z;
		
		return $z;
	}
	
	/*
	public function aliasToUrl($pth, $addRoot = false)
	{
		$rs = substr($this->aliasToFull(ltrim($pth,"/\\ ")), strlen(dirname(static::$vendorDir)));
		return str_replace("\\", '/', $rs);
	}
	*/


	public function prependPrs4Alias($__DIR__, &$containerBuilder = null, array $aPsr4 = null, $prepend = true)
	{
		$hasArrPsr4 = !empty($aPsr4);
		if ($hasArrPsr4 || file_exists($__DIR__ . "\\" . 'prs4-alias.php')) {
			static::$pauseRefreshAlias = true;
			if (!$hasArrPsr4) {
				$aPsr4 = require $__DIR__ . "\\" . 'prs4-alias.php';
			}
			$baseDir = dirname($this->getVendorDir());
			foreach($aPsr4 as $k => $v) {
				if (empty($v)) continue;
				if (is_string($v)) $v = [$v];
				$k = rtrim($k, "\\") . "\\";
				
				foreach ($v as $kn => $vs) {
					if (!$hasArrPsr4) {
						if (in_array($vs[0], ["\\", "/"])) $v[$kn] = str_replace('/', "\\", $baseDir . $vs);
					}
					$v[$kn] = $this->aliasToFull($v[$kn], !in_array($vs[0], ["\\", "/"]));
				}
				$this->addPsr4($k, $v, $prepend);
			}
			static::$pauseRefreshAlias = false;
			$this->_refreshAlias();
		}
		if (!empty($containerBuilder)) {
			$this->detectModule($containerBuilder, $this);
			(require_once $this->aliasToFull('hSlim/base/Psr4SysDependencies.inc.php', true))($containerBuilder, $this);
		}
	}
	
	public function detectModule(&$containerBuilder = null) {
		if (!empty(static::$u) || empty($containerBuilder)) {
			return ['u' => static::$u, 'gModule' => static::$gModule, 'd' => static::$d, 'm' => static::$m];
		}
		$rs = (require_once $this->aliasToFull('hSlim/base/Psr4CheckModule.inc.php', true))($containerBuilder, $this);
		static::$u = $rs['u'];
		static::$gModule = $rs['gModule'];
		static::$d = $rs['d'];
		static::$m = $rs['m'];
		return $rs;
	}
	
	/*Chua can dung 
	public function autoload($classOrFileName) {
		if (($file = $this->findFile($classOrFileName,true, false)) || ($file = $this->findFile($classOrFileName,true, true))) {
            $includeFile = self::$includeFile;
            $includeFile($file);
            return true;
        }
		return false;
	}
	
	/**
     * @return void
     * /
    private static function initializeIncludeClosure()
    {
        if (self::$includeFile !== null) {
            return;
        }

        /**
         * Scope isolated include.
         *
         * Prevents access to $this/self from included files.
         *
         * @param  string $file
         * @return void
         * /
        self::$includeFile = \Closure::bind(static function($file) {
            //include $file;
			//require_once $file;
			include_once $file;
        }, null, null);
    }
	*/
}
;
