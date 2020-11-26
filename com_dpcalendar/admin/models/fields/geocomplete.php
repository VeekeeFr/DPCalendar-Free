<?php
/**
 * @package   DPCalendar
 * @copyright Copyright (C) 2014 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */
defined('_JEXEC') or die();

JLoader::import('components.com_dpcalendar.helpers.dpcalendar', JPATH_ADMINISTRATOR);
JFormHelper::loadFieldClass('text');

class JFormFieldGeocomplete extends JFormFieldText
{
	protected $type = 'Geocomplete';

	public function getInput()
	{
		(new \DPCalendar\Helper\LayoutHelper())->renderLayout(
			'block.map',
			['document' => new DPCalendar\HTML\Document\HtmlDocument(), 'translator' => new \DPCalendar\Translator\Translator()]
		);

		$input = parent::getInput();

		$input .= '<button id="' . $this->id . '_find" class="dp-button" type="button" title="' . JText::_('JSEARCH_FILTER_SUBMIT') .
			'"><i class="icon-search"></i></button>';

		return $input;
	}
}
