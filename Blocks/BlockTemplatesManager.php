<?php

	namespace Uneak\BlocksManagerBundle\Blocks;



	class BlockTemplatesManager {

		protected $templates = array();

		public function __construct() {
		}

        public function addTemplate($id, BlockTemplateInterface $template, $override = true) {
            if ($override || !isset($this->templates[$id])) {
                $this->templates[$id] = $template;
            }
            return $this;
        }

        public function setTemplates(array $templates) {
            $this->templates = $templates;
        }

        public function getTemplates() {
            return $this->templates;
        }

        public function getTemplate($id) {
            return (isset($this->templates[$id])) ? $this->templates[$id] : null;
        }

        public function hasTemplate($id) {
            return isset($this->templates[$id]);
        }

        public function removeTemplate($id) {
            unset($this->templates[$id]);
            return $this;
        }

	}
