<?php

namespace Pucene\Components\Metadata;

interface PropertyMetadataInterface
{
    public function getClass(): ClassMetadataInterface;

    public function getName(): string;
}
