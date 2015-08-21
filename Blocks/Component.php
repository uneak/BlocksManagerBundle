<?php

	namespace Uneak\BlocksManagerBundle\Blocks;


	class Component extends BlockModel {

		protected $uniqid;

		public function __construct() {
			$this->uniqid = uniqid('comp_');
			parent::__construct();
		}

		public function getUniqid() {
			return $this->uniqid;
		}

		public function getJsArray($array = null) {
			return $this->getMetas()->_getJsArray($array);
		}

	}
