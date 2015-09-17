<?php

	namespace Uneak\BlocksManagerBundle\Blocks;


    use Symfony\Component\DependencyInjection\ContainerAware;

    class BlocksManager extends ContainerAware {

        protected $blocks = array();

        public function addBlock($id, $block, $override = true) {
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
            if (!isset($this->blocks[$id])) {
                // TODO: execption
                return null;
            }
            if (is_string($this->blocks[$id])) {
                $this->blocks[$id] = $this->container->get($this->blocks[$id]);
            }

            return $this->blocks[$id];
        }

        public function hasBlock($id) {
            return isset($this->blocks[$id]);
        }

        public function removeBlock($id) {
            unset($this->blocks[$id]);
            return $this;
        }

    }
