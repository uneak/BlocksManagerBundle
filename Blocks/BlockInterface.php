<?php

	namespace Uneak\BlocksManagerBundle\Blocks;

	use Uneak\AssetsManagerBundle\Assets\AssetsBuilderInterface;
	use Uneak\TemplatesManagerBundle\Templates\TemplatesManager;

	interface BlockInterface extends AssetsBuilderInterface {
		public function getMetas();
		public function getTemplate();
		public function setTemplate($template);
		public function getTitle();
		public function setTitle($title);
		public function render(\Twig_Environment $environment, TemplatesManager $templatesManager, array $options = array());
	}
