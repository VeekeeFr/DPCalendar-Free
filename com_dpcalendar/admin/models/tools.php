<?php
/**
 * @package   DPCalendar
 * @copyright Copyright (C) 2014 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */

defined('_JEXEC') or die();

use DPCalendar\Helper\Transifex;

JLoader::import('joomla.application.component.modellist');

class DPCalendarModelTools extends JModelLegacy
{

	public function getResourcesFromTransifex()
	{
		$resources = Transifex::getData('resources');

		$data = json_decode($resources['data']);
		usort($data, function ($r1, $r2) {
			return strcmp($r1->name, $r2->name);
		});

		return $data;
	}
}
