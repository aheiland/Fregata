<?php

declare(strict_types=1);

namespace Fregata\Utility;

use Stringable;

class Name implements Stringable
{
    public readonly string $normalized;

    public function __construct(
        public readonly string $name
    ) {
        $this->normalized = self::normalize($name);
    }

    private static function normalize(string $name): string
    {
        return preg_replace('/^\\\*/', '', $name);
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
