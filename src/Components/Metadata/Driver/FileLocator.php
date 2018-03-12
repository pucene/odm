<?php

namespace Pucene\Components\Metadata\Driver;

class FileLocator implements FileLocatorInterface
{
    /**
     * @var array
     */
    private $dirs;

    /**
     * @param string[] $directories
     */
    public function __construct(array $directories)
    {
        $this->dirs = $directories;
    }

    public function findFileForClass(\ReflectionClass $class, string $extension): ?string
    {
        foreach ($this->dirs as $prefix => $dir) {
            if ('' !== $prefix && 0 !== strpos($class->getNamespaceName(), $prefix)) {
                continue;
            }

            $len = '' === $prefix ? 0 : strlen($prefix) + 1;
            $path = $dir . '/' . str_replace('\\', '.', substr($class->name, $len)) . '.' . $extension;
            if (file_exists($path)) {
                return $path;
            }
        }

        return null;
    }

    public function findAllClasses(string $extension): array
    {
        $classes = [];
        foreach ($this->dirs as $prefix => $dir) {
            $directoryIterator = new \RecursiveDirectoryIterator($dir);
            $iterator = new \RecursiveIteratorIterator($directoryIterator, \RecursiveIteratorIterator::LEAVES_ONLY);

            $namespacePrefix = '' !== $prefix ? $prefix . '\\' : '';
            foreach ($iterator as $file) {
                $fileName = $file->getBasename('.' . $extension);
                if ($fileName === $file->getBasename()) {
                    continue;
                }

                $classes[] = $namespacePrefix . str_replace('.', '\\', $fileName);
            }
        }

        return $classes;
    }
}
