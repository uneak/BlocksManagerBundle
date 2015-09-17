<?php

	namespace Uneak\BlocksManagerBundle\Blocks;



	use Symfony\Component\DependencyInjection\ContainerAware;

    class BlockTemplatesManager extends ContainerAware {

		protected $templates = array();

		public function __construct() {
		}

        public function addTemplate($id, $template, $override = true) {
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

            if (!isset($this->templates[$id])) {
                // TODO: execption
                return null;
            }
            if (is_string($this->templates[$id])) {
                $this->templates[$id] = $this->container->get($this->templates[$id]);
            }

            return $this->templates[$id];
        }

        public function hasTemplate($id) {
            return isset($this->templates[$id]);
        }

        public function removeTemplate($id) {
            unset($this->templates[$id]);
            return $this;
        }

	}
