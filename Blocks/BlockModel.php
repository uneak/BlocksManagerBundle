<?php

	namespace Uneak\BlocksManagerBundle\Blocks;

	class BlockModel implements BlockModelInterface {

		protected $blockModels = array();

		public function addBlock(BlockModelInterface $block, $id = null, $priority = 0, $group = null) {
			if (!$group) {
				$group = "__undefined";
			}

			if (!isset($this->blockModels[$group])) {
				$this->blockModels[$group] = array();
			}

			$groupData = array('block' => $block, 'priority' => $priority);

			if ($id) {
				$this->blockModels[$group][$id] = $groupData;
			} else {
				$this->blockModels[$group][] = $groupData;
			}
			uasort($this->blockModels[$group], array($this, "_cmp"));

			return $this;
		}


		private function _cmp($a, $b) {
			if ($a['priority'] == $b['priority']) {
				return 0;
			}
			return ($a['priority'] > $b['priority']) ? -1 : 1;
		}


		public function getBlocks($group = null) {
			if (!$group) {
				$group = "__undefined";
			}

			if (isset($this->blockModels[$group])) {
				$array = array();
				foreach ($this->blockModels[$group] as $key => $block) {
					$array[$key] = $block['block'];
				}
				return $array;
			}

			return null;
		}

		public function getBlock($id, $group = null) {
			if (!$group) {
				$group = "__undefined";
			}

			if (isset($this->blockModels[$group][$id])) {
				return $this->blockModels[$group][$id]['block'];
			}

			return null;
		}

		public function hasBlock($id, $group = null) {
			if (!$group) {
				$group = "__undefined";
			}

			return isset($this->blockModels[$group][$id]);
		}

		public function removeBlock($id, $group = null) {
			if (!$group) {
				$group = "__undefined";
			}

			if (isset($this->blockModels[$group][$id])) {
				unset($this->blockModels[$group][$id]);
			}

			return $this;
		}

		public function clearBlocks($group) {
			if (isset($this->blockModels[$group])) {
				unset($this->blockModels[$group]);
			}

			return $this;
		}


		public function getBlockName() {
			return "block_model_manager";
		}
	}
