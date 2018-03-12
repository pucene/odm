<?php

namespace Pucene\Tests\Components\ODM\Unit\Metadata\Driver;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use Pucene\Components\Metadata\Driver\FileLocatorInterface;
use Pucene\Components\ODM\Annotation\Document;
use Pucene\Components\ODM\Annotation\Property;
use Pucene\Components\ODM\Metadata\Driver\AnnotationDriver;
use Pucene\Tests\Components\Metadata\Unit\A;

class AnnotationDriverTest extends TestCase
{
    public function testLoadMetadataForClass()
    {
        $class = new \ReflectionClass(A::class);

        $locator = $this->prophesize(FileLocatorInterface::class);
        $reader = $this->prophesize(AnnotationReader::class);

        $locator->findFileForClass($class, 'php')->willReturn('A.php');

        $document = new Document(['value' => 'article']);
        $reader->getClassAnnotations($class)->willReturn([$document]);

        $property = new Property(['value' => 'string']);
        $reader->getPropertyAnnotations(new \ReflectionProperty(A::class, 'propertyA'))->willReturn([$property]);

        $driver = new AnnotationDriver($reader->reveal(), $locator->reveal());
        $metadata = $driver->loadMetadataForClass($class);

        $this->assertEquals(A::class, $metadata->getName());
        $this->assertEquals('article', $metadata->getType());

        $result = $metadata->getProperty('propertyA');
        $this->assertEquals('propertyA', $result->getName());
        $this->assertEquals('string', $result->getType());
    }

    public function testGetAllClassNames()
    {
        $locator = $this->prophesize(FileLocatorInterface::class);
        $reader = $this->prophesize(AnnotationReader::class);

        $locator->findAllClasses('php')->willReturn([A::class]);

        $driver = new AnnotationDriver($reader->reveal(), $locator->reveal());

        $this->assertEquals([A::class], $driver->getAllClassNames());
    }
}
