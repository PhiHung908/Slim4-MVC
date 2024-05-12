<?php
// src/models/user/User.php

namespace App\models\user;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;

use Doctrine\ORM\EntityManager;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

use Psr\Container\ContainerInterface;

use JsonSerializable;

#[Entity, Table(name: 'user')]
class User extends \hSlim\base\domain\AbstractRepository implements JsonSerializable
{
	private $repository;
	
	
	#[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
	private ?int $id;

	#[Column(type: 'string')]
    private string $username;

	#[Column(type: 'string')]
    private string $firstname;

	#[Column(type: 'string')]
    private string $lastname;
	
	
	public function __construct(protected ContainerInterface &$c, $data = null)
    {
		if (!empty($data)) {
			$this->id = $data['id'];
			$this->username = $data['username'];
			$this->firstname = $data['firstname'];
			$this->lastname = $data['lastname'];
		}
		parent::__construct($this->c);
		//your code...
	}

    public function getId(): ?int
    {
        return $this->id;
    }

	public function getName(): string
    {
        return $this->username;
    }
	

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getFirstName(): string
    {
        return $this->firstname;
    }

    public function getLastName(): string
    {
        return $this->lastname;
    }
	
	
	public function toArray($id)
	{
		$dql = $this->em->createQuery("select t from " . __CLASS__ . " t where :id is null or t.id = :id")
				->setParameters(new ArrayCollection([
					new Parameter('id', $id)
				]));
		return $dql->getResult();
	}
	

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'firstName' => $this->firstname,
            'lastName' => $this->lastname,
        ];
    }	
	/**
	  * {@overide}
	  */
	public function repositoryInstance(): \hSlim\base\domain\AbstractRepository {
		if (empty($this->repository)) $this->repository = parent::repositoryInstance();
		return $this;
	}
}
