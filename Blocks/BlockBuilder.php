<?php

	namespace Uneak\BlocksManagerBundle\Blocks;

    use Uneak\AssetsManagerBundle\Assets\AssetsBuilderManager;
    use Uneak\AssetsManagerBundle\Assets\AssetsBuilder;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Uneak\TemplatesManagerBundle\Templates\TemplatesManager;

    class BlockBuilder extends AssetsBuilder {

        protected $templatesManager;
        protected $blockTemplatesManager;
        protected $blocksManager;
        protected $container;
        protected $blocks = array();


        public function __construct(TemplatesManager $templatesManager,BlocksManager $blocksManager, BlockTemplatesManager $blockTemplatesManager, ContainerInterface $container) {
            $this->templatesManager = $templatesManager;
            $this->blockTemplatesManager = $blockTemplatesManager;
            $this->container = $container;
            $this->blocksManager = $blocksManager;
        }


        public function addBlock($id, $block, $override = true) {
            if ($override || !isset($this->blocks[$id])) {
                $this->blocks[$id] = $block;
            }
            return $this;
        }

        public function setBlocks(array $blocks) {
            $this->blocks = $blocks;
            return $this;
        }

        public function getBlocks() {
            foreach ($this->blocks as $id => $block) {
                $block = $this->_blockResolver($block);
                $block->processBuildBlocks($this->blocksManager);
                $this->blocks[$id] = $block;

            }
            return $this->blocks;
        }

        public function getBlock($id) {
            preg_match("/([^\\/]*)(?:\\/(.*))?$/", $id, $matches);
            $id = $matches[1];
            $path = (isset($matches[2])) ? $matches[2] : null;

            if (!isset($this->blocks[$id])) {
                // TODO: exeption
                return null;
            }

            $block = $this->_blockResolver($this->blocks[$id]);
            if ($path) {
                $block = $this->_getPath($block, $path);
            }

            $block->processBuildBlocks($this->blocksManager);

            return $block;

        }

        private function _getPath(BlockInterface $block, $path) {
            preg_match("/([^\\/]*)(?:\\/(.*))?$/", $path, $matches);
            $id = $matches[1];
            $path = (isset($matches[2])) ? $matches[2] : null;

            if (null === $childBlock = $block->getBlock($id)) {
                // TODO: exeption
                return null;
            }
            $childBlock = $this->_blockResolver($childBlock);
            $childBlock->setParentBlock($block);

            if ($path) {
                return $this->_getPath($childBlock, $path);
            } else {
                return $childBlock;
            }
        }

        public function hasBlock($id) {
            return isset($this->blocks[$id]);
        }

        public function removeBlock($id) {
            unset($this->blocks[$id]);
            return $this;
        }



        public function render($id, array $parameters = array(), Response $response = null) {
//            return $this->templating->renderResponse('{{ renderBlock("'.$id.'",'.$parameters.') }}', $parameters, $response);
            return $this->container->get('templating')->renderResponse('{{ renderBlock("'.$id.'") }}', $parameters, $response);
        }



        //
        //
        public function processBuildAssets(AssetsBuilderManager $builder) {
            $blocks = $this->getBlocks();

            foreach ($blocks as $block) {
//                if (is_string($block)) {
//                    die($block);
//                }
                $this->_fetchAssets($builder, $block);
            }
        }

        private function _fetchAssets(AssetsBuilderManager $builder, BlockInterface $block) {
            if (!$block->isBlockTemplateBuilded()) {
                $blockTemplate = $this->blockTemplatesManager->getTemplate($block->getTemplateAlias());

                if (null !== $blockTemplate) {
                    $blockTemplate->buildAsset($builder, $block);
                }
                $block->refreshBlockTemplateBuilded(true);
            }

            $blocks = $block->getBlocks();
            foreach ($blocks as $block) {
//                if (is_string($block)) {
//                    die($block);
//                }
                $this->_fetchAssets($builder, $block);
            }
        }



        private function _blockResolver($block) {
            if (is_string($block)) {
                $block = $this->blocksManager->getBlock($block);
            }
            if (!$block instanceof Block) {
                // TODO: exeption
            }
            return $block;
        }

    }
