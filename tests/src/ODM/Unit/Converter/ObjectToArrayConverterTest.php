<?php

namespace ODM\Unit\Converter;

use PHPUnit\Framework\TestCase;
use Pucene\Components\Metadata\ClassMetadataInterface;
use Pucene\Components\Metadata\PropertyMetadataInterface;
use Pucene\Components\ODM\Converter\ObjectToArrayConverter;
use Pucene\Tests\AppBundle\Document\ArticleDocument;

class ObjectToArrayConverterTest extends TestCase
{
    public function testConvert()
    {
        $metadata = $this->prophesize(ClassMetadataInterface::class);
        $metadata->getName(ArticleDocument::class);

        $property = $this->prophesize(PropertyMetadataInterface::class);
        $metadata->getProperties()->willReturn([$property->reveal()]);

        $object = $this->prophesize(ArticleDocument::class);

        $property->getName()->willReturn('title');
        $property->getValue($object->reveal())->willReturn('test')->shouldBeCalled();

        $converter = new ObjectToArrayConverter();

        $this->assertEquals(['title' => 'test'], $converter->convert($object->reveal(), $metadata->reveal()));
    }
}
