<?php

	namespace Uneak\BlocksManagerBundle\Blocks;


	use Uneak\AssetsManagerBundle\Assets\AssetsComponentInterface;
	use Uneak\TemplatesManagerBundle\Templates\TemplatesManager;

	interface BlockTemplateInterface extends AssetsComponentInterface {
		public function render(\Twig_Environment $environment, TemplatesManager $templatesManager, array $options = array());
		public function getTemplate();
	}
