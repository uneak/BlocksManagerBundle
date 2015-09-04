<?php

	namespace Uneak\BlocksManagerBundle\Blocks;


	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Uneak\AssetsManagerBundle\Assets\AssetsBuilderInterface;
	use Uneak\TemplatesManagerBundle\Templates\TemplatesManager;

	interface BlockTemplateInterface extends AssetsBuilderInterface {
		public function configureOptions(OptionsResolver $resolver);
		public function buildOptions(TemplatesManager $templatesManager, $block, array &$options);
		public function getRenderTemplate();
	}
