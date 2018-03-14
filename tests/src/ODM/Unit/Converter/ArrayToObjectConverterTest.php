<?php

namespace Pucene\Tests\Components\ODM\Unit\Metadata\Driver;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Pucene\Components\Metadata\ClassMetadataInterface;
use Pucene\Components\Metadata\PropertyMetadataInterface;
use Pucene\Components\ODM\Converter\ArrayToObjectConverter;
use Pucene\Tests\AppBundle\Document\ArticleDocument;

class ArrayToObjectConverterTest extends TestCase
{
    public function testConvert()
    {
        $converter = new ArrayToObjectConverter();

        $metadata = $this->prophesize(ClassMetadataInterface::class);
        $metadata->getName(ArticleDocument::class);

        $property = $this->prophesize(PropertyMetadataInterface::class);
        $metadata->getProperties()->willReturn([$property->reveal()]);

        $object = $this->prophesize(ArticleDocument::class);
        $metadata->createInstance()->willReturn($object->reveal())->shouldBeCalled();

        $property->getName()->willReturn('title');
        $property->setValue($object->reveal(), 'test')->shouldBeCalled();

        $result = $converter->convert(['title' => 'test'], $metadata->reveal());

        $this->assertEquals($object->reveal(), $result);
    }

    public function testConvertNoDataForProperty()
    {
        $converter = new ArrayToObjectConverter();

        $metadata = $this->prophesize(ClassMetadataInterface::class);
        $metadata->getName(ArticleDocument::class);

        $property = $this->prophesize(PropertyMetadataInterface::class);
        $metadata->getProperties()->willReturn([$property->reveal()]);

        $object = $this->prophesize(ArticleDocument::class);
        $metadata->createInstance()->willReturn($object->reveal())->shouldBeCalled();

        $property->getName()->willReturn('title');
        $property->setValue(Argument::cetera())->shouldNotBeCalled();

        $result = $converter->convert([], $metadata->reveal());

        $this->assertEquals($object->reveal(), $result);
    }
}
