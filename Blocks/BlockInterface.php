<?php

	namespace Uneak\BlocksManagerBundle\Blocks;


	interface BlockInterface {
        public function processBuildBlocks(BlocksManager $blocksManager);
		public function getTemplateAlias();
        public function setTemplateAlias($blockTemplateAlias);

		public function addBlock($block, $id = null, $priority = null);
        public function getBlocks();
		public function getBlock($id);
		public function hasBlock($id);
		public function removeBlock($id);

        public function isBlocksBuilded();
        public function refreshBlocksBuilded();

	}
