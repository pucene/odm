<?php

namespace Pucene\Components\Metadata;

interface MetadataFactoryInterface
{
    /**
     * Returns the gathered metadata for the given class name.
     *
     * If the drivers return instances of MergeableClassMetadata, these will be
     * merged prior to returning. Otherwise, all metadata for the inheritance
     * hierarchy will be returned as ClassHierarchyMetadata unmerged.
     *
     * If no metadata is available, null is returned.
     */
    public function getMetadataForClass(string $className): ?ClassMetadataInterface;

    public function getAllClassNames(): array;
}
