<?php

	namespace Uneak\BlocksManagerBundle\Twig\Extension;

	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Twig_Extension;
	use Twig_Function_Method;
    use Uneak\BlocksManagerBundle\Blocks\BlockBuilder;
    use Uneak\BlocksManagerBundle\Blocks\BlockInterface;
    use Uneak\BlocksManagerBundle\Blocks\BlockTemplatesManager;
	use Uneak\TemplatesManagerBundle\Templates\TemplatesManager;

	class JsArrayExtension extends Twig_Extension {

		public function getFunctions() {
			$options = array('pre_escape' => 'html', 'is_safe' => array('html'));
			return array(
				'jsArray' => new Twig_Function_Method($this, 'jsArrayFunction', $options),
			);
		}

		public function jsArrayFunction($array) {

			if (is_array($array)) {
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

			} else {

				return $array;

			}


		}

		public function getName() {
			return 'uneak_js_array';
		}


	}
