<?php
/**
 * @package   DPCalendar
 * @author    Digital Peak http://www.digital-peak.com
 * @copyright Copyright (C) 2007 - 2020 Digital Peak. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */
defined('_JEXEC') or die();

if ($this->params->get('show_selection', 1) == 2) {
	return;
}
?>
<div class="com-dpcalendar-calendar__list com-dpcalendar-calendar__list_<?php echo $this->params->get('show_selection', 1) == 3 ? '' : 'hidden'; ?>">
	<?php foreach ($this->doNotListCalendars as $calendar) { ?>
		<?php $style = 'background-color: #' . $calendar->color . ';'; ?>
		<?php $style .= 'border-color: #' . $calendar->color . ';'; ?>
		<?php $style .= 'color: #' . \DPCalendar\Helper\DPCalendarHelper::getOppositeBWColor($calendar->color); ?>
		<dl class="com-dpcalendar-calendar__calendar-description dp-description">
			<dt class="dp-description__label">
				<input type="checkbox" name="cal-<?php echo $calendar->id; ?>" value="<?php echo $calendar->id; ?>"
					   id="cal-<?php echo $calendar->id; ?>" class="dp-input dp-input-checkbox" style="<?php echo $style; ?>">
				<label for="cal-<?php echo $calendar->id; ?>" class="dp-input-label">
					<?php echo str_pad(' ' . $calendar->title, strlen(' ' . $calendar->title) + $calendar->level - 1, '-', STR_PAD_LEFT); ?>
					<?php if ((!empty($calendar->icalurl) || !$calendar->external) && $this->params->get('show_export_links', 1)) { ?>
						<a href="<?php echo str_replace(['http://', 'https://'], 'webcal://', $this->router->getCalendarIcalRoute($calendar->id)); ?>"
						   class="dp-link dp-link-subscribe">
							[<?php echo $this->translate('COM_DPCALENDAR_VIEW_CALENDAR_TOOLBAR_SUBSCRIBE'); ?>]
						</a>
						<a href="<?php echo $this->router->getCalendarIcalRoute($calendar->id); ?>" class="dp-link">
							[<?php echo $this->translate('COM_DPCALENDAR_VIEW_CALENDAR_TOOLBAR_ICAL'); ?>]
						</a>
						<?php if (!$this->user->guest && $token = (new \Joomla\Registry\Registry($this->user->params))->get('token')) { ?>
							<a href="<?php echo $this->router->getCalendarIcalRoute($calendar->id, $token); ?>" class="dp-link dp-link-ical">
								[<?php echo $this->translate('COM_DPCALENDAR_VIEW_CALENDAR_TOOLBAR_PRIVATE_ICAL'); ?>]
							</a>
						<?php } ?>
						<?php if (!$calendar->external && !DPCalendarHelper::isFree() && !$this->user->guest) { ?>
							<?php $url = '/components/com_dpcalendar/caldav.php/calendars/' . $this->user->username . '/dp-' . $calendar->id; ?>
							<a href="<?php echo trim(JUri::base(), '/') . $url; ?>" class="dp-link dp-link-caldav">
								[<?php echo $this->translate('COM_DPCALENDAR_VIEW_PROFILE_TABLE_CALDAV_URL_LABEL'); ?>]
							</a>
						<?php } ?>
					<?php } ?>
				</label>
			</dt>
			<dl class="dp-description__description"><?php echo $calendar->description; ?></dl>
		</dl>
	<?php } ?>
</div>
