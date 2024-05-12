<?php
declare(strict_types=1);

namespace hSlim\base\domain;


use Psr\Http\Message\ResponseInterface as Response;

interface RepositoryInterface //extends GlobalRepositoryInterface
{	

	
	/**
     * @return Product[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * dynamic return Product
     * @throws RecodNotFoundException
     */
    public function findById(int $id);
}
