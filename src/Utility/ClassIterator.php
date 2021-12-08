<?php

declare(strict_types=1);

namespace Fregata\Utility;

use LogicException;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Finder\Finder;
use Traversable;

/** @implements \IteratorAggregate<string, ReflectionClass> */
class ClassIterator implements \IteratorAggregate
{
    public readonly array $classMap;

    public function __construct(Finder $finder)
    {
        $classMap = [];
        /** @var \Symfony\Component\Finder\SplFileInfo $fileInfo */
        foreach ($finder as $fileInfo) {
            $fileInfo = new SplFileInfo($fileInfo);
            try {
                foreach ($fileInfo->getDefinitionNames() as $name) {
                        $classMap[$name] = $fileInfo;
                }
            } catch (ReaderException) {
                //caused by none php files - nothing to handle
            }
        }
        $this->classMap = $classMap;
    }

    /**
     * @return Traversable<string, ReflectionClass>
     */
    public function getIterator(): Traversable
    {
        foreach ($this->classMap as $name => $fileInfo) {
            try {
                yield $name => new ReflectionClass($name);
            } catch (ReflectionException $e) {
                $msg = "Unable to iterate, {$e->getMessage()}, is autoloading enabled?";
                throw new LogicException($msg, 0, $e);
            }
        }
    }

    public function autoLoad(): void
    {
        new ClassLoader($this);
    }
}
