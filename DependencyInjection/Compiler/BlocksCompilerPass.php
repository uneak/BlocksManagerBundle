<?php

namespace Uneak\BlocksManagerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class BlocksCompilerPass implements CompilerPassInterface {

    public function process(ContainerBuilder $container) {
        if ($container->hasDefinition('uneak.blocksmanager') === false) {
            return;
        }
        $definition = $container->getDefinition('uneak.blocksmanager');
        $taggedServices = $container->findTaggedServiceIds('uneak.blocksmanager.block');
		
        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $definition->addMethodCall(
					'addBlock', array(new Reference($id), $attributes['id'], $attributes['priority'], $attributes['group'])
                );
            }
        }

    }

}
