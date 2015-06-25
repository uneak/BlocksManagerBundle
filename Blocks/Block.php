<?php

	namespace Uneak\BlocksManagerBundle\Blocks;

	use Uneak\AssetsManagerBundle\Assets\AssetsContainer;

	abstract class Block extends AssetsContainer implements BlockInterface {

		protected $title;
		protected $template;
		protected $metas;
		protected $blocks = array();


		public function __construct() {
			$this->metas = new Meta($this);
		}

		public function preRender() {
		}

		public function getMetas() {
			return $this->metas;
		}

		public function getMeta($key) {
			return $this->metas->__get($key);
		}

		public function setMeta($key, $value) {
			$this->metas->__set($key, $value);

			return $this;
		}

		public function getTemplate() {
			return $this->template;
		}

		public function setTemplate($template) {
			$this->template = $template;
			return $this;
		}

		public function getTitle() {
			return $this->title;
		}

		public function setTitle($title) {
			$this->title = $title;
			return $this;
		}


		public function addBlock(BlockInterface $block, $id = null, $priority = 0, $group = null) {
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
			uasort($this->blocks[$group], array($this, "_cmp"));

			$this->addAssetsContainer($block);
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
				$this->removeAssetsContainer($this->blocks[$group][$id]['block']);
				unset($this->blocks[$group][$id]);
			}

			return $this;
		}

		public function clearBlocks($group) {
			if (isset($this->blocks[$group])) {
				foreach ($this->blocks[$group] as $blockArray) {
					$this->removeAssetsContainer($blockArray['block']);
				}
				unset($this->blocks[$group]);
			}

			return $this;
		}

	}
