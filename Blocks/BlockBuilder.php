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
//        protected $blocksBuilded = true;


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
            $blockModel = $this->_blockResolver($block);

            return $blockModel;
        }

        public function setBlocks(array $blocks) {
            $this->blocks = $blocks;
        }

        public function getBlocks() {
            $blocks = array();
            foreach ($this->blocks as $id => $blockModel) {
                $blocks[$id] = $this->_blockResolver($blockModel);
            }
            return $this->blocks;
        }

        public function getBlock($id) {
            preg_match("/([^\\/]*)(?:\\/(.*))?$/", $id, $matches);
            $id = $matches[1];
            $path = (isset($matches[2])) ? $matches[2] : null;

            // ID : "hello"
            if (!isset($this->blocks[$id])) {
                // TODO: exeption
                return null;
            }
            $blockModel = $this->_blockResolver($this->blocks[$id]);
            if ($path) {
                $blockModel = $this->_getPath($blockModel, $path);
            }

            $blockModel->processBuildBlocks($this->blocksManager);

            return $blockModel;

        }

        private function _getPath(BlockModelInterface $blockModel, $path) {
            preg_match("/([^\\/]*)(?:\\/(.*))?$/", $path, $matches);
            $id = $matches[1];
            $path = (isset($matches[2])) ? $matches[2] : null;

            preg_match("/^([^:]*)(?::(.*))?$/", $id, $matches);
            $id = $matches[1];
            $group = (isset($matches[2])) ? $matches[2] : null;

            if (null === $blockModel = $blockModel->getBlock($id, $group)) {
                // TODO: exeption
                return null;
            }
            $blockModel = $this->_blockResolver($blockModel);

            if ($path) {
                return $this->_getPath($blockModel, $path);
            } else {
                return $blockModel;
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


        public function processBuildAssets(AssetsBuilderManager $builder) {
            foreach ($this->blocks as $blockModel) {
                $this->_fetchAssets($builder, $blockModel);
            }
            $this->assetsBuilded = true;
        }

        private function _fetchAssets(AssetsBuilderManager $builder, $blockModel) {


            $blockModel = $this->_blockResolver($blockModel);

            $blockTemplate = $this->blockTemplatesManager->getTemplate($blockModel->getTemplateAlias());

            if (null !== $blockTemplate) {
                $blockTemplate->buildAsset($builder, $blockModel);
            }

            $blocks = $blockModel->getBlocks();
            if (null !== $blocks) {
                foreach ($blocks as $block) {
                    $this->_fetchAssets($builder, $block);
                }
            }
        }



        private function _blockResolver($blockModel) {
            if (is_string($blockModel)) {
                $blockModel = $this->blocksManager->getBlock($blockModel);
            }
            if (!$blockModel instanceof BlockModel) {
                // TODO: exeption
            }
            return $blockModel;
        }

    }
