<?php

namespace Pucene\Tests\Components\ODM\Functional\Metadata\Driver;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use Pucene\Components\Metadata\Driver\FileLocator;
use Pucene\Components\ODM\Metadata\ClassMetadata;
use Pucene\Components\ODM\Metadata\Driver\AnnotationDriver;
use Pucene\Tests\AppBundle\Document\ArticleDocument;

class AnnotationDriverTest extends TestCase
{
    public function testLoadMetadataForClass()
    {
        $locator = new FileLocator(
            ['Pucene\\Tests\\AppBundle\\Document' => __DIR__ . '/../../../../AppBundle/Document']
        );
        $reader = new AnnotationReader();

        $driver = new AnnotationDriver($reader, $locator);

        /** @var ClassMetadata $metadata */
        $metadata = $driver->loadMetadataForClass(new \ReflectionClass(ArticleDocument::class));

        $this->assertEquals(ArticleDocument::class, $metadata->getName());
        $this->assertEquals('article', $metadata->getType());

        $titleProperty = $metadata->getProperty('title');
        $this->assertEquals('title', $titleProperty->getName());
        $this->assertEquals('string', $titleProperty->getType());

        $this->assertEquals($metadata->getIdProperty()->getName(), 'id');
    }

    public function testGetAllClassNames()
    {
        $locator = new FileLocator(
            ['Pucene\\Tests\\AppBundle\\Document' => __DIR__ . '/../../../../AppBundle/Document']
        );
        $reader = new AnnotationReader();

        $driver = new AnnotationDriver($reader, $locator);
        $this->assertEquals([ArticleDocument::class], $driver->getAllClassNames());
    }
}
