<?php

	namespace Uneak\BlocksManagerBundle\Twig\Extension;

	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Twig_Extension;
	use Twig_Function_Method;
	use Uneak\BlocksManagerBundle\Blocks\BlockModelInterface;
    use Uneak\BlocksManagerBundle\Blocks\BlocksManager;
    use Uneak\BlocksManagerBundle\Blocks\BlockTemplateManager;
	use Uneak\TemplatesManagerBundle\Templates\TemplatesManager;

	class BlocksManagerExtension extends Twig_Extension {

		private $twig;
		private $environment;
		private $blocksManager;
		private $templatesManager;
		private $blockTemplateManager;

		public function __construct(BlocksManager $blocksManager, TemplatesManager $templatesManager, BlockTemplateManager $blockTemplateManager, $twig) {
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

		public function renderBlockFunction($block, $options = null, $template = null) {

			if ($block instanceOf BlockModelInterface || is_string($block) || (is_array($block) && isset($block[0]))) {
				// is block

				if (is_string($block)) {
					$blockName = $block;
					$groupName = null;
					$blockObject = $this->blocksManager->getBlock($blockName, $groupName);

				} else if (is_array($block)) {
					$blockName = (isset($block[0]) && is_string($block[0])) ? $block[0] : null;
					$groupName = (isset($block[1]) && is_string($block[1])) ? $block[1] : null;
					$blockObject = $this->blocksManager->getBlock($blockName, $groupName);

				} else {
					$blockObject = $block;

				}

				if ($blockObject === null) {
					// TODO: trow Exception
					return '[ERREUR] no block found with '.$blockName.' -> group:'.$groupName;
				}

				if (is_string($options)) {
					$template = $options;
					$options = array();
				} else {
					$options = (is_null($options)) ? array() : $options;
				}

				$template = (is_null($template)) ? $blockObject->getTemplateName() : $template;

			} else {
				// is options

				$template = $options;
				$options = $block;
				$blockObject = null;

				if ($template === null) {
					// TODO: trow Exception
					return '[ERREUR] no template defined';
				}
			}

			return $this->_renderBlock($blockObject, $options, $template);

		}

		public function renderBlockManagerFunction($group, $separator = "") {
			$htmls = array();
			$blocks = $this->blocksManager->getBlocks($group);
			if ($blocks) {
				foreach ($blocks as $block) {
					$htmls[] = $this->_renderBlock($block, array(), $block->getTemplateName());
				}
			}

			$html = implode($separator, $htmls);

			return $html;
		}

		private function _renderBlock($blockObject, array $options, $template) {

			if (null === $blockTemplate = $this->blockTemplateManager->get($template)) {
				// TODO: trow Exception
				return '[ERREUR] no block template found for '.$template;
			}

			$resolver = new OptionsResolver();
			$blockTemplate->configureOptions($resolver);
			$options = $resolver->resolve($options);


			$blockTemplate->buildOptions($this->templatesManager, $blockObject, $options);

			$renderTemplate = (isset($options['template'])) ? $options['template'] : $blockTemplate->getRenderTemplate();
			$renderTemplate = ($this->templatesManager->has($renderTemplate)) ? $this->templatesManager->get($renderTemplate) : $renderTemplate;
			return $this->environment->render($renderTemplate, $options);
		}

		public function getName() {
			return 'uneak_blocksmanager';
		}


	}
