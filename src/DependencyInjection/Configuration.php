<?php

namespace Fregata\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('fregata');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('migrations')
                ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->variableNode('options')->end()
                            ->scalarNode('parent')->end()
                            ->scalarNode('migrators_directory')->end()
                            ->arrayNode('migrators')
                                ->scalarPrototype()->end()
                            ->end()
                            ->arrayNode('tasks')
                                ->children()
                                    ->arrayNode('before')
                                        ->scalarPrototype()->end()
                                    ->end()
                                    ->arrayNode('after')
                                        ->scalarPrototype()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
