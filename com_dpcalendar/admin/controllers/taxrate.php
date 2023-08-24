<?php

use Joomla\CMS\MVC\Controller\FormController;

/**
 * @package   DPCalendar
 * @copyright Copyright (C) 2019 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */
defined('_JEXEC') or die();

class DPCalendarControllerTaxrate extends FormController
{
	protected $text_prefix = 'COM_DPCALENDAR_TAXRATE';

	public function save($key = null, $urlVar = 'r_id')
	{
		return parent::save($key, $urlVar);
	}

	public function edit($key = null, $urlVar = 'r_id')
	{
		return parent::edit($key, $urlVar);
	}

	public function cancel($key = 'r_id')
	{
		return parent::cancel($key);
	}
}
