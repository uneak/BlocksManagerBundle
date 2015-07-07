<?php

	namespace Uneak\BlocksManagerBundle\Blocks;

	use Uneak\AssetsManagerBundle\Assets\AssetsComponentInterface;

	interface BlockInterface extends AssetsComponentInterface {
		public function getMetas();
		public function getTemplate();
		public function setTemplate($template);
		public function getTitle();
		public function setTitle($title);
		public function preRender();
	}
