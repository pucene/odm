<?php

namespace Pucene\Tests\Components\Metadata\Unit\Driver;

use PHPUnit\Framework\TestCase;
use Pucene\Components\Metadata\ClassMetadata;
use Pucene\Components\Metadata\Driver\FileDriver;
use Pucene\Components\Metadata\Driver\FileLocatorInterface;
use Pucene\Tests\Components\Metadata\Unit\A;

class FileDriverTest extends TestCase
{
    public function testLoadMetadataForClass()
    {
        $fileLocator = $this->prophesize(FileLocatorInterface::class);
        $fileLocator->findFileForClass(new \ReflectionClass(A::class), 'php')->willReturn('/A.php');

        $metadata = $this->prophesize(ClassMetadata::class);

        $driver = $this->getMockForAbstractClass(FileDriver::class, [$fileLocator->reveal()]);
        $driver->expects($this->any())
            ->method('loadMetadataFromFile')
            ->with(new \ReflectionClass(A::class), '/A.php')
            ->willReturn($metadata->reveal());
        $driver->expects($this->any())
            ->method('getExtension')
            ->willReturn('php');

        $this->assertEquals($metadata->reveal(), $driver->loadMetadataForClass(new \ReflectionClass(A::class)));
    }

    public function testGetAllClassNames()
    {
        $fileLocator = $this->prophesize(FileLocatorInterface::class);
        $fileLocator->findAllClasses('php')->willReturn([A::class]);

        $driver = $this->getMockForAbstractClass(FileDriver::class, [$fileLocator->reveal()]);
        $driver->expects($this->any())
            ->method('getExtension')
            ->willReturn('php');

        $this->assertEquals([A::class], $driver->getAllClassNames());
    }
}
