<?php

	namespace Uneak\BlocksManagerBundle\Blocks;


	use Uneak\AssetsManagerBundle\Assets\AssetsBuilderInterface;
	use Uneak\TemplatesManagerBundle\Templates\TemplatesManager;

	interface BlockTemplateInterface extends AssetsBuilderInterface {
		public function render(\Twig_Environment $environment, TemplatesManager $templatesManager, array $options = array());
		public function getTemplate();
	}
