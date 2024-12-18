<?php
/**
 * @package   DPCalendar
 * @copyright Copyright (C) 2015 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */

\defined('_JEXEC') or die();

use DigitalPeak\Component\DPCalendar\Administrator\Helper\DPCalendarHelper;
use DigitalPeak\Component\DPCalendar\Administrator\Translator\Translator;

$event = $displayData['event'];

$dateFormat = empty($displayData['dateFormat']) ? DPCalendarHelper::getComponentParameter('event_date_format', 'd.m.Y') : $displayData['dateFormat'];
$timeFormat = empty($displayData['timeFormat']) ? DPCalendarHelper::getComponentParameter('event_time_format', 'H:i') : $displayData['timeFormat'];

$translator = empty($displayData['translator']) ? new Translator() : $displayData['translator'];
$dateFormat = $translator->translate($dateFormat);
$timeFormat = $translator->translate($timeFormat);

// These are the dates to display
$startDate = DPCalendarHelper::getDate($event->start_date, $event->all_day)->format($dateFormat, true);
$startTime = DPCalendarHelper::getDate($event->start_date, $event->all_day)->format($timeFormat, true);
$startTime = trim($startTime);
$endDate   = DPCalendarHelper::getDate($event->end_date, $event->all_day)->format($dateFormat, true);
$endTime   = DPCalendarHelper::getDate($event->end_date, $event->all_day)->format($timeFormat, true);
$endTime   = trim($endTime);
?>
<span class="dp-date dp-time">
	<?php if ($event->all_day && $startDate === $endDate) { ?>
		<span class="dp-date__start"><?php echo $startDate; ?></span>
	<?php } ?>
	<?php if ($event->all_day && $startDate !== $endDate) { ?>
		<span class="dp-date__start"><?php echo $startDate; ?></span>
		<span class="dp-date__separator">-</span>
		<span class="dp-date__end"><?php echo $endDate; ?></span>
	<?php } ?>
	<?php if (!$event->all_day && $startDate === $endDate) { ?>
		<span class="dp-date__start"><?php echo $startDate; ?></span>
		<span class="dp-time__start"><?php echo $startTime; ?></span>
		<?php if ($event->show_end_time) { ?>
			<span class="dp-time__separator">-</span>
			<span class="dp-time__end"><?php echo $endTime; ?></span>
		<?php } ?>
	<?php } ?>
	<?php if (!$event->all_day && $startDate !== $endDate) { ?>
		<span class="dp-date__start"><?php echo $startDate; ?></span>
		<span class="dp-time__start"><?php echo $startTime; ?></span>
		<span class="dp-date__separator dp-time__separator">-</span>
		<span class="dp-date__end"><?php echo $endDate; ?></span>
		<?php if ($event->show_end_time) { ?>
			<span class="dp-time__end"><?php echo $endTime; ?></span>
		<?php } ?>
	<?php } ?>
</span>
