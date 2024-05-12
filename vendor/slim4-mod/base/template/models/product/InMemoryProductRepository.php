<?php
declare(strict_types=1);

namespace App\models\#TPL_PRODUCT#;


use App\models\#TPL_PRODUCT#\#U_TPL_PRODUCT#;

use hSlim\base\domain\RepositoryInterface;
use hSlim\base\domain\DomainException\DomainRecordNotFoundException;

class InMemory#U_TPL_PRODUCT#Repository implements RepositoryInterface
{
    /**
     * @var #U_TPL_PRODUCT#[]
     */
    private $data;

    /**
     * @param #U_TPL_PRODUCT#[]|null $data
     */
    public function __construct(&$c, $data = null)
    {
        $this->data = $data ?? [
            1 => new #U_TPL_PRODUCT#($c, ['id' => 1, 'name' => 'IOS Phone']),
            2 => new #U_TPL_PRODUCT#($c, ['id' => 2, 'name' => 'IBM Computer']),
            3 => new #U_TPL_PRODUCT#($c, ['id' => 3, 'name' => 'HONDA Motor']),
            4 => new #U_TPL_PRODUCT#($c, ['id' => 4, 'name' => 'TOYOTA Car'])
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
    public function findById(int $id): #U_TPL_PRODUCT#
    {
        if (!isset($this->data[$id])) {
            throw new DomainRecordNotFoundException();
        }
        return $this->data[$id];
    }
}
