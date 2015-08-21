<?php

	namespace Uneak\BlocksManagerBundle\Blocks;

	class Meta {

		protected $data;
		protected $parent;

		public function __construct($parent, $data = array()) {
			$this->parent = $parent;
			$this->_init($data);
		}

		public function __get($name) {
			if (array_key_exists($name, $this->data)) {
				return $this->data[$name];
			}
			return null;
		}

		public function __set($name, $value) {
			$this->data[$name] = $value;
			return $this->parent;
		}

		public function __isset($name) {
			return isset($this->data[$name]);
		}

		public function __unset($name) {
			unset($this->data[$name]);
			return $this->parent;
		}

		public function _init($data) {
			$this->data = $data;
		}

		public function _merge($data) {
			$this->data = array_merge($this->data, $data);
		}

		public function _getArray() {
			return $this->data;
		}


		public function _getJsArray(array $array = null) {
			$array = (is_null($array)) ? $this->data : $array;
			$returnArray = array();
			foreach ($array as $key => $value) {
				if (!is_null($value)) {
					$returnArray[$key] = $value;
				}
			}

			$json = json_encode($returnArray);
			$json = preg_replace_callback("/(?:\"|')##(.*?)##(?:\"|')/", function ($matches) {
				return stripslashes($matches[1]);
			}, $json);
			return $json;
		}

		public function __toString() {
			return $this->_getJsArray();
		}

	}
