<?php

	namespace Uneak\BlocksManagerBundle\Blocks;


	use Uneak\AssetsManagerBundle\Assets\AssetsBuilderManager;
	use Uneak\TemplatesManagerBundle\Templates\TemplatesManager;

	class BlockTemplate implements BlockTemplateInterface {

		protected $assetsBuilded = false;

		public function buildAsset(AssetsBuilderManager $builder, $parameters) {
		}

		public function processBuildAssets(AssetsBuilderManager $builder) {
			$this->buildAsset($builder, $this);
			$this->assetsBuilded = true;
		}

		public function getTemplate() {
			return "block_template_abstract";
		}

		public function render(\Twig_Environment $environment, TemplatesManager $templatesManager, array $options = array()) {
			$template = (isset($options['template'])) ? $options['template'] : $this->getTemplate();
			$template = ($templatesManager->has($template)) ? $templatesManager->get($template) : $template;
			return $environment->render($template, $options);
		}

		public function isAssetsBuilded() {
			return $this->assetsBuilded;
		}
	}
