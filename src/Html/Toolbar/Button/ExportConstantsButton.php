<?php

namespace Saharin\Component\Translator\Administrator\Html\Toolbar\Button;

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\
{
	Language\Text,
	Layout\FileLayout,
	Toolbar\ToolbarButton,
};
use Saharin\Component\Translator\Administrator\Helper\TranslatorHelper;

class ExportConstantsButton extends ToolbarButton
{

	protected $layout = 'joomla.toolbar.constants.export';

	public function fetchButton($name = '', $label = '', $files = [], $to_file = null)
	{
		if (empty($to_file) || file_exists(TranslatorHelper::getPath($to_file)) === false)
		{
			return null;
		}

		$options = [
			'name'    => $name,
			'label'   => Text::_($label),
			'files'   => $files,
			'to_file' => $to_file
		];

		$layout = new FileLayout($this->layout);

		return $layout->render($options);
	}

}
