<?php

	namespace Uneak\BlocksManagerBundle\Blocks;


	use Symfony\Component\OptionsResolver\OptionsResolver;
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

		public function configureOptions(OptionsResolver $resolver) {
		}

		public function buildOptions(TemplatesManager $templatesManager, $block, array &$options) {
			$options = array_merge($options, array('item' => $block));
		}

		public function isAssetsBuilded() {
			return $this->assetsBuilded;
		}

		public function getRenderTemplate() {
			return "block_template_abstract";
		}
	}
