<?php
/**
 * @package   DPCalendar
 * @copyright Copyright (C) 2014 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */

\defined('_JEXEC') or die();

use Joomla\CMS\HTML\HTMLHelper;

$this->dpdocument->loadStyleFile('dpcalendar/views/adminlist/default.css');
$this->dpdocument->loadScriptFile('views/adminlist/default.js');
$this->dpdocument->addScriptOptions('adminlist', ['listOrder' => $this->state->get('list.ordering')]);
?>
<div class="com-dpcalendar-locations com-dpcalendar-adminlist">
	<form action="<?php echo $this->router->route('index.php?option=com_dpcalendar&view=locations'); ?>"
		  method="post" name="adminForm" id="adminForm">

		<div id="j-main-container">
			<?php echo $this->layoutHelper->renderLayout('joomla.searchtools.default', ['view' => $this]); ?>
			<?php echo $this->loadTemplate('locations'); ?>
		</div>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="boxchecked" value="0"/>
		<?php echo HTMLHelper::_('form.token'); ?>
	</form>
	<?php echo $this->loadTemplate('footer'); ?>
</div>
