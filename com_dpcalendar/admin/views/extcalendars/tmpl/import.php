<?php
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Text;
/**
 * @package   DPCalendar
 * @copyright Copyright (C) 2014 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */
defined('_JEXEC') or die();

$this->dpdocument->loadScriptFile('dpcalendar/views/extcalendars/default.js');

$plugin = $this->input->getCmd('dpplugin');
Factory::getLanguage()->load('plg_dpcalendar_' . $plugin, JPATH_PLUGINS . '/dpcalendar/' . $plugin);
Form::addFormPath(JPATH_PLUGINS . '/dpcalendar/' . $plugin . '/forms');
$form = Form::getInstance('form', 'params');
?>
<form action="<?php echo htmlspecialchars(Uri::getInstance()) ?>" method="post" target="_parent">
	<?php foreach ($form->getFieldset('params') as $field) { ?>
		<?php if (!$form->getFieldAttribute(str_replace('params[', '', trim($field->__get('name'), ']')), 'import', null, 'params')) { ?>
			<?php continue; ?>
		<?php } ?>
		<div class="control-group">
			<div class="control-label">
				<?php echo $field->label; ?>
			</div>
			<div class="controls">
				<?php echo $field->input; ?>
				<br/><b><?php echo Text::_($field->description) ?></b>
			</div>
		</div>
	<?php } ?>
	<input type="hidden" name="task" value="plugin.action"/>
	<input type="hidden" name="action" value="import"/>
	<input type="submit" class="btn btn-primary" value="<?php echo Text::_('COM_DPCALENDAR_VIEW_TOOLS_IMPORT') ?>"/>
</form>
