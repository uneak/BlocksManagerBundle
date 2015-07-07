<?php

	namespace Uneak\BlocksManagerBundle\Twig\Extension;

	use Twig_Extension;
	use Twig_Function_Method;
	use Uneak\BlocksManagerBundle\Blocks\BlockInterface;
	use Uneak\BlocksManagerBundle\Blocks\Block;

	class BlocksManagerExtension extends Twig_Extension {

		private $twig;
		private $environment;
		private $blocksManager;

		public function __construct(Block $blocksManager, $twig) {
			$this->blocksManager = $blocksManager;
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

		public function renderBlockFunction($block, $group = null, $parameters = array()) {
			if (is_string($block)) {
				$block = $this->blocksManager->getBlock($block, $group);
			}

			if ($block && $block instanceof BlockInterface) {
				$block->preRender();
				$parameters = array_merge($parameters, array('item' => $block));
				return $this->environment->render($block->getTemplate(), $parameters);
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
