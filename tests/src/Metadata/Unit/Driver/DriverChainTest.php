<?php

namespace Pucene\Tests\Components\Metadata\Unit\Driver;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Pucene\Components\Metadata\ClassMetadata;
use Pucene\Components\Metadata\Driver\DriverChain;
use Pucene\Components\Metadata\Driver\DriverInterface;
use Pucene\Tests\Components\Metadata\Unit\A;
use Pucene\Tests\Components\Metadata\Unit\B;

class DriverChainTest extends TestCase
{
    public function testLoadMetadataForClass()
    {
        $driverA = $this->prophesize(DriverInterface::class);
        $driverB = $this->prophesize(DriverInterface::class);

        $metadata = $this->prophesize(ClassMetadata::class);
        $driverA->loadMetadataForClass(new \ReflectionClass(A::class))->willReturn($metadata->reveal());
        $driverB->loadMetadataForClass(Argument::any())->shouldNotBeCalled($metadata->reveal());

        $chain = new DriverChain([$driverA->reveal(), $driverB->reveal()]);

        $result = $chain->loadMetadataForClass(new \ReflectionClass(A::class));
        $this->assertEquals($metadata->reveal(), $result);
    }

    public function testGetAllClassNames()
    {
        $driverA = $this->prophesize(DriverInterface::class);
        $driverB = $this->prophesize(DriverInterface::class);

        $driverA->getAllClassNames()->willReturn([A::class]);
        $driverB->getAllClassNames()->willReturn([B::class]);

        $chain = new DriverChain([$driverA->reveal(), $driverB->reveal()]);

        $result = $chain->getAllClassNames();
        $this->assertEquals([A::class, B::class], $result);
    }
}
