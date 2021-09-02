<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('jquery.framework');

//HTMLHelper::_('behavior.formvalidation');
//HTMLHelper::_('behavior.keepalive');
//HTMLHelper::_('behavior.tooltip');
//HTMLHelper::_('formbehavior.chosen', 'select');

/** @var \Joomla\CMS\Application\AdministratorApplication $app */
$app = Factory::getApplication();
$doc = $app->getDocument();
$wa  = $doc->getWebAssetManager();

$wa->useScript('form.validate');
$wa->useScript('keepalive');

$js = <<< JS

	Joomla.submitbutton = function(task)
	{
		if (task === "file.cancel" || document.formvalidator.isValid(document.getElementById("adminForm")))
		{
			Joomla.submitform(task, document.getElementById("adminForm"));
		}
	};

    jQuery(document).ready(function ($) {

        var extension = $('#jform_extension'),
            language = $('#jform_language'),
            langfn = function () {
                var val = $(':selected', extension).text(),
                    val_split = val.split(' - ');
                if (typeof val_split[1] !== 'undefined') {
                    $('option', language).each(function (index, value) {
                        var option_split = $(this).text().split(' - ');
                        if (typeof option_split[1] !== 'undefined') {
                            if (option_split[1] === val_split[1]) {
                                $(this).show();
                            } else {
                                $(this).hide();
                            }
                        } else {
                            $(this).show();
                        }
                    });
                } else {
                    $('option', language).show();
                }
                language.val('').trigger('liszt:updated');
            };
        langfn();
        extension.on('change', function () {
            langfn();
        });
    });

JS;

$wa->addInlineScript($js);

?>

<form action="<?php echo JRoute::_('index.php?option=com_translator&layout=edit'); ?>" method="post" name="adminForm"
      id="adminForm" class="form-validate">

    <div class="form-horizontal">

		<?= $this->form->renderFieldset('general') ?>

        <input type="hidden" name="task" value=""/>

		<?php echo JHtml::_('form.token'); ?>

    </div>

</form>