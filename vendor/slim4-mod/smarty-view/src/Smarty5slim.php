<?php
namespace hSlim\Views;

use Smarty\Smarty;

use ArrayIterator;
use Psr\Http\Message\ResponseInterface;
use Smarty\Exception;

/**
 * Smarty View
 *
 * This class is a Slim Framework 3 view helper built
 * on top of the Smarty templating component. Smarty is
 * a PHP component created by New Digital Group, Inc.
 *
 * @link http://www.smarty.net/
 */
class Smarty5slim implements \ArrayAccess
{
	protected $extManager;
	
    /**
     * Smarty instance
     *
     * @var \Smarty
     */
    public $smarty;

    /**
     * Default view variables
     *
     * @var array
     */
    protected $defaultVariables = [];
	

    /********************************************************************************
     * Constructors and service provider registration
     *******************************************************************************/

    /**
     * Create new Smarty view
     *
     * @param string|array $paths Paths to templates directories
     * @param array $settings Smarty settings
     */
    public function __construct($paths, $settings = [], $c = null)
    {
        $this->smarty = empty($c) ? new Smarty() : $c->get(Smarty::class);

        $this->smarty->setTemplateDir($paths);

        if (isset($settings['cacheDir'])) {
            $this->smarty->setCacheDir($settings['cacheDir']);
        }

        if (isset($settings['compileDir'])) {
            $this->smarty->setCompileDir($settings['compileDir']);
        }

        if (isset($settings['pluginsDir'])) {
            $this->smarty->addPluginsDir($settings['pluginsDir']);
        }
		
    }
	
	
	public function _setExtManager(&$ext) {
		$this->extManager = $ext;
	}
	
	public function _getExtManager() {
		return $this->extManager = $ext;
	}
	
	
	public function registerPlugin($type, $name, $cacheable = true, $arrConstructorParams = null)
    {
        $this->extManager->registerPlugin($type, $name, $cacheable, $arrConstructorParams);
		return $this;
    }
	

    /**
     * Fetch rendered template
     *
     * @param string $template Template pathname relative to templates directory
     * @param array $data Associative array of template variables
     *
     * @return string
     * @throws SmartyException
     */
    public function fetch(string $template, &$data = []): string
    {
        //$data = array_merge($this->defaultVariables, $data);
		foreach ($this->defaultVariables as $k => $v) {
			if (is_array($k)) {
				$data[] = $k;
				continue;
			}
			if (key_exists($k, $data)) continue;
			$data[$k] = $v;
		}

        $this->smarty->assign($data);

        return $this->smarty->fetch($template, str_replace('. /\\','-',$template));
    }

    /**
     * Output rendered template
     *
     * @param ResponseInterface $response
     * @param string $template Template pathname relative to templates directory
     * @param array $data Associative array of template variables
     * @return ResponseInterface
     * @throws SmartyException
     */
    public function render(ResponseInterface $response, string $template, &$data = []): ResponseInterface
    {
        $response->getBody()->write($this->fetch($template, $data));

        return $response;
    }

    /********************************************************************************
     * Accessors
     *******************************************************************************/

    /**
     * Return Smarty instance
     *
     * @return \Smarty
     */
    public function getSmarty(): Smarty
    {
        return $this->smarty;
    }

    /********************************************************************************
     * ArrayAccess interface
     *******************************************************************************/

    /**
     * Does this collection have a given key?
     *
     * @param  string $key The data key
     *
     * @return bool
     */
    public function offsetExists($key): bool
    {
        return array_key_exists($key, $this->defaultVariables);
    }

    /**
     * Get collection item for key
     *
     * @param string $key The data key
     *
     * @return mixed The key's value, or the default value
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($key)
    {
        return $this->defaultVariables[$key];
    }

    /**
     * Set collection item
     *
     * @param string $key The data key
     * @param mixed $value The data value
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($key, $value)
    {
        $this->defaultVariables[$key] = $value;
    }

    /**
     * Remove item from collection
     *
     * @param string $key The data key
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($key)
    {
        unset($this->defaultVariables[$key]);
    }

    /********************************************************************************
     * Countable interface
     *******************************************************************************/

    /**
     * Get number of items in collection
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->defaultVariables);
    }

    /********************************************************************************
     * IteratorAggregate interface
     *******************************************************************************/

    /**
     * Get collection iterator
     *
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->defaultVariables);
    }
	
}
