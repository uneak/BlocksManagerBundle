<?php

	namespace Uneak\BlocksManagerBundle\Blocks;


	interface BlockInterface {

		public function isTemplateDirty();
		public function setTemplateDirty($templateDirty);

		public function debugBuild();

		public function processBuildBlocks(BlocksManager $blocksManager);
		public function postBuild(BlocksManager $blocksManager);

		public function getTemplateAlias();
		public function setTemplateAlias($blockTemplateAlias);

		public function getParentBlock();
		public function setParentBlock(BlockInterface $parentBlock);

		public function addBlock($block, $id = null, $priority = null);
		public function getBlocks();
		public function getBlock($id);
		public function hasBlock($id);
		public function removeBlock($id);

		public function debug();
	}