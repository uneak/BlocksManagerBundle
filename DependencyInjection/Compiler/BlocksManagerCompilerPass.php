<?php

namespace Uneak\BlocksManagerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class BlocksManagerCompilerPass implements CompilerPassInterface {

    public function process(ContainerBuilder $container) {

        $definition = $container->getDefinition('uneak.blocksmanager.blocks');
        $taggedServices = $container->findTaggedServiceIds('uneak.blocksmanager.block');

        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $override = (isset($attributes['override'])) ? $attributes['override'] : null;
                if (is_null($override)) {
                    $definition->addMethodCall('addBlock', array($attributes['alias'], new Reference($id)));
                } else {
                    $definition->addMethodCall('addBlock', array($attributes['alias'], new Reference($id), $override));
                }
            }
        }

    }

}
