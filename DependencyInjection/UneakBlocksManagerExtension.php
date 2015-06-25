<?php

	namespace Uneak\BlocksManagerBundle\DependencyInjection;

	use Symfony\Component\Config\Definition\Processor;
	use Symfony\Component\DependencyInjection\ContainerBuilder;
	use Symfony\Component\Config\FileLocator;
	use Symfony\Component\HttpKernel\DependencyInjection\Extension;
	use Symfony\Component\DependencyInjection\Loader;

	/**
	 * This is the class that loads and manages your bundle configuration
	 *
	 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
	 */
	class UneakBlocksManagerExtension extends Extension {

		/**
		 * {@inheritdoc}
		 */
		public function load(array $configs, ContainerBuilder $container) {

//			$processor = new Processor();
//			$configuration = new Configuration();

//			$config = $processor->processConfiguration($configuration, $configs);
//			$container->setParameter('uneak.assets.manager.root_path', $config['root_path']);

			$loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
			$loader->load('services.yml');
		}

	}
