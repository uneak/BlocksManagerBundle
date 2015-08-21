<?php

namespace Uneak\BlocksManagerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class BlocksCompilerPass implements CompilerPassInterface {

    public function process(ContainerBuilder $container) {
        if ($container->hasDefinition('uneak.blocksmanager') === false || $container->hasDefinition('uneak.blocksmanager.templatemanager') === false) {
            return;
        }

        $templateManagerDefinition = $container->getDefinition('uneak.blocksmanager.templatemanager');
        $templateManagerTaggedServices = $container->findTaggedServiceIds('uneak.blocksmanager.template');

        $blockManagerDefinition = $container->getDefinition('uneak.blocksmanager');
        $BlockManagerTaggedServices = $container->findTaggedServiceIds('uneak.blocksmanager.block');

		
        foreach ($BlockManagerTaggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $blockManagerDefinition->addMethodCall(
					'addBlock', array(new Reference($id), $attributes['id'], $attributes['priority'], $attributes['group'])
                );
            }
        }

        foreach ($templateManagerTaggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $templateManagerDefinition->addMethodCall(
                    'set', array($attributes['model'], new Reference($id))
                );
            }
        }


    }

}
