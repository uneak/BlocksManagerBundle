<?php

	namespace Uneak\BlocksManagerBundle\Twig\Extension;

	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Twig_Extension;
	use Twig_Function_Method;
    use Uneak\BlocksManagerBundle\Blocks\BlockBuilder;
    use Uneak\BlocksManagerBundle\Blocks\BlockModelInterface;
    use Uneak\BlocksManagerBundle\Blocks\BlockTemplatesManager;
	use Uneak\TemplatesManagerBundle\Templates\TemplatesManager;

	class BlocksManagerExtension extends Twig_Extension {

		private $twig;
		private $environment;
		private $blockBuilder;
		private $templatesManager;
		private $blockTemplatesManager;

		public function __construct(BlockBuilder $blockBuilder, BlockTemplatesManager $blockTemplatesManager, TemplatesManager $templatesManager, $twig) {
			$this->blockBuilder = $blockBuilder;
			$this->templatesManager = $templatesManager;
			$this->blockTemplatesManager = $blockTemplatesManager;
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
			return $this->blockBuilder->hasBlock($block, $group);
		}

		public function renderBlockFunction($block, $options = null, $template = null) {

			if ($block instanceOf BlockModelInterface || is_string($block) || (is_array($block) && isset($block[0]))) {
				// is block

				if (is_string($block)) {
					$blockName = $block;
					$groupName = null;
					$blockObject = $this->blockBuilder->getBlock($blockName);

				} else if (is_array($block)) {
					$blockName = (isset($block[0]) && is_string($block[0])) ? $block[0] : null;
					$groupName = (isset($block[1]) && is_string($block[1])) ? $block[1] : null;
					$blockObject = $this->blockBuilder->getBlock($blockName, $groupName);

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

				$template = (is_null($template)) ? $blockObject->getTemplateAlias() : $template;

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
			$blocks = $this->blockBuilder->getBlocks($group);
			if ($blocks) {
				foreach ($blocks as $block) {
					$htmls[] = $this->_renderBlock($block, array(), $block->getTemplateAlias());
				}
			}

			$html = implode($separator, $htmls);

			return $html;
		}

		private function _renderBlock($blockObject, array $options, $template) {

			if (null === $blockTemplate = $this->blockTemplatesManager->getTemplate($template)) {
				// TODO: trow Exception
				return '[ERREUR] no block template found for '.$template;
			}

			$resolver = new OptionsResolver();
			$blockTemplate->configureOptions($resolver);
			$options = $resolver->resolve($options);


			$blockTemplate->buildOptions($this->templatesManager, $blockObject, $options);

			$renderTemplate = (isset($options['template'])) ? $options['template'] : $blockTemplate->getRenderTemplate();
			$renderTemplate = ($this->templatesManager->hasTemplate($renderTemplate)) ? $this->templatesManager->getTemplate($renderTemplate) : $renderTemplate;
			return $this->environment->render($renderTemplate, $options);
		}

		public function getName() {
			return 'uneak_blocksmanager';
		}


	}
