<?php

namespace Pucene\Tests\Components\ODM\Functional\Repository;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use Pucene\Component\Client\ClientInterface;
use Pucene\Component\Client\IndexInterface;
use Pucene\Component\QueryBuilder\Search;
use Pucene\Components\Metadata\Driver\FileLocator;
use Pucene\Components\Metadata\MetadataFactory;
use Pucene\Components\Metadata\MetadataFactoryInterface;
use Pucene\Components\ODM\Metadata\ClassMetadata;
use Pucene\Components\ODM\Metadata\Driver\AnnotationDriver;
use Pucene\Components\ODM\ObjectManager;
use Pucene\Components\ODM\Repository\ObjectRepository;
use Pucene\Tests\AppBundle\Document\ArticleDocument;

class ObjectManagerTest extends TestCase
{
    public function testCreate()
    {
        $client = $this->prophesize(ClientInterface::class);
        $objectManager = new ObjectManager($client->reveal(), $this->getMetadataFactory(), 'test');

        $client->exists('test')->shouldBeCalled()->willReturn(false);
        $client->create('test', ['mappings' => ['article' => ['title' => ['type' => 'string']]]])->shouldBeCalled();

        $objectManager->create();
    }

    public function testDelete()
    {
        $client = $this->prophesize(ClientInterface::class);
        $objectManager = new ObjectManager($client->reveal(), $this->getMetadataFactory(), 'test');

        $client->exists('test')->shouldBeCalled()->willReturn(true);
        $client->delete('test')->shouldBeCalled();

        $objectManager->delete();
    }

    public function testCreateRepository()
    {
        $client = $this->prophesize(ClientInterface::class);
        $objectManager = new ObjectManager($client->reveal(), $this->getMetadataFactory(), 'test');

        $repository = $objectManager->createRepository(ArticleDocument::class);

        $this->assertInstanceOf(ObjectRepository::class, $repository);

        $reflectionProperty = new \ReflectionProperty($repository, 'metadata');
        $reflectionProperty->setAccessible(true);
        $metadata = $reflectionProperty->getValue($repository);

        $reflectionProperty = new \ReflectionProperty($repository, 'objectManager');
        $reflectionProperty->setAccessible(true);
        $innerObjectManager = $reflectionProperty->getValue($repository);

        $this->assertInstanceOf(ClassMetadata::class, $metadata);
        $this->assertEquals(ArticleDocument::class, $metadata->getName());

        $this->assertEquals($objectManager, $innerObjectManager);
    }

    public function testCreateSearch()
    {
        $client = $this->prophesize(ClientInterface::class);
        $objectManager = new ObjectManager($client->reveal(), $this->getMetadataFactory(), 'test');

        $this->assertInstanceOf(Search::class, $objectManager->createSearch());
    }

    public function testGetIndex()
    {
        $index = $this->prophesize(IndexInterface::class);

        $client = $this->prophesize(ClientInterface::class);
        $objectManager = new ObjectManager($client->reveal(), $this->getMetadataFactory(), 'test');

        $client->exists('test')->shouldBeCalled()->willReturn(true);
        $client->get('test')->shouldBeCalled()->willReturn($index->reveal());

        $this->assertEquals($index->reveal(), $objectManager->getIndex());
    }

    public function testGetIndexNotExists()
    {
        $index = $this->prophesize(IndexInterface::class);

        $client = $this->prophesize(ClientInterface::class);
        $objectManager = new ObjectManager($client->reveal(), $this->getMetadataFactory(), 'test');

        $client->exists('test')->shouldBeCalled()->willReturn(false);
        $client->create('test', ['mappings' => ['article' => ['title' => ['type' => 'string']]]])
            ->shouldBeCalled()
            ->willReturn($index->reveal());
        $client->get('test')->shouldBeCalled()->willReturn($index->reveal());

        $this->assertEquals($index->reveal(), $objectManager->getIndex());
    }

    protected function getMetadataFactory(): MetadataFactoryInterface
    {
        $dirs = ['Pucene\Tests\AppBundle\Document' => __DIR__ . '/../../AppBundle/Document'];

        return new MetadataFactory(new AnnotationDriver(new AnnotationReader(), new FileLocator($dirs)));
    }
}
