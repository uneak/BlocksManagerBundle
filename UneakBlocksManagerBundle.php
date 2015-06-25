<?php

namespace Uneak\BlocksManagerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Uneak\BlocksManagerBundle\DependencyInjection\Compiler\BlocksCompilerPass;

class UneakBlocksManagerBundle extends Bundle {

	public function build(ContainerBuilder $container) {
		parent::build($container);
		$container->addCompilerPass(new BlocksCompilerPass());
	}

}
