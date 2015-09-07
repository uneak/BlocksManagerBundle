<?php

	namespace Uneak\BlocksManagerBundle\Blocks;



    class BlocksManager {

        protected $blocks = array();

        public function __construct() {
        }

        public function addBlock($id, BlockModelInterface $block, $override = true) {
            if ($override || !isset($this->blocks[$id])) {
                $this->blocks[$id] = $block;
            }
            return $this;
        }

        public function setBlocks(array $blocks) {
            $this->blocks = $blocks;
        }

        public function getBlocks() {
            return $this->blocks;
        }

        public function getBlock($id) {
            return (isset($this->blocks[$id])) ? $this->blocks[$id] : null;
        }

        public function hasBlock($id) {
            return isset($this->blocks[$id]);
        }

        public function removeBlock($id) {
            unset($this->blocks[$id]);
            return $this;
        }

    }
