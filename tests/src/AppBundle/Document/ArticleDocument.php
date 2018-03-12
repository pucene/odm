<?php

namespace Pucene\Tests\AppBundle\Document;

use Pucene\Components\ODM\Annotation\Document;
use Pucene\Components\ODM\Annotation\Property;

/**
 * @Document("article")
 */
class ArticleDocument
{
    /**
     * @var string
     *
     * @Property("string")
     */
    private $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}
