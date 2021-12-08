<?php

declare(strict_types=1);

namespace Fregata\Tests\Utility;

use Fregata\Utility\Name;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    public function testToString(): void
    {
        $name = new Name('some\name\class');

        self::assertEquals($name->name, (string)$name);
    }

    public function testName(): void
    {
        $name = new Name('Some\Name\Space\Class');

        self::assertEquals('Some\Name\Space\Class', $name->name);
        self::assertEquals('Some\Name\Space\Class', $name->normalized);
    }

    public function testNormalized(): void
    {
        $name = new Name('\Some\Name\Space\Class');

        self::assertEquals('\Some\Name\Space\Class', $name->name);
        self::assertEquals('Some\Name\Space\Class', $name->normalized);
    }

}
