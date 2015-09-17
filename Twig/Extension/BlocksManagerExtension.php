<?php

	namespace Uneak\BlocksManagerBundle\Twig\Extension;

	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Twig_Extension;
	use Twig_Function_Method;
    use Uneak\BlocksManagerBundle\Blocks\BlockBuilder;
    use Uneak\BlocksManagerBundle\Blocks\BlockInterface;
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

		public function renderBlockFunction($block, array $options = array(), $template = null) {


			if (is_string($block)) {
				$blockObject = $this->blockBuilder->getBlock($block);
			} else {
				$blockObject = $block;
			}

			if ($blockObject === null) {
				// TODO: trow Exception
				return '[ERREUR] no block found'.$block;
			}

			$template = (is_null($template)) ? $blockObject->getTemplateAlias() : $template;

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
