<?php

namespace Pucene\Tests\Components\Metadata\Unit\Cache;

use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use Pucene\Components\Metadata\Cache\Cache;
use Pucene\Components\Metadata\Cache\CacheResource;
use Pucene\Components\Metadata\ClassMetadata;
use Pucene\Tests\Components\Metadata\Unit\A;

class CacheTest extends TestCase
{
    public function testFetch()
    {
        $resource = $this->prophesize(CacheResource::class);
        $resource->isFresh()->willReturn(true);

        $metadata = new ClassMetadata(A::class);
        $metadata->addResource($resource->reveal());

        $psrCache = $this->prophesize(CacheInterface::class);
        $psrCache->has(A::class)->willReturn(true);
        $psrCache->get(A::class)->willReturn(serialize($metadata));

        $cache = new Cache($psrCache->reveal());

        $result = $cache->fetch(A::class);

        $this->assertEquals($metadata->getName(), $result->getName());
    }

    public function testFetchNotFresh()
    {
        $resource = $this->prophesize(CacheResource::class);
        $resource->isFresh()->willReturn(false);

        $metadata = new ClassMetadata(A::class);
        $metadata->addResource($resource->reveal());

        $psrCache = $this->prophesize(CacheInterface::class);
        $psrCache->has(A::class)->willReturn(true);
        $psrCache->get(A::class)->willReturn(serialize($metadata));

        $cache = new Cache($psrCache->reveal());

        $result = $cache->fetch(A::class);

        $this->assertNull($result);
    }

    public function testFetchNotExists()
    {
        $psrCache = $this->prophesize(CacheInterface::class);
        $psrCache->has(A::class)->willReturn(false);

        $cache = new Cache($psrCache->reveal());

        $result = $cache->fetch(A::class);

        $this->assertNull($result);
    }

    public function testSave()
    {
        $metadata = new ClassMetadata(A::class);

        $psrCache = $this->prophesize(CacheInterface::class);
        $psrCache->set(A::class, serialize($metadata));

        $cache = new Cache($psrCache->reveal());

        $result = $cache->save(A::class, $metadata);

        $this->assertEquals($metadata, $result);
    }
}
