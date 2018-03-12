<?php

namespace Pucene\Tests\Components\Metadata\Unit;

use PHPUnit\Framework\TestCase;
use Pucene\Components\Metadata\ClassMetadata;
use Pucene\Components\PropertyMetadata;

class PropertyMetadataTest extends TestCase
{
    public function testGetClass()
    {
        $class = $this->prophesize(ClassMetadata::class);
        $class->getName()->willReturn(A::class);

        $metadata = new PropertyMetadata($class->reveal(), 'propertyA');

        $this->assertEquals($class->reveal(), $metadata->getClass());
    }

    public function testGetName()
    {
        $class = $this->prophesize(ClassMetadata::class);
        $class->getName()->willReturn(A::class);

        $metadata = new PropertyMetadata($class->reveal(), 'propertyA');

        $this->assertEquals('propertyA', $metadata->getName());
    }

    public function testGetValue()
    {
        $class = $this->prophesize(ClassMetadata::class);
        $class->getName()->willReturn(A::class);

        $metadata = new PropertyMetadata($class->reveal(), 'propertyA');

        $aObject = new A();
        $aObject->propertyA = 'test';

        $this->assertEquals('test', $metadata->getValue($aObject));
    }

    public function testSetValue()
    {
        $class = $this->prophesize(ClassMetadata::class);
        $class->getName()->willReturn(A::class);

        $metadata = new PropertyMetadata($class->reveal(), 'propertyA');

        $aObject = new A();
        $metadata->setValue($aObject, 'test');

        $this->assertEquals('test', $aObject->propertyA);
    }
}
