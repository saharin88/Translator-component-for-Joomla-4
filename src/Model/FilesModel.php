<?php

namespace Saharin\Component\Translator\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\
{Application\AdministratorApplication, Filesystem\Folder, MVC\Model\ListModel, Factory, Language\LanguageHelper
};
use  Saharin\Component\Translator\Administrator\Helper\TranslatorHelper;

class FilesModel extends ListModel
{

	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'client',
				'language',
			);
		}

		parent::__construct($config);
	}

	public function getFiles()
	{
		$path = constant('JPATH_' . strtoupper($this->getState('filter.client', 'site'))) . '/language/' . $this->getState('filter.language', Factory::getLanguage()->getTag());

		$args = array(
			$path,
			'.ini',
			false,
			false,
			array('.svn', 'CVS', '.DS_Store', '__MACOSX')
		);

		$filters = array('^\..*', '.*~');

		if (TranslatorHelper::getParam('show_sys', '1') === '0')
		{
			$filters[] = '.sys';
		}

		if (TranslatorHelper::getParam('show_self', '1') === '0')
		{
			$filters[] = '.com_translator';
		}

		$args[] = $filters;

		$files = Folder::files(...$args);

		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			$filter_files = [];

			foreach ($files AS $file)
			{
				if (mb_stristr($file, $search, false, 'UTF-8') !== false)
				{
					$filter_files[] = $file;
				}
			}

			return $filter_files;
		}

		return $files;

	}

	protected function populateState($ordering = 'a.element', $direction = 'asc')
	{

		/** @var AdministratorApplication $app */
		$app = Factory::getApplication();

		$client = $this->getUserStateFromRequest($this->context . '.filter.client', 'filter_client', 'site');
		$this->setState('filter.client', $client);

		$language = $this->getUserStateFromRequest($this->context . '.filter.language', 'filter_language', $app->getLanguage()->getTag());
		$this->setState('filter.language', $language);

		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search', '');
		$this->setState('filter.search', $search);

		$this->setState('filter.compare', 0);

		$formSubmited = $app->input->post->get('form_submited');

		if (!empty($formSubmited))
		{
			$filters = $app->input->post->get('filter', [], 'array');
			foreach ($filters as $key => $val)
			{
				$this->setState('filter.' . $key, $val);
				$app->setUserState($this->context . '.filter.' . $key, $val);
			}

		}
	}

	protected function loadFormData()
	{
		$data = parent::loadFormData();

		// Pre-fill the filter options
		if (!property_exists($data, 'filter'))
		{
			$data->filter = array(
				'client'   => $this->getState('filter.client'),
				'language' => $this->getState('filter.language'),
				'search'   => $this->getState('filter.search'),
				'compare'  => $this->getState('filter.compare'),
			);
		}

		return $data;
	}


}