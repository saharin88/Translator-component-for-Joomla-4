<?php

namespace Saharin\Component\Translator\Administrator\Helper;

defined('_JEXEC') or die;

require_once(__DIR__ . '/vendor/autoload.php');

use \Dejurin\GoogleTranslateForFree;
use Joomla\CMS\
{
	Factory,
	Component\ComponentHelper,
	Language\LanguageHelper,
	Language\Text,
};

class TranslatorHelper
{

	protected static $constants = [];

	protected static $path = [];

	/**
	 * @param   string    $source
	 * @param   string    $target
	 * @param   string    $text
	 * @param   int|null  $attempts
	 *
	 * @return array|string
	 *
	 * @since version
	 */
	public static function translateByGoogle(string $source, string $target, string $text, ?int $attempts = 5)
	{
		$tr = new GoogleTranslateForFree();

		return $tr->translate($source, $target, $text, $attempts);
	}

	/**
	 * @param   string  $name
	 * @param   null    $default
	 *
	 * @return array|mixed
	 *
	 * @since version
	 */
	public static function getParam(string $name, $default = null)
	{
		$params = self::getParams();
		if (is_array($name))
		{
			$arrparams = array();
			foreach ($name as $n)
			{
				$arrparams[$n] = $params->get($n);
			}

			return $arrparams;
		}
		else
		{
			return $params->get($name, $default);
		}

	}

	public static function getParams()
	{
		return ComponentHelper::getParams('com_translator');
	}

	/**
	 * Get extension name
	 *
	 * @param   string  $file
	 *
	 * @return mixed
	 *
	 * @since version
	 */
	public static function getExtension(string $file)
	{
		$fileExpl = explode(':', $file);

		if (count($fileExpl) === 3)
		{
			$filename = $fileExpl[2];
		}
		else
		{
			$filename = $fileExpl[0];
		}

		$filename = str_replace('.sys', '', $filename);
		$filename = str_replace('.ini', '', $filename);

		$fileExpl = explode('.', $filename);

		if (empty($fileExpl[1]))
		{
			return $fileExpl[0];
		}
		else
		{
			return $fileExpl[1];
		}
	}

	/**
	 * Get path by file key
	 *
	 * @param   string|null  $file  site:en-GB:com_exemple.ini
	 *
	 * @return mixed
	 *
	 * @throws \Exception
	 * @since version
	 */
	public static function getPath(string $file)
	{
		if (empty($file))
		{
			throw new \Exception(Text::sprintf('COM_TRANSLATOR_ERROR_GET_PATH', Text::_('COM_TRANSLATOR_MISSING_FILE')));
		}

		if (isset(self::$path[$file]) === false)
		{
			$fileExpl = explode(':', $file);

			if ($fileExpl[0] !== 'administrator' && $fileExpl[0] !== 'site')
			{
				throw new \Exception(Text::sprintf('COM_TRANSLATOR_ERROR_GET_PATH', Text::_('COM_TRANSLATOR_UNKNOWN_CLIENT')));
			}

			if (empty($fileExpl[1]))
			{
				throw new \Exception(Text::sprintf('COM_TRANSLATOR_ERROR_GET_PATH', Text::_('COM_TRANSLATOR_LANGUAGE_EMPTY')));
			}

			if (empty($fileExpl[2]))
			{
				throw new \Exception(Text::sprintf('COM_TRANSLATOR_ERROR_GET_PATH', Text::_('COM_TRANSLATOR_FILENAME_EMPTY')));
			}

			$client   = $fileExpl[0];
			$language = $fileExpl[1];
			$filename = $fileExpl[2];

			$path = constant('JPATH_' . strtoupper($client)) . '/language/' . $language . '/' . $filename;

			if (file_exists($path) === false)
			{
				throw new \Exception(Text::sprintf('COM_TRANSLATOR_ERROR_GET_PATH', Text::_('COM_TRANSLATOR_FILE_NOT_EXISTS')));
			}

			self::$path[$file] = $path;
		}

		return self::$path[$file];
	}

	/**
	 * Get language constants by file
	 *
	 * @param   string  $file
	 * @param   int     $save
	 *
	 * @return array
	 *
	 * @throws \Exception
	 * @since version
	 */
	public static function getConstants(string $file)
	{
		if (isset(self::$constants[$file]) === false)
		{
			$path      = self::getPath($file);
			$constants = LanguageHelper::parseIniFile($path);
			ksort($constants);
			self::$constants[$file] = $constants;
		}

		return self::$constants[$file];
	}

	/**
	 * Save constants to a language file.
	 *
	 * @param   array   $constants
	 * @param   string  $file
	 *
	 * @return bool
	 *
	 * @throws \Exception
	 * @since version
	 */
	public static function saveToIniFile(array $constants, string $file)
	{
		if (LanguageHelper::saveToIniFile(self::getPath($file), $constants) === false)
		{
			Factory::getApplication()->enqueueMessage(Text::sprintf('COM_TRANSLATOR_ERROR_SAVE_CONSTANTS_TO_LANGUAGE_FILE', $file), 'error');

			return false;
		}

		self::$constants[$file] = $constants;

		return true;
	}


}