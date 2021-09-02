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

class ImportConstantsButton extends ToolbarButton
{

	protected $layout = 'joomla.toolbar.constants.import';

	public function fetchButton($type = 'ImportConstants', $name = '', $label = '', $files = [], $to_file = null)
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

		$layout = new FileLayout(strtolower($type));

		return $layout->render($options);
	}

}
