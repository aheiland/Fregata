<?php

declare(strict_types=1);

namespace Fregata\Utility;

use LogicException;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Finder\Finder;

/** @implements \IteratorAggregate<string, ReflectionClass> */
class ClassIterator implements \IteratorAggregate
{
    public readonly array $classMap;

    public function __construct(Finder $finder)
    {
        /** @var \Symfony\Component\Finder\SplFileInfo $fileInfo */
        foreach ($finder as $fileInfo) {
            $fileInfo = new SplFileInfo($fileInfo);
            foreach ($fileInfo->getDefinitionNames() as $name) {
                $this->classMap[$name] = $fileInfo;
            }
        }
    }

    /**
     * @return iterable<string, ReflectionClass>
     */
    public function getIterator(): iterable
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
