<?php

namespace Fregata\Utility;

class ClassLoader
{
    /** @var array<SplFileInfo> */
    private $classMap;

    public function __construct(ClassIterator $classIterator)
    {
        $this->classMap = $classIterator->classMap;
        $this->register();
    }

    public function register()
    {
        return spl_autoload_register([$this, 'load']);
    }

    public function load(string $classname): void
    {
        if (isset($this->classMap[$classname])) {
            require $this->classMap[$classname]->getRealPath();
        }
    }
}
