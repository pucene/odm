<?php

namespace Pucene\Tests\Components\Metadata\Unit\Cache;

use PHPUnit\Framework\TestCase;
use Pucene\Components\Metadata\Cache\FileCacheResource;

class FileCacheResourceTest extends TestCase
{
    public function testIsFresh()
    {
        $path = __DIR__ . '/FileCacheResourceTest.php';
        $resource = new FileCacheResource($path, new \DateTimeImmutable('@' . (filemtime($path) + 3)));

        $this->assertFalse($resource->isFresh());
    }

    public function testIsNotFresh()
    {
        $path = __DIR__ . '/FileCacheResourceTest.php';
        $resource = new FileCacheResource($path, new \DateTimeImmutable('@' . (filemtime($path) - 3)));

        $this->assertTrue($resource->isFresh());
    }
}
