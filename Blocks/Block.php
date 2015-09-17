<?php

	namespace Uneak\BlocksManagerBundle\Blocks;

	class Block implements BlockInterface {

		private $_idString = "/^([^:]*)(?::(.*))?$/";
		protected $blocks = array();
		protected $parentBlock = null;
		protected $blocksBuilded = true;
		protected $templateBuilded = false;
		protected $blockTemplateBuilded = false;
		protected $templateAlias = "block_model_manager";
		protected $priority = 1000;

		/**
		 * @return boolean
		 */
		public function isBlocksBuilded() {
			return $this->blocksBuilded;
		}

		/**
		 * @return boolean
		 */
		public function isBlockTemplateBuilded() {
			return $this->blockTemplateBuilded;
		}

		public function refreshBlockTemplateBuilded($templateBuilded = null) {
			if (!is_null($templateBuilded)) {
				$this->templateBuilded = $templateBuilded;
			} else {
				$templateBuilded = $this->templateBuilded;
			}

			if ($templateBuilded) {
				$blocks = $this->getBlocks();
				foreach ($blocks as $block) {
					if (is_string($block) || !$block->isBlockTemplateBuilded()) {
						$templateBuilded = false;
						break;
					}
				}
			}

			if ($this->blockTemplateBuilded != $templateBuilded) {
				$this->blockTemplateBuilded = $templateBuilded;
				if ($this->parentBlock) {
					$this->parentBlock->refreshBlockTemplateBuilded();
				}
			}



		}

		public function setTemplateBuilded($templateBuilded) {

			if ($this->templateBuilded != $templateBuilded) {
				$this->templateBuilded = $templateBuilded;
				if ($this->isBlockTemplateBuilded() && $this->parentBlock) {
					$this->parentBlock->refreshChildTemplateBuilded();
				}
			}

		}


		public function refreshBlocksBuilded() {
			$blocksBuilded = true;

			$blocks = $this->getBlocks();
			foreach ($blocks as $block) {
				if (is_string($block)) {
					$blocksBuilded = false;
					break;
				}
			}

			if ($this->blocksBuilded != $blocksBuilded) {
				$this->blocksBuilded = $blocksBuilded;
				if ($this->parentBlock) {
					$this->parentBlock->refreshBlocksBuilded();
				}
			}
		}


		/**
		 * @return null
		 */
		public function getParentBlock() {
			return $this->parentBlock;
		}

		/**
		 * @param null $parentBlock
		 */
		public function setParentBlock(BlockInterface $parentBlock) {
			$this->parentBlock = $parentBlock;
		}


		public function addBlock($block, $id = null, $priority = null) {
			if ($id) {
				preg_match($this->_idString, $id, $matches);
				$id = (isset($matches[1]) && $matches[1]) ? $matches[1] : null;
				$group = (isset($matches[2]) && $matches[2]) ? $matches[2] : "__undefined";
			} else {
				$group = "__undefined";
			}

			if (!isset($this->blocks[$group])) {
				$this->blocks[$group] = array();
			}

			$blockData = array();
			$blockData['priority'] = (is_null($priority)) ? $this->priority-- : $priority;
			if (is_array($block)) {
				$blockData['block'] = $block[0];
				if (count($block) >= 2) {
					$blockData['template'] = $block[1];
				}
			} else {
				$blockData['block'] = $block;
			}

			if ($id) {
				$this->blocks[$group][$id] = $blockData;
			} else {
				$this->blocks[$group][] = $blockData;
			}

			uasort($this->blocks[$group], array($this, "_cmp"));

			if ($block instanceof Block) {
				$block->setParentBlock($this);
			} else {
				$this->refreshBlocksBuilded();
			}

			return $this;
		}


		public function getBlocks() {

			$array = array();
			foreach ($this->blocks as $group => $blocks) {
				foreach ($blocks as $key => $block) {
					$array[] = $block['block'];
				}
			}

			return $array;
		}

		public function getBlock($id) {
			if ($id) {
				preg_match($this->_idString, $id, $matches);
				$id = (isset($matches[1]) && $matches[1]) ? $matches[1] : null;
				$group = (isset($matches[2]) && $matches[2]) ? $matches[2] : "__undefined";
			} else {
				$group = "__undefined";
			}


			if ($id) {
				if (isset($this->blocks[$group][$id])) {
					return $this->blocks[$group][$id]['block'];
				}

				return null;
			} else {
				$array = array();
				if (isset($this->blocks[$group])) {
					foreach ($this->blocks[$group] as $key => $block) {
						$array[] = $block['block'];
					}
				}

				return $array;
			}

		}

		public function hasBlock($id) {
			if ($id) {
				preg_match($this->_idString, $id, $matches);
				$id = (isset($matches[1]) && $matches[1]) ? $matches[1] : null;
				$group = (isset($matches[2]) && $matches[2]) ? $matches[2] : "__undefined";
			} else {
				$group = "__undefined";
			}

			return isset($this->blocks[$group][$id]);
		}

		public function removeBlock($id) {
			if ($id) {
				preg_match($this->_idString, $id, $matches);
				$id = (isset($matches[1]) && $matches[1]) ? $matches[1] : null;
				$group = (isset($matches[2]) && $matches[2]) ? $matches[2] : "__undefined";
			} else {
				$group = "__undefined";
			}


			if ($id) {
				if (isset($this->blocks[$group][$id])) {
					unset($this->blocks[$group][$id]);
				}
			} else {
				if (isset($this->blocks[$group])) {
					unset($this->blocks[$group]);
				}
			}

			return $this;
		}


		public function processBuildBlocks(BlocksManager $blocksManager) {
			if ($this->isBlocksBuilded() == true) {
				return;
			}

			foreach ($this->blocks as $group => $blocksData) {
//                uasort($this->blocks[$group], array($this, "_cmp"));
				foreach ($blocksData as $id => $blockData) {

					if (is_string($blockData['block'])) {
						$block = $blocksManager->getBlock($blockData['block']);
						$block->setParentBlock($this);
					} else {
						$block = $blockData['block'];
					}

					if (!$block instanceof BlockInterface) {
						throw new \Exception('block '.$id.' not instance of BlockInterface');
					}

					if (isset($blockData['template'])) {
						$block->setTemplateAlias($blockData['template']);
						unset($this->blocks[$group][$id]['template']);
					}

					$this->blocks[$group][$id]['block'] = $block;

					$block->processBuildBlocks($blocksManager);
				}
			}

			$this->refreshBlocksBuilded();
		}

		private function _cmp($a, $b) {
			if ($a['priority'] == $b['priority']) {
				return 0;
			}

			return ($a['priority'] > $b['priority']) ? -1 : 1;
		}

		public function getTemplateAlias() {
			return $this->templateAlias;
		}

		public function setTemplateAlias($blockTemplateAlias) {
			$this->templateAlias = $blockTemplateAlias;
			$this->refreshBlockTemplateBuilded(false);
			return $this;
		}


	}
