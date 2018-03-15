<?php

namespace Pucene\Tests\Components\ODM\Functional\Repository;

use PHPUnit\Framework\TestCase;
use Pucene\Component\Client\IndexInterface;
use Pucene\Component\QueryBuilder\Search;
use Pucene\Components\ODM\Metadata\ClassMetadata;
use Pucene\Components\ODM\Metadata\IdPropertyMetadata;
use Pucene\Components\ODM\Metadata\PropertyMetadata;
use Pucene\Components\ODM\ObjectManagerInterface;
use Pucene\Components\ODM\Repository\ObjectRepository;
use Pucene\Components\ODM\Search\DocumentIterator;
use Pucene\Tests\AppBundle\Document\ArticleDocument;

class ObjectRepositoryTest extends TestCase
{
    public function testIndex()
    {
        $index = $this->prophesize(IndexInterface::class);
        $objectManager = $this->prophesize(ObjectManagerInterface::class);
        $objectManager->getIndex()->willReturn($index->reveal());

        $repository = new ObjectRepository($this->getClassMetadata(), $objectManager->reveal());

        $article = new ArticleDocument('Pucene is awesome', '123-123-123');

        $index->index(['title' => 'Pucene is awesome'], 'article', '123-123-123')->shouldBeCalled()->willReturn(
            [
                '_id' => '123-123-123',
                '_type' => 'article',
                '_index' => 'test',
                '_score' => 1,
                '_source' => [
                    'title' => 'Pucene is awesome',
                ],
            ]
        );

        $repository->index($article);
    }

    public function testIndexNoId()
    {
        $index = $this->prophesize(IndexInterface::class);
        $objectManager = $this->prophesize(ObjectManagerInterface::class);
        $objectManager->getIndex()->willReturn($index->reveal());

        $repository = new ObjectRepository($this->getClassMetadata(), $objectManager->reveal());

        $article = new ArticleDocument('Pucene is awesome', null);

        $index->index(['title' => 'Pucene is awesome'], 'article', null)->shouldBeCalled()->willReturn(
            [
                '_id' => '123-123-123',
                '_type' => 'article',
                '_index' => 'test',
                '_score' => 1,
                '_source' => [
                    'title' => 'Pucene is awesome',
                ],
            ]
        );

        $repository->index($article);

        $this->assertEquals('123-123-123', $article->getId());
    }

    public function testFind()
    {
        $index = $this->prophesize(IndexInterface::class);
        $objectManager = $this->prophesize(ObjectManagerInterface::class);
        $objectManager->getIndex()->willReturn($index->reveal());

        $index->get('article', '123-123-123')->willReturn(
            [
                '_id' => '123-123-123',
                '_type' => 'article',
                '_index' => 'test',
                '_score' => 1,
                '_source' => [
                    'title' => 'Pucene is awesome',
                ],
            ]
        );

        $repository = new ObjectRepository($this->getClassMetadata(), $objectManager->reveal());

        $object = $repository->find('123-123-123');

        $this->assertNotNull($object);
        $this->assertInstanceOf(ArticleDocument::class, $object);
        $this->assertEquals('Pucene is awesome', $object->getTitle());
    }

    public function testCreateSearch()
    {
        $objectManager = $this->prophesize(ObjectManagerInterface::class);
        $search = $this->prophesize(Search::class);
        $objectManager->createSearch()->willReturn($search->reveal());

        $repository = new ObjectRepository($this->getClassMetadata(), $objectManager->reveal());

        $this->assertEquals($search->reveal(), $repository->createSearch());
    }

    public function testExecute()
    {
        $index = $this->prophesize(IndexInterface::class);
        $objectManager = $this->prophesize(ObjectManagerInterface::class);
        $objectManager->getIndex()->willReturn($index->reveal());

        $repository = new ObjectRepository($this->getClassMetadata(), $objectManager->reveal());

        $search = new Search();
        $index->search($search, 'article')->willReturn(
            [
                'hits' => [
                    'total' => 10,
                    'hits' => [
                        [
                            '_id' => '123-123-123',
                            '_type' => 'article',
                            '_index' => 'test',
                            '_score' => 1,
                            '_source' => [
                                'title' => 'Pucene is awesome',
                            ],
                        ],
                        [
                            '_id' => '123-456-789',
                            '_type' => 'article',
                            '_index' => 'test',
                            '_score' => 1,
                            '_source' => [
                                'title' => 'Pucene is really awesome',
                            ],
                        ],
                    ],
                ],
            ]
        );

        $result = $repository->execute($search);

        $this->assertInstanceOf(DocumentIterator::class, $result);
        $this->assertEquals(2, $result->count());
        $this->assertEquals(2, count($result));
        $this->assertEquals(10, $result->total());

        $result = iterator_to_array($result);
        $this->assertInstanceOf(ArticleDocument::class, $result[0]);
        $this->assertEquals('123-123-123', $result[0]->getId());
        $this->assertEquals('Pucene is awesome', $result[0]->getTitle());

        $this->assertInstanceOf(ArticleDocument::class, $result[1]);
        $this->assertEquals('123-456-789', $result[1]->getId());
        $this->assertEquals('Pucene is really awesome', $result[1]->getTitle());
    }

    protected function getClassMetadata(): ClassMetadata
    {
        $metadata = new ClassMetadata('article', ArticleDocument::class);
        $metadata->addProperty(new PropertyMetadata('string', $metadata, 'title'));
        $metadata->setIdProperty(new IdPropertyMetadata($metadata, 'id'));

        return $metadata;
    }
}
