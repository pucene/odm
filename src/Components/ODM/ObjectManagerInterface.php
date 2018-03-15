<?php

namespace Pucene\Components\ODM;

use Pucene\Component\Client\IndexInterface;
use Pucene\Component\QueryBuilder\Search;
use Pucene\Components\ODM\Repository\ObjectRepositoryInterface;

interface ObjectManagerInterface
{
    public function create(): void;

    public function delete(): void;

    public function createRepository(string $documentClass): ObjectRepositoryInterface;

    public function createSearch(): Search;

    public function getIndex(): IndexInterface;
}
