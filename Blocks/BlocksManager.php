<?php

	namespace Uneak\BlocksManagerBundle\Blocks;

    use Uneak\AssetsManagerBundle\Assets\AssetsBuilderInterface;
    use Uneak\AssetsManagerBundle\Assets\AssetsBuilderManager;

    class BlocksManager extends BlockModel implements AssetsBuilderInterface {

        protected $blockTemplateManager;
        protected $assetsBuilded = false;

        public function __construct(BlockTemplateManager $blockTemplateManager) {
            $this->blockTemplateManager = $blockTemplateManager;
        }

        public function buildAsset(AssetsBuilderManager $builder, $parameters) {
        }

        public function processBuildAssets(AssetsBuilderManager $builder) {
            $this->fetch($builder, $this);
            $this->assetsBuilded = true;
        }

        protected function fetch(AssetsBuilderManager $builder, BlockModel $blockModel) {

            $blockTemplate = $this->blockTemplateManager->get($blockModel->getBlockName());
            if (null !== $blockTemplate) {
                $blockTemplate->buildAsset($builder, $blockModel);
            }

            $blocks = $blockModel->getBlocks();
            if (null !== $blocks) {
                foreach ($blocks as $block) {
                    $this->fetch($builder, $block);
                }
            }

        }

        public function isAssetsBuilded() {
            return $this->assetsBuilded;
        }
    }
