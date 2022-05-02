<?php

namespace Saharin\Component\Translator\Administrator\View\Constants;

defined('_JEXEC') or die;

use Joomla\CMS\Application\AdministratorApplication;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Object\CMSObject;
use Saharin\Component\Translator\Administrator\Html\Toolbar\Button\PayPalButton;
use Saharin\Component\Translator\Administrator\Model\ConstantsModel;
use Joomla\CMS\Router\Route;
use Saharin\Component\Translator\Administrator\Html\Toolbar\Button\ExportConstantsButton;
use Joomla\CMS\Toolbar\Button\CustomButton;


class HtmlView extends BaseHtmlView
{
	public $activeFilters;

	/**
	 * @var CMSObject
	 * @since version
	 */
	public $state;

	public $filterForm;

	public $googleForm;

	public $to_file;

	public $items;

	public function display($tpl = null)
	{
		/** @var AdministratorApplication $app */
		$app         = Factory::getApplication();
		$this->items = $this->get('Items');
		$this->state = $this->get('State');

		switch ($this->getLayout())
		{
			case 'default':

				$this->filterForm    = $this->get('FilterForm');
				$this->activeFilters = $this->get('ActiveFilters');
				$this->googleForm    = $this->get('GoogleForm');
				break;

			case 'export':

				$this->to_file = $app->input->get('to_file', null, 'raw');

				if (empty($this->to_file))
				{
					throw new \Exception('Empty file to import');
				}

				if (!empty($this->items))
				{
					/** @var ConstantsModel $model */
					$model      = $this->getModel();
					$diff_items = $model->getItems($this->to_file);
					if (!empty($diff_items))
					{
						$this->items = array_diff_key($this->items, $diff_items);
					}
				}

				break;

			default;

				throw new \Exception('Unknown layout');
		}


		$this->addToolbar();

		parent::display($tpl);
	}

	protected function addToolbar()
	{

		/** @var AdministratorApplication $app */
		$app = Factory::getApplication();
		$doc = $app->getDocument();

		ToolbarHelper::title(Text::sprintf('COM_TRANSLATOR_LANGUAGE_FILE_S', str_replace(':', '/', $this->state->get('file'))), 'file-2');

		if ($this->getLayout() === 'default')
		{
			$bar = Toolbar::getInstance('toolbar');

			ToolbarHelper::link(Route::_('index.php?option=com_translator&view=files', false), Text::_('COM_TRANSLATOR_BACK_TO_LIST'), 'arrow-left-3');

			ToolbarHelper::addNew('constant.add', 'COM_TRANSLATOR_NEW_CONSTANT');

			/* google translate button */
			$googleTranslate = new CustomButton('google');
			$layout          = new FileLayout('joomla.toolbar.translate.google');
			$googleTranslate->html($layout->render());
			$bar->appendButton($googleTranslate);

			ToolbarHelper::deleteList('', 'constants.delete');
			$files = $this->get('OtherLangs');
			if (count($files))
			{
				$btn = new ExportConstantsButton('export', '', [
					'name'    => 'export',
					'label'   => Text::_('COM_TRANSLATOR_EXPORT_CONSTANTS'),
					'files'   => $files,
					'to_file' => $this->state->get('file', $app->input->get('file', null, 'raw'))
				]);
				$bar->appendButton($btn);
			}

			$payPalDonateBtn = new PayPalButton();
			$bar->appendButton($payPalDonateBtn);

		}
		else
		{
			ToolbarHelper::custom('constant.export', 'out', '', 'COM_TRANSLATOR_EXPORT');
		}
	}
}
