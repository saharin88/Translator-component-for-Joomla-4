<?php


namespace Saharin\Component\Translator\Administrator\Html\Toolbar\Button;


use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Toolbar\ToolbarButton;

class PayPalButton extends ToolbarButton
{

	protected $layout = 'joomla.toolbar.donate.paypal';

	public function fetchButton()
	{
		$layout = new FileLayout($this->layout);

		return $layout->render();
	}
}