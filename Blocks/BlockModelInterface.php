<?php

	namespace Uneak\BlocksManagerBundle\Blocks;


	interface BlockModelInterface {
		public function getBlockName();
		public function addBlock(BlockModelInterface $block, $id = null, $priority = 0, $group = null);
		public function getBlocks($group = null);
		public function getBlock($id, $group = null);
		public function hasBlock($id, $group = null);
		public function removeBlock($id, $group = null);
		public function clearBlocks($group);
	}
