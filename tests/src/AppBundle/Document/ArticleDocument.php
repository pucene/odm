<?php

namespace Pucene\Tests\AppBundle\Document;

use Pucene\Components\ODM\Annotation\Document;
use Pucene\Components\ODM\Annotation\Id;
use Pucene\Components\ODM\Annotation\Property;

/**
 * @Document("article")
 */
class ArticleDocument
{
    /**
     * @var string
     *
     * @Id
     */
    private $id;

    /**
     * @var string
     *
     * @Property("string")
     */
    private $title;

    public function __construct(string $title, ?string $id)
    {
        $this->title = $title;
        $this->id = $id;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
