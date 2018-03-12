<?php

namespace Pucene\Tests\Components\Metadata\Unit;

use PHPUnit\Framework\TestCase;
use Pucene\Components\Metadata\Cache\CacheResource;
use Pucene\Components\Metadata\ClassMetadataInterface;
use Pucene\Components\Metadata\Driver\DriverInterface;
use Pucene\Components\Metadata\MetadataFactory;
use Pucene\Components\Metadata\PropertyMetadataInterface;

class MetadataFactoryTest extends TestCase
{
    /**
     * @var DriverInterface
     */
    private $driver;

    /**
     * @var MetadataFactory
     */
    private $metadataFactory;

    protected function setUp()
    {
        $this->driver = $this->prophesize(DriverInterface::class);

        $this->metadataFactory = new MetadataFactory($this->driver->reveal());
    }

    public function testGetMetadataForClass()
    {
        $metadata = $this->prophesize(ClassMetadataInterface::class);
        $this->driver->loadMetadataForClass(new \ReflectionClass(A::class))->willReturn($metadata->reveal());

        $result = $this->metadataFactory->getMetadataForClass(A::class);
        $this->assertEquals($metadata->reveal(), $result);
    }

    public function testGetMetadataForClassWithHierarchy()
    {
        $metadataA = $this->prophesize(ClassMetadataInterface::class);
        $this->driver->loadMetadataForClass(new \ReflectionClass(A::class))->willReturn($metadataA->reveal());

        $this->driver->loadMetadataForClass(new \ReflectionClass(B::class))->willReturn(null);

        $propertyA = $this->prophesize(PropertyMetadataInterface::class);
        $metadataA->getProperties()->willReturn(['propertyA' => $propertyA->reveal()]);

        $metadataC = $this->prophesize(ClassMetadataInterface::class);
        $this->driver->loadMetadataForClass(new \ReflectionClass(C::class))->willReturn($metadataC->reveal());

        $propertyC = $this->prophesize(PropertyMetadataInterface::class);
        $propertyC->getName()->willReturn('propertyC');
        $metadataC->getProperties()->willReturn(['propertyC' => $propertyC->reveal()]);

        $metadataA->hasProperty('propertyC')->shouldBeCalled();
        $metadataA->addProperty($propertyC->reveal())->shouldBeCalled();

        $resource = $this->prophesize(CacheResource::class);
        $metadataC->getResources()->willReturn([$resource->reveal()]);

        $metadataA->addResource($resource->reveal())->shouldBeCalled();

        $result = $this->metadataFactory->getMetadataForClass(C::class);
        $this->assertEquals($metadataA->reveal(), $result);
    }
}

class A
{
    public $propertyA;
}

class B extends A
{
}

class C extends B
{
    public $propertyC;
}
