<?php

defined('JPATH_BASE') or die;

use Joomla\CMS\
{
    Uri\Uri,
    Factory,
    HTML\HTMLHelper,
    Language\Text,
};

/** @var array $displayData */

extract($displayData);

/**
 * Layout variables
 * ------------------
 *
 * @var string $to_file
 * @var string $name
 * @var string $label
 * @var array  $files
 *
 */

//HTMLHelper::_('bootstrap.modal', '#'.$name.'Modal', []);
$wa  = Factory::getApplication()->getDocument()->getWebAssetManager();
$url = Uri::base().'index.php?option=com_translator&view=constants&layout=import&tmpl=component&to_file='.$to_file.'&file=';
$js  = <<< JS
jQuery(document).ready(function ($) {
    
    let importModal = $('#{$name}Modal').appendTo('body');
    
    $('#{$name}').on('change', function () {
        let file = $(this).val();
        $(this).prop('selectedIndex', 0).trigger("liszt:updated");
        let iframeHtml = '<iframe class="iframe" src="{$url}'+file+'" name="{$label}" title="{$label}"></iframe>';
        importModal.attr('data-iframe', iframeHtml);
        importModal.modal('show');
        
    });
});
JS;
//$wa->addInlineStyle('#'.$name.'Modal .modal-body{max-height:80vh;overflow:auto !important;}');
$wa->addInlineScript($js);

echo HTMLHelper::_('bootstrap.renderModal', $name.'Modal', [
    'title'  => $label,
    'footer' => false,
    'bodyHeight'  => 80,
    'modalWidth'  => 80,
    'url'    => $url
]);

?>

<div class="float-end ms-2">
    <div class="form-inline mt-1">
        <div class="form-group">
            <label for="<?= $name ?>" class="float-left mr-2" data-toggle="tooltip" title="<?= Text::_('COM_TRANSLATOR_IMPORT_FROM_DESC') ?>"><?= Text::_('COM_TRANSLATOR_IMPORT_FROM_LABEL') ?></label>
            <select class="form-control custom-select float-left" id="<?= $name ?>">
                <option value=""><?= Text::_('JSELECT') ?></option>
                <?= HTMLHelper::_('select.options', $files) ?>
            </select>
        </div>
    </div>
</div>

