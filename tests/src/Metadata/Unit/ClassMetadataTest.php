<?php

namespace Pucene\Tests\Components\Metadata\Unit;

use PHPUnit\Framework\TestCase;
use Pucene\Components\Metadata\Cache\CacheResource;
use Pucene\Components\Metadata\ClassMetadata;
use Pucene\Components\Metadata\PropertyMetadataInterface;

class ClassMetadataTest extends TestCase
{
    public function testGetName()
    {
        $metadata = new ClassMetadata(self::class);

        $this->assertEquals(self::class, $metadata->getName());
    }

    public function testGetProperty()
    {
        $property = $this->prophesize(PropertyMetadataInterface::class);
        $metadata = new ClassMetadata(self::class, ['test' => $property->reveal()]);

        $this->assertEquals($property->reveal(), $metadata->getProperty('test'));
    }

    public function testGetProperties()
    {
        $property = $this->prophesize(PropertyMetadataInterface::class);
        $metadata = new ClassMetadata(self::class, ['test' => $property->reveal()]);

        $this->assertEquals(['test' => $property->reveal()], $metadata->getProperties());
    }

    public function testAddProperty()
    {
        $metadata = new ClassMetadata(self::class);

        $property = $this->prophesize(PropertyMetadataInterface::class);
        $property->getName()->willReturn('test');
        $metadata->addProperty($property->reveal());

        $this->assertEquals(['test' => $property->reveal()], $metadata->getProperties());
    }

    public function testGetResources()
    {
        $resource = $this->prophesize(CacheResource::class);
        $metadata = new ClassMetadata(self::class, [], [$resource->reveal()]);

        $this->assertEquals([$resource->reveal()], $metadata->getResources());
    }

    public function testAddResource()
    {
        $metadata = new ClassMetadata(self::class);

        $resource = $this->prophesize(CacheResource::class);
        $metadata->addResource($resource->reveal());
        $this->assertEquals([$resource->reveal()], $metadata->getResources());
    }
}
