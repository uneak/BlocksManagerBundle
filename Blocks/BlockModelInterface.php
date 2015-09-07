<?php

	namespace Uneak\BlocksManagerBundle\Blocks;


	interface BlockModelInterface {
        public function processBuildBlocks(BlocksManager $blocksManager);
		public function getTemplateAlias();
        public function setTemplateAlias($blockTemplateAlias);

		public function addBlock($block, $id = null, $priority = 0, $group = null);
		public function getBlocks($group = null);
		public function getBlock($id, $group = null);
		public function hasBlock($id, $group = null);
		public function removeBlock($id, $group = null);
		public function clearBlocks($group);
	}
