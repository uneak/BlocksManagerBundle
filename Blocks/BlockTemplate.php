<?php

	namespace Uneak\BlocksManagerBundle\Blocks;


	use Uneak\AssetsManagerBundle\Assets\AssetBuilder;
	use Uneak\TemplatesManagerBundle\Templates\TemplatesManager;

	class BlockTemplate implements BlockTemplateInterface {

		public function buildAsset(AssetBuilder $builder, $parameters) {
		}

		public function processBuildAssets(AssetBuilder $builder) {
			$this->buildAsset($builder, $this);
		}

		public function getTemplate() {
			return "block_template_abstract";
		}

		public function render(\Twig_Environment $environment, TemplatesManager $templatesManager, array $options = array()) {
			$template = (isset($options['template'])) ? $options['template'] : $this->getTemplate();
			$template = ($templatesManager->has($template)) ? $templatesManager->get($template) : $template;
			return $environment->render($template, $options);
		}

	}
