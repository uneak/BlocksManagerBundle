<?php

namespace Uneak\BlocksManagerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Uneak\BlocksManagerBundle\DependencyInjection\Compiler\BlocksManagerCompilerPass;
use Uneak\BlocksManagerBundle\DependencyInjection\Compiler\BlockTemplatesManagerCompilerPass;

class UneakBlocksManagerBundle extends Bundle {

	public function build(ContainerBuilder $container) {
		parent::build($container);
		$container->addCompilerPass(new BlockTemplatesManagerCompilerPass());
		$container->addCompilerPass(new BlocksManagerCompilerPass());
	}

}
