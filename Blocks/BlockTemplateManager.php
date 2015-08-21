<?php

	namespace Uneak\BlocksManagerBundle\Blocks;



	class BlockTemplateManager {

		protected $blockTemplates = array();

		public function __construct() {
		}

		public function set($id, BlockTemplateInterface $blockTemplate) {
			$this->blockTemplates[$id] = $blockTemplate;
			return $this;
		}

		public function get($id) {
			return (isset($this->blockTemplates[$id])) ? $this->blockTemplates[$id] : null;
		}

		public function has($id) {
			return isset($this->blockTemplates[$id]);
		}

		public function remove($id) {
			unset($this->blockTemplates[$id]);
			return $this;
		}

	}
