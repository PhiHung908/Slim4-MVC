<?php
declare(strict_types=1);

namespace App\models\user;


use App\models\user\User;

use hSlim\base\domain\RepositoryInterface;
use hSlim\base\domain\DomainException\DomainRecordNotFoundException;

class InMemoryUserRepository implements RepositoryInterface
{
    /**
     * @var User[]
     */
    private $data;

    /**
     * @param User[]|null $data
     */
    public function __construct(&$c, $data = null)
    {
        $this->data = $data ?? [
            1 => new User($c, ['id' => 1, 'username' => 'User-1', 'firstname' => 'user', 'lastname' => 'no 1']),
            2 => new User($c, ['id' => 2, 'username' => 'User-2', 'firstname' => 'user', 'lastname' => 'no 2']),
            3 => new User($c, ['id' => 3, 'username' => 'User-3', 'firstname' => 'user', 'lastname' => 'no 3']),
            4 => new User($c, ['id' => 4, 'username' => 'User-4', 'firstname' => 'user', 'lastname' => 'no 4'])
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        return array_values($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function findById(int $id): User
    {
        if (!isset($this->data[$id])) {
            throw new DomainRecordNotFoundException();
        }
        return $this->data[$id];
    }
	
}
