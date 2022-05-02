<?php

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\
{
	Language\Text,
	Router\Route,
	HTML\HTMLHelper,
	Factory,
};

/** @var \Joomla\CMS\Application\AdministratorApplication $app */
$app = Factory::getApplication();

HTMLHelper::_('jquery.framework');
HTMLHelper::_('behavior.core');

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->disableScript('core');

$input = Factory::getApplication()->input;
Text::script('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST');

$js = <<<JS
jQuery(document).ready(function($) {
    $('#btnImport').on('click', function (e) {
        e.stopPropagation();
        console.log(typeof this.form.boxchecked.value, this.form.boxchecked.value);
        if (this.form.boxchecked.value === 0) { 
            alert(Joomla.JText._('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST')); return false;
        }
        let importForm = $(this).closest('form').appendTo(parent.document.getElementById("wrapper"));
        importForm.hide();
        importForm.submit();
        window.parent.Joomla.Modal.getCurrent().close();
    });
});
JS;

$wa->addInlineScript($js);
?>

<form action="<?= Route::_('index.php?option=com_translator&task=constants.export', false) ?>" method="post"
      name="importForm" id="importForm">

    <div id="j-main-container">

		<?php if (empty($this->items))
		{
			?>
            <div class="alert alert-no-items"><?= Text::_('COM_TRANSLATOR_NO_CONSTANTS_TO_EXPORT') ?></div>
			<?php
		}
		else
		{
			?>
            <div class="btns-row">
                <button id="btnImport" class="btn btn-success float-end btn-import">
                    <span class="icon-out" aria-hidden="true"></span>
					<?= Text::_('COM_TRANSLATOR_EXPORT') ?>
                </button>
            </div>

            <table class="table table-striped" id="overrideList">
                <thead>
                <tr>

                    <th width="1%" class="center">
						<?php echo HTMLHelper::_('grid.checkall'); ?>
                    </th>

                    <th class="center" width="1%">
						<?= Text::_('COM_TRANSLATOR_NUMBER') ?>
                    </th>

                    <th>
						<?= Text::_('COM_TRANSLATOR_CONSTANT_KEY') ?>
                    </th>

                    <th class="center">
						<?= Text::_('COM_TRANSLATOR_CONSTANT_VALUE') ?>
                    </th>

                </tr>
                </thead>
                <tbody>
				<?php
				$i = 0;
				foreach ($this->items as $key => $val)
				{
					$i++;
					?>

                    <tr class="row<?php echo $i % 2; ?>">

                        <td class="center">
							<?= HTMLHelper::_('grid.id', $i . 'export', $key) ?>
                        </td>

                        <td class="center">
							<?= $i ?>
                        </td>

                        <td>
							<?= $key ?>
                        </td>

                        <td class="center">
							<?= $val ?>
                        </td>

                    </tr>

					<?php
				}
				?>
                </tbody>
            </table>

			<?php
		}
		?>

        <input type="hidden" name="boxchecked" value="0">
		<?= HTMLHelper::_('form.token') ?>

        <input type="hidden" name="file" value="<?= $input->get->get('to_file', null, 'raw') ?>">
        <input type="hidden" name="from_file" value="<?= $input->get->get('file', null, 'raw') ?>">

    </div>

</form>