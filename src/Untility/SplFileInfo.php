<?php

declare(strict_types=1);

namespace Fregata\Untility;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\ParserFactory;
use Symfony\Component\Finder\SplFileInfo as FinderSplFileInfo;

class SplFileInfo extends FinderSplFileInfo
{
    public function __construct(FinderSplFileInfo $decorated)
    {
        parent::__construct($decorated->getFilename(), $decorated->getRelativePath(), $decorated->getRelativePathname());
    }

    /** @return string[] */
    private function getDefinitions(array $stmts, string $namespace = ''): array
    {
        $names = [];

        foreach ($stmts as $stmt) {
            if ($stmt instanceof Namespace_) {
                array_push($names, ...($this->getDefinitions($stmt->stmts, (string)$stmt->name)));
            } elseif ($stmt instanceof Class_ || $stmt instanceof Interface_ || $stmt instanceof Trait_) {
                $names[] = $this->normalize("{$namespace}\\{$stmt->name}");
            }
        }

        return $names;
    }

    private function normalize(string $name): string
    {
        return preg_replace('/^\\\*/', '', $name);
    }

    /** ®return string[] */
    public function getDefinitionNames(): array
    {
        $parserFactory = new ParserFactory();
        $parser = $parserFactory->create(ParserFactory::PREFER_PHP5);
        $stmts = $parser->parse($this->getContents());
        return $this->getDefinitions($stmts);
    }
}
