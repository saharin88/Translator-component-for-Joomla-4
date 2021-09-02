<?php

namespace Saharin\Component\Translator\Administrator\View\Constant;

defined('_JEXEC') or die;

use Joomla\CMS\Application\AdministratorApplication;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\WebAsset\WebAssetAttachBehaviorInterface;

class HtmlView extends BaseHtmlView
{

	protected $form;

	protected $item;

	/**
	 * @var CMSObject
	 * @since version
	 */
	protected $state;

	public function __construct(array $config = array())
	{
		parent::__construct($config);
		$this->setLayout('edit');
	}

	public function display($tpl = null)
	{
		$this->item  = $this->get('Item');
		$this->form  = $this->get('Form');
		$this->state = $this->get('State');
		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar()
	{

		ToolbarHelper::title(Text::_((empty($this->state->get('row')) ? 'COM_TRANSLATOR_CONSTANT_ADD' : 'COM_TRANSLATOR_CONSTANT_EDIT')), 'edit');

		/** @var AdministratorApplication $app */
		$app = Factory::getApplication();
		$doc = $app->getDocument();
		$wa  = $doc->getWebAssetManager();
		$wa->useScript('form.validate');

		ToolbarHelper::apply('constant.apply');
		ToolbarHelper::save('constant.save');
		ToolBarHelper::cancel('constant.cancel', 'JTOOLBAR_CANCEL');

		$this->sidebar = \JHtmlSidebar::render();
	}

}