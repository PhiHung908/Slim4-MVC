<?php
declare(strict_types=1);

namespace App\models\product;


use App\models\product\Product;

use hSlim\base\domain\RepositoryInterface;
use hSlim\base\domain\DomainException\DomainRecordNotFoundException;

class InMemoryProductRepository implements RepositoryInterface
{
    /**
     * @var Product[]
     */
    private $data;

    /**
     * @param Product[]|null $data
     */
    public function __construct(&$c, $data = null)
    {
        $this->data = $data ?? [
            1 => new Product($c, ['id' => 1, 'name' => 'IOS Phone']),
            2 => new Product($c, ['id' => 2, 'name' => 'IBM Computer']),
            3 => new Product($c,['id' => 3, 'name' => 'HONDA Motor']),
            4 => new Product($c, ['id' => 4, 'name' => 'TOYOTA Car'])
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
    public function findById(int $id): Product
    {
        if (!isset($this->data[$id])) {
            throw new DomainRecordNotFoundException();
        }
        return $this->data[$id];
    }
	
	public function repositoryInstance(): self
	{
		return $this;
	}
}
