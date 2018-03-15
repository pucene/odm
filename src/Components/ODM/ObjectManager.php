<?php

namespace Pucene\Components\ODM;

use Pucene\Component\Client\ClientInterface;
use Pucene\Component\Client\IndexInterface;
use Pucene\Component\QueryBuilder\Search;
use Pucene\Components\Metadata\MetadataFactoryInterface;
use Pucene\Components\ODM\Metadata\ClassMetadata;
use Pucene\Components\ODM\Repository\ObjectRepository;
use Pucene\Components\ODM\Repository\ObjectRepositoryInterface;

class ObjectManager implements ObjectManagerInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var MetadataFactoryInterface
     */
    private $metadataFactory;

    /**
     * @var string
     */
    private $indexName;

    public function __construct(ClientInterface $client, MetadataFactoryInterface $metadataFactory, string $indexName)
    {
        $this->client = $client;
        $this->metadataFactory = $metadataFactory;
        $this->indexName = $indexName;
    }

    public function create(): void
    {
        if ($this->client->exists($this->indexName)) {
            return;
        }

        $classNames = $this->metadataFactory->getAllClassNames();

        $mappings = [];
        foreach ($classNames as $className) {
            /** @var ClassMetadata $metadata */
            $metadata = $this->metadataFactory->getMetadataForClass($className);
            $mappings[$metadata->getType()] = $metadata->getMapping();
        }

        $this->client->create($this->indexName, ['mappings' => $mappings]);
    }

    public function delete(): void
    {
        if (!$this->client->exists($this->indexName)) {
            return;
        }

        $this->client->delete($this->indexName);
    }

    public function createRepository(string $documentClass): ObjectRepositoryInterface
    {
        $metadata = $this->metadataFactory->getMetadataForClass($documentClass);

        return new ObjectRepository($metadata, $this);
    }

    public function createSearch(): Search
    {
        return new Search();
    }

    public function getIndex(): IndexInterface
    {
        if (!$this->client->exists($this->indexName)) {
            $this->create();
        }

        return $this->client->get($this->indexName);
    }
}
