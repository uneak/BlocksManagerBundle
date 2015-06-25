<?php

	namespace Uneak\BlocksManagerBundle\Blocks;

	use Uneak\AssetsManagerBundle\Assets\AssetsContainerInterface;

	interface BlockInterface extends AssetsContainerInterface {
		public function getMetas();

		public function getTemplate();

		public function setTemplate($template);

		public function getTitle();

		public function setTitle($title);

		public function preRender();
	}
