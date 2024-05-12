<?php
declare(strict_types=1);

namespace hSlim\base\domain;

use Doctrine\ORM\EntityManager;
use hSlim\base\domain\DomainException\DomainRecordNotFoundException;


//use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;

abstract class AbstractRepository implements RepositoryInterface
{
	protected ContainerInterface $c;
	private $_db;
	private $_em;
	private $_emRepository;
	private $callClass;
	
	//public function __construct(protected ContainerInterface &$c)
	public function __construct()
	{
		$this->_em = $this->c->get(EntityManager::class); 
		$this->callClass = get_called_class();
		$this->_emRepository = $this->_em->getRepository($this->callClass);
	}
	
	public function db($db = null)
	{
		return $this->_db;
	}
	
	public function em()
	{
		return $this->_em;
	}
	public function emRepository() 
	{
		return $this->_emRepository;
	}

	/**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
		return array_values($this->_emRepository->findAll($this->callClass));
    }
	
    /**
     * {@inheritdoc}
     */
    public function findById(int $id) //: Product
    {
		$r = $this->_emRepository->find($id);
		if (empty($r)) {
            throw new DomainRecordNotFoundException();
        }
        return $r;
    }
}
