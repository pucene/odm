<?php

namespace Pucene\Components\ODM\Repository;

use Pucene\Component\QueryBuilder\Search;
use Pucene\Components\ODM\Converter\ArrayToObjectConverter;
use Pucene\Components\ODM\Converter\ObjectToArrayConverter;
use Pucene\Components\ODM\Metadata\ClassMetadata;
use Pucene\Components\ODM\ObjectManagerInterface;
use Pucene\Components\ODM\Search\DocumentIterator;

class ObjectRepository implements ObjectRepositoryInterface
{
    /**
     * @var ClassMetadata
     */
    private $metadata;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    public function __construct(ClassMetadata $metadata, ObjectManagerInterface $objectManager)
    {
        $this->metadata = $metadata;
        $this->objectManager = $objectManager;
    }

    public function createSearch(): Search
    {
        return $this->objectManager->createSearch();
    }

    public function execute(Search $search): DocumentIterator
    {
        $result = $this->objectManager->getIndex()->search($search, $this->metadata->getType());

        return new DocumentIterator($result, $this->metadata, new ArrayToObjectConverter());
    }

    public function find(string $id)
    {
        $result = $this->objectManager->getIndex()->get($this->metadata->getType(), $id);

        $converter = new ArrayToObjectConverter();

        return $converter->convert($result['_source'], $this->metadata);
    }

    public function index($object): void
    {
        $objectToArrayConverter = new ObjectToArrayConverter();

        $result = $this->objectManager->getIndex()->index(
            $objectToArrayConverter->convert($object, $this->metadata),
            $this->metadata->getType(),
            $this->metadata->getIdProperty()->getValue($object)
        );

        $this->metadata->getIdProperty()->setValue($object, $result['_id']);
    }
}
