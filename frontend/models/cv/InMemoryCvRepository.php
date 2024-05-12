<?php
declare(strict_types=1);

namespace App\models\cv;


use App\models\cv\Cv;

use hSlim\base\domain\RepositoryInterface;
use hSlim\base\domain\DomainException\DomainRecordNotFoundException;

class InMemoryCvRepository implements RepositoryInterface
{
    /**
     * @var Cv[]
     */
    private $data;

    /**
     * @param Cv[]|null $data
     */
    public function __construct(&$c, $data = null)
    {
        $this->data = $data ?? [
            1 => new Cv($c, ['id' => 1, 'name' => 'IOS Phone']),
            2 => new Cv($c, ['id' => 2, 'name' => 'IBM Computer']),
            3 => new Cv($c, ['id' => 3, 'name' => 'HONDA Motor']),
            4 => new Cv($c, ['id' => 4, 'name' => 'TOYOTA Car'])
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
    public function findById(int $id): Cv
    {
        if (!isset($this->data[$id])) {
            throw new DomainRecordNotFoundException();
        }
        return $this->data[$id];
    }
}
