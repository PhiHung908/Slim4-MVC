<?php

namespace hSlim\jui;


class Widget extends \hSlim\Base\Widget
{
	/**
	  * Abstract {inherited}
	  **/
	protected function registerClientOptions($name, $id = null)
	{
		$options = empty($this->options['clientOptions']) ? '' : Json::htmlEncode($this->options['clientOptions']);
		$js = "jQuery('#$id').$name($options);";
		//$this->getView()->registerJs($js);
	}

	protected function registerClientEvents($name, $id = null)
	{
	}
    
}