<?php

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\LanguageHelper;
use Saharin\Component\Translator\Administrator\Helper\TranslatorHelper;
use Joomla\CMS\Session\Session;

/** @var Saharin\Component\Translator\Administrator\View\Files\HtmlView $this */

HTMLHelper::_('jquery.framework');
HTMLHelper::_('bootstrap.tooltip', '.hasTooltip');

/** @var \Joomla\CMS\Application\AdministratorApplication $app */
$app = Factory::getApplication();

$client   = $this->state->get('filter.client', 'site');
$language = $this->state->get('filter.language', $app->getLanguage()->getTag());

$doc = $app->getDocument();
$js  = <<< JS
jQuery(document).ready(function($) {
    $('#compareCheckbox').on('click', function(e) {
        if($(this).is(':checked')) {
            $('#compare').val('1');
        } else {
            $('#compare').val('0');
        }
        this.form.submit();
    });
});
JS;
$css = <<< CSS
span.diff-constants {
    cursor: pointer;
}
CSS;
$doc->addScriptDeclaration($js);
$doc->addStyleDeclaration($css);


?>
<form action="<?= Route::_('index.php?option=com_translator&view=files', false) ?>" method="post" name="adminForm"
      id="adminForm">


    <div id="j-main-container">

        <div class="clearfix w-100 float-left">

            <div class="w-100 clearfix">
				<?= LayoutHelper::render('joomla.searchtools.default', ['view' => $this]) ?>
            </div>

            <div class="float-right mb-3">
                <div class="custom-control custom-switch">
                    <input type="hidden" name="filter[compare]" value="0" id="compare" class="">
                    <input id="compareCheckbox"
                           type="checkbox"<?= ($this->state->get('filter.compare') ? ' checked' : '') ?>
                           class="custom-control-input">
                    <label class="custom-control-label"
                           for="compareCheckbox"><?= Text::_('COM_TRANSLATE_SHOW_LANGUAGES_FOR_COMPARE') ?></label>
                </div>
            </div>

        </div>

        <div class="w-100 float-left">

			<?php if (empty($this->files))
			{
				?>
                <div class="alert alert-info">
                    <span class="fa fa-info-circle" aria-hidden="true"></span><span
                            class="sr-only"><?php echo Text::_('INFO'); ?></span>
					<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                </div>
				<?php
			}
			else
			{
				?>

                <table class="table table-striped" id="overrideList">
                    <thead>
                    <tr>
                        <th class="text-center" width="1%">
							<?= Text::_('COM_TRANSLATOR_NUMBER') ?>
                        </th>
                        <th>
							<?= Text::_('COM_TRANSLATOR_FILE_NAME') ?>
                        </th>
                        <th class="text-center">
							<?= Text::_('COM_TRANSLATOR_NUMBER_OF_CONSTANTS') ?>
                        </th>
						<?php
						if (!empty($this->languages))
						{
							foreach ($this->languages as $lang)
							{
								?>
                                <th class="text-center">
									<?= $lang['nativeName'] ?>
                                    <br/>
                                    <small><?= Text::_('COM_TRANSLATOR_TOTAL_UNIQUE_LABEL') ?></small>
                                </th>
								<?php
							}
						}
						?>
                    </tr>
                    </thead>
                    <tbody>
					<?php
					$i = 0;
					foreach ($this->files as $file)
					{
						$i++;
						$fileKey        = $client . ':' . $language . ':' . $file;
						$constatnts     = TranslatorHelper::getConstants($fileKey);
						$countConstants = count($constatnts);
						?>
                        <tr class="row<?php echo $i % 2; ?>">
                            <td class="text-center">
								<?= $i ?>
                            </td>
                            <td>
								<?= HTMLHelper::link(Route::_('index.php?option=com_translator&view=constants&file=' . $fileKey, false), $file) ?>
                            </td>
                            <td class="text-center">
								<?= $countConstants ?>
                            </td>
							<?php
							if (!empty($this->languages))
							{
								foreach ($this->languages as $lang)
								{
									$compareFileKey = $client . ':' . $lang['tag'] . ':' . $file;

									echo '<td class="text-center">';
									try
									{
										$compareConstants      = TranslatorHelper::getConstants($compareFileKey);
										$countCompareConstants = count($compareConstants);
										$diffConstants         = array_diff_key($compareConstants, $constatnts);
										$countDiffConstants    = count($diffConstants);

										echo $countCompareConstants . ($countDiffConstants > 0 ? ' <span class="hasTooltip diff-constants text-error" title="' . implode('<br/>', array_keys($diffConstants)) . '">(' . $countDiffConstants . ')</span>' : '');

									}
									catch (Exception $e)
									{
										echo HTMLHelper::_('link', Route::_('index.php?option=com_translator&task=file.create&file=' . $compareFileKey . '&' . Session::getFormToken() . '=1', false), Text::_('COM_TRANSLATOR_ADD_FILE'));
									}
									echo '</td>';
								}
							}
							?>
                        </tr>
						<?php
					}
					?>
                    </tbody>
                </table>
				<?php
			}
			?>

        </div>

        <input type="hidden" name="form_submited" value="1">
        <input type="hidden" name="task" value="">
		<?= HTMLHelper::_('form.token') ?>

    </div>

</form>
