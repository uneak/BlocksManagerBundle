<?php

	namespace Uneak\BlocksManagerBundle\Blocks;

	use Uneak\AssetsManagerBundle\Assets\AssetBuilder;
    use Uneak\AssetsManagerBundle\Assets\AssetsComponentInterface;
    use Uneak\AssetsManagerBundle\Assets\AssetsComponentNested;

    class BlocksManager extends BlockModel implements AssetsComponentInterface {

        protected $blockTemplateManager;

        public function __construct(BlockTemplateManager $blockTemplateManager) {
            $this->blockTemplateManager = $blockTemplateManager;
        }

        public function buildAsset(AssetBuilder $builder, $parameters) {
        }

        public function processBuildAssets(AssetBuilder $builder) {
            $this->fetch($builder, $this);
        }

        protected function fetch(AssetBuilder $builder, BlockModel $blockModel) {

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

	}
