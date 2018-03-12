<?php

namespace Pucene\Tests\Components\Metadata\Unit;

use PHPUnit\Framework\TestCase;
use Pucene\Components\Metadata\CachableMetadataFactory;
use Pucene\Components\Metadata\Cache\CacheInterface;
use Pucene\Components\Metadata\ClassMetadataInterface;
use Pucene\Components\Metadata\Driver\DriverInterface;
use Pucene\Components\Metadata\MetadataFactory;

class CachableMetadataFactoryTest extends TestCase
{
    /**
     * @var CacheInterface
     */
    private $cache;

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
        $this->cache = $this->prophesize(CacheInterface::class);
        $this->driver = $this->prophesize(DriverInterface::class);

        $this->metadataFactory = new CachableMetadataFactory($this->cache->reveal(), $this->driver->reveal());
    }

    public function testGetMetadataForClass()
    {
        $metadata = $this->prophesize(ClassMetadataInterface::class);
        $this->driver->loadMetadataForClass(new \ReflectionClass(A::class))->willReturn($metadata->reveal());

        $this->cache->fetch(A::class)->willReturn(null);
        $this->cache->save(A::class, $metadata->reveal())->shouldBeCalled();

        $result = $this->metadataFactory->getMetadataForClass(A::class);
        $this->assertEquals($metadata->reveal(), $result);
    }

    public function testGetMetadataForClassContains()
    {
        $metadata = $this->prophesize(ClassMetadataInterface::class);
        $this->driver->loadMetadataForClass(new \ReflectionClass(A::class))->shouldNotBeCalled();

        $this->cache->fetch(A::class)->willReturn($metadata->reveal());
        $this->cache->save(A::class, $metadata->reveal())->shouldNotBeCalled();

        $result = $this->metadataFactory->getMetadataForClass(A::class);
        $this->assertEquals($metadata->reveal(), $result);
    }

    public function testGetMetadataForClassWithHierarchy()
    {
        $metadataA = $this->prophesize(ClassMetadataInterface::class);
        $metadataA->getProperties()->willReturn([]);
        $metadataA->getResources()->willReturn([]);
        $this->driver->loadMetadataForClass(new \ReflectionClass(A::class))->willReturn($metadataA->reveal());

        $metadataB = $this->prophesize(ClassMetadataInterface::class);
        $metadataB->getProperties()->willReturn([]);
        $metadataB->getResources()->willReturn([]);
        $this->driver->loadMetadataForClass(new \ReflectionClass(B::class))->shouldNotBeCalled();

        $this->cache->fetch(A::class)->willReturn(null);
        $this->cache->save(A::class, $metadataA->reveal())->shouldBeCalled();

        $this->cache->fetch(B::class)->willReturn($metadataB->reveal());

        $result = $this->metadataFactory->getMetadataForClass(B::class);
        $this->assertEquals($metadataA->reveal(), $result);
    }
}
