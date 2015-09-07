<?php

	namespace Uneak\BlocksManagerBundle\Blocks;

	class BlockModel implements BlockModelInterface {

		protected $blocks = array();
        protected $blocksBuilded = true;
		protected $templateAlias = "block_model_manager";

		public function addBlock($block, $id = null, $priority = 0, $group = null) {
			if (!$group) {
				$group = "__undefined";
			}

			if (!isset($this->blocks[$group])) {
				$this->blocks[$group] = array();
			}

			$groupData = array('block' => $block, 'priority' => $priority);

			if ($id) {
				$this->blocks[$group][$id] = $groupData;
			} else {
				$this->blocks[$group][] = $groupData;
			}

            if (is_string($block)) {
                $this->blocksBuilded = false;
            }

			return $this;
		}

		public function getBlocks($group = null) {
			if (!$group) {
				$group = "__undefined";
			}

			if (isset($this->blocks[$group])) {
				$array = array();
				foreach ($this->blocks[$group] as $key => $block) {
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

			if (isset($this->blocks[$group][$id])) {
				return $this->blocks[$group][$id]['block'];
			}

			return null;
		}

		public function hasBlock($id, $group = null) {
			if (!$group) {
				$group = "__undefined";
			}

			return isset($this->blocks[$group][$id]);
		}

		public function removeBlock($id, $group = null) {
			if (!$group) {
				$group = "__undefined";
			}

			if (isset($this->blocks[$group][$id])) {
				unset($this->blocks[$group][$id]);
			}

			return $this;
		}

		public function clearBlocks($group) {
			if (isset($this->blocks[$group])) {
				unset($this->blocks[$group]);
			}

			return $this;
		}

        public function processBuildBlocks(BlocksManager $blocksManager) {
            if ($this->blocksBuilded == true) {
//                return;
            }

            foreach ($this->blocks as $group => $blocks) {
                uasort($this->blocks[$group], array($this, "_cmp"));
                foreach ($blocks as $id => $block) {
                    $blockModel = (is_string($block['block'])) ? $blocksManager->getBlock($block['block']) : $block['block'];
                    if (!$blockModel instanceof BlockModel) {
                        // TODO: exeption
                    }
                    $this->blocks[$group][$id]['block'] = $blockModel;
                    $blockModel->processBuildBlocks($blocksManager);
                }
            }

            $this->blocksBuilded = true;
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
            return $this;
        }


    }
