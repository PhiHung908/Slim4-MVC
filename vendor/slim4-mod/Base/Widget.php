<?php
declare(strict_types=1);

namespace hSlim\base;

abstract class Widget
{
	public $options = ['id' => null, 'clientOptions' => ['id' => null]];
	
	public function __construct(&$options = [])
	{
		$this->options = array_merge($this->options, $options);
		$this->getId();
		$this->init();
	}
	
	protected function init()
	{
	}
	
	protected function getId() {
		if (empty($this->options['id']) && empty($this->options['clientOptions']['id'])) {
				$this->options['clientOptions']['id'] = $this->_autoId();
				$this->options['id'] = this->options['clientOptions']['id'];
		}  else if (empty($this->options['id'])) $this->options['id'] = this->options['clientOptions']['id'];
		else $this->options['clientOptions']['id'] = $this->options['id'];
		return $this->options['id'];
	}
	
	protected abstract function registerClientOptions($name, $id = null);
	
	protected abstract function registerClientEvents($name, $id = null);
        
	protected function registerWidget($name, $id = null)
    {
        if ($id === null) {
            $id = $this->getId();
        }
        //JuiAsset::register($this->getView());
        $this->registerClientEvents($name, $id);
        $this->registerClientOptions($name, $id);
    }
	
	private function _autoId()
	{
		return 'id0';
	}
}