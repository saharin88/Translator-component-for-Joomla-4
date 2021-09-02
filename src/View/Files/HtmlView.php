<?php

namespace Saharin\Component\Translator\Administrator\View\Files;

defined('_JEXEC') or die;

use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\Registry\Registry;
use Saharin\Component\Translator\Administrator\Model\FilesModel;

class HtmlView extends BaseHtmlView
{

	/**
	 * The search tools form
	 *
	 * @var    Form
	 * @since  1.6
	 */
	public $filterForm;

	/**
	 * The active search filters
	 *
	 * @var    array
	 * @since  1.6
	 */
	public $activeFilters = [];

	/**
	 * An array of files
	 *
	 * @var    array
	 * @since  1.6
	 */
	public $files;

	/**
	 * The model state
	 *
	 * @var    Registry
	 * @since  1.6
	 */
	public $state;

	public function display($tpl = null)
	{

		/** @var FilesModel $model */
		$model               = $this->getModel();
		$this->files         = $model->getFiles();
		$this->state         = $model->getState();
		$this->filterForm    = $model->getFilterForm();
		$this->activeFilters = $model->getActiveFilters();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
		}

		$this->addToolbar();

		parent::display($tpl);
	}

	/**
	 * Adds the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   2.5
	 */
	protected function addToolbar()
	{
		ToolbarHelper::title(Text::_('COM_TRANSLATOR_LANGUAGE_FILES'), 'copy');
		ToolbarHelper::addNew('file.add');
		ToolbarHelper::preferences('com_translator');
	}
}
