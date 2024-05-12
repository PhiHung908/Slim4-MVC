<?php
declare(strict_types=1);

namespace hSlim\base\domain;

use hSlim\base\domain\RepositoryInterface;

use Psr\Log\LoggerInterface;
use Psr\Container\ContainerInterface;

class DynamicRepository implements RepositoryInterface
{
	public $model;
	
	public function __construct(ContainerInterface &$c, string $actionCallClass = null, bool $inMemory = false)
    {
		if (empty($actionCallClass)) {
			$callClass = rtrim(str_replace("/", "\\", $c->get('uriHelper')->uriPath()),"\\");
			if (!empty($c->get('routeCurrentModule'))) $callClass = substr($callClass, strpos($callClass,'\\',1));
		} else $callClass = dirname($actionCallClass);
	
		if (substr($callClass, strrpos($callClass,"\\")+1)=='auto_gen') {
			$callClass = dirname($callClass);
		}
		
		$a = explode("\\", $callClass);
		
		if ($inMemory && count($a)>2) array_pop($a);
			
		$modelName = array_pop($a);
		
		$modelClass = ($inMemory ? "App\\controllers" : '') . implode("\\",$a);
		array_pop($a); 
		if (!$inMemory) $this->model =  new (implode("\\",$a) . "\\models\\$modelName\\".ucfirst($modelName))($c);
		else $this->model =  new ("App\\models\\$modelName\\InMemory" . ucfirst($modelName) . "Repository")($c);
		
	}
	
	
	public function findById(int $id)
	{
		return $this->model->findById($id);
	}
	
	
	/**
     * @return Product[]
     */
    public function findAll(): array
	{
		return $this->model->findAll();
	}
}
