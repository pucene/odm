<?php

namespace Pucene\Components\ODM\Repository;

use Pucene\Component\QueryBuilder\Search;
use Pucene\Components\ODM\Search\DocumentIterator;

interface ObjectRepositoryInterface
{
    public function createSearch(): Search;

    public function execute(Search $search): DocumentIterator;

    public function find(string $id);

    public function index($object): void;
}
