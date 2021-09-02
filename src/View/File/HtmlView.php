<?php

namespace Saharin\Component\Translator\Administrator\View\File;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

class HtmlView extends BaseHtmlView
{

	protected $form;

	public function __construct(array $config = array())
	{
		parent::__construct($config);
		$this->setLayout('edit');
	}

	public function display($tpl = null)
	{
		$this->form  = $this->get('Form');
		$this->state = $this->get('State');
		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar()
	{
		ToolbarHelper::title(Text::_('COM_TRANSLATOR_ADD_FILE'), 'file-plus');
		ToolbarHelper::save('file.save', 'JTOOLBAR_APPLY');
		ToolbarHelper::cancel('file.cancel', 'JTOOLBAR_CANCEL');
	}

}