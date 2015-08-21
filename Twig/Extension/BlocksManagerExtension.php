<?php

	namespace Uneak\BlocksManagerBundle\Twig\Extension;

	use Twig_Extension;
	use Twig_Function_Method;
	use Uneak\BlocksManagerBundle\Blocks\BlockModel;
	use Uneak\BlocksManagerBundle\Blocks\BlockModelInterface;
	use Uneak\BlocksManagerBundle\Blocks\BlockTemplateManager;
	use Uneak\TemplatesManagerBundle\Templates\TemplatesManager;

	class BlocksManagerExtension extends Twig_Extension {

		private $twig;
		private $environment;
		private $blocksManager;
		private $templatesManager;
		private $blockTemplateManager;

		public function __construct(BlockModel $blocksManager, TemplatesManager $templatesManager, BlockTemplateManager $blockTemplateManager, $twig) {
			$this->blocksManager = $blocksManager;
			$this->templatesManager = $templatesManager;
			$this->blockTemplateManager = $blockTemplateManager;
			$this->twig = $twig;
		}

		public function initRuntime(\Twig_Environment $environment) {
			$this->environment = $environment;
		}

		public function getFunctions() {
			$options = array('pre_escape' => 'html', 'is_safe' => array('html'));

			return array(
				'hasBlock' => new Twig_Function_Method($this, 'hasBlockFunction'),
				'renderBlock' => new Twig_Function_Method($this, 'renderBlockFunction', $options),
				'renderBlockManager' => new Twig_Function_Method($this, 'renderBlockManagerFunction', $options),
			);
		}

		public function hasBlockFunction($block, $group = null) {
			return $this->blocksManager->hasBlock($block, $group);
		}

		public function renderBlockFunction($block, $group = null, $options = array()) {
			if (is_string($block)) {
				$block = $this->blocksManager->getBlock($block, $group);
			}

			if ($block && $block instanceof BlockModelInterface) {
				$blockTemplate = $this->blockTemplateManager->get($block->getBlockName());
				return $blockTemplate->render($this->environment, $this->templatesManager, array_merge($options, array('item' => $block)));
			}
		}

		public function renderBlockManagerFunction($group, $separator = "") {
			$htmls = array();
			$blocks = $this->blocksManager->getBlocks($group);
			if ($blocks) {
				foreach ($blocks as $block) {
					$htmls[] = $this->renderBlockFunction($block);
				}
			}

			$html = implode($separator, $htmls);

			return $html;
		}

		public function getName() {
			return 'uneak_blocksmanager';
		}


	}
