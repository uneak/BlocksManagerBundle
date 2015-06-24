<?php

	namespace Uneak\BlocksManagerBundle\Blocks;

	class BlocksManager extends Block {

		public function __construct(AssetsManager $assetsManager) {
			parent::__construct();
			$assetsManager->addAssetsDependency($this);
		}

	}
