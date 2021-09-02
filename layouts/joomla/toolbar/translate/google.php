<?php

defined('JPATH_BASE') or die;

use Joomla\CMS\
{
	Language\Text,
	Factory,
};

Text::script('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST');

$js = <<< JS
jQuery(document).ready(function ($) {
    $('#translateByGoogle').on('click', function () {
        if (document.adminForm.boxchecked.value === '0') {
            alert(Joomla.JText._('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST'));
        } else {
            $('#translateByGoogleModal').modal('show');
        }
    });
});
JS;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->addInlineScript($js);


?>
<joomla-toolbar-button>
    <button id="translateByGoogle" class="btn"><?= Text::_('COM_TRANSLATOR_TOOLBAR_GOOGLE_LABEL') ?></button>
</joomla-toolbar-button>
