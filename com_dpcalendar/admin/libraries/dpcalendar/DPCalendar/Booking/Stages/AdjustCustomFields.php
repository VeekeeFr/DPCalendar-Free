<?php
/**
 * @package   DPCalendar
 * @author    Digital Peak http://www.digital-peak.com
 * @copyright Copyright (C) 2007 - 2020 Digital Peak. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */

namespace DPCalendar\Booking\Stages;

defined('_JEXEC') or die();

use League\Pipeline\StageInterface;

class AdjustCustomFields implements StageInterface
{
	public function __invoke($payload)
	{
		if (empty($payload->eventsWithTickets)) {
			return $payload;
		}

		// Clear the cache, doggy
		$reflection = new \ReflectionProperty(\FieldsHelper::class, 'fieldsCache');
		$reflection->setAccessible(true);
		$reflection->setValue(null, null);

		$event                   = reset($payload->eventsWithTickets);
		$payload->item->catid    = $event->catid;
		$payload->item->jcfields = \FieldsHelper::getFields('com_dpcalendar.booking', $payload->item, true);
		unset($payload->item->catid);

		return $payload;
	}
}
