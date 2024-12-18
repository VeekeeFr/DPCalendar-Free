<?php
/**
 * @package   DPCalendar
 * @copyright Copyright (C) 2014 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */

\defined('_JEXEC') or die();

$this->dpdocument->loadStyleFile('dpcalendar/views/cpanel/default.css');
$this->dpdocument->loadScriptFile('views/cpanel/default.js');
?>
<div class="com-dpcalendar-cpanel">
	<div id="j-main-container">
		<?php echo $this->loadtemplate('icons'); ?>
		<div class="com-dpcalendar-cpanel__welcome">
			<h2 class="dp-heading"><?php echo $this->translate('COM_DPCALENDAR_VIEW_CPANEL_WELCOME'); ?></h2>
			<p class="dp-paragraphe"><?php echo $this->translate('COM_DPCALENDAR_VIEW_CPANEL_INTRO'); ?></p>
		</div>
		<div class="com-dpcalendar-cpanel__boxes dp-grid">
			<div class="dp-grid__column com-dpcalendar-cpanel__box"><?php echo $this->loadtemplate('events_upcoming'); ?></div>
			<div class="dp-grid__column com-dpcalendar-cpanel__box"><?php echo $this->loadtemplate('events_last'); ?></div>
			<div class="dp-grid__column com-dpcalendar-cpanel__box"><?php echo $this->loadtemplate('events_new'); ?></div>
		</div>
		<div class="com-dpcalendar-cpanel__boxes dp-grid">
			<div class="dp-grid__column com-dpcalendar-cpanel__box"><?php echo $this->loadtemplate('stats'); ?></div>
			<div class="dp-grid__column com-dpcalendar-cpanel__box"><?php echo $this->loadtemplate('checks'); ?></div>
			<div class="dp-grid__column com-dpcalendar-cpanel__box"><?php echo $this->loadtemplate('help'); ?></div>
		</div>
		<?php echo $this->loadtemplate('footer'); ?>
	</div>
</div>
