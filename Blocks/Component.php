<?php

	namespace Uneak\BlocksManagerBundle\Blocks;


	class Component extends Block {

		protected $uniqid;

		public function __construct() {
			$this->uniqid = uniqid('comp_');
			parent::__construct();
		}

		public function getUniqid() {
			return $this->uniqid;
		}

		public function getJsArray($array = null) {

			if (is_null($array)) {
				$array = $this->getMetas()->_getArray();
			}

			return $this->_jsJson($array);
		}


		protected function _jsJson($array) {
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

	}
