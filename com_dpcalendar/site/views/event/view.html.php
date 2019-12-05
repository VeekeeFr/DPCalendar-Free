<?php
/**
 * @package    DPCalendar
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2007 - 2019 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JLoader::import('components.com_dpcalendar.helpers.schema', JPATH_ADMINISTRATOR);

class DPCalendarViewEvent extends \DPCalendar\View\BaseView
{
	protected $event;

	public function init()
	{
		if ($this->getLayout() == 'empty') {
			return;
		}

		$event = $this->get('Item');

		if ($event == null || !$event->id) {
			throw new Exception(JText::_('COM_DPCALENDAR_ERROR_EVENT_NOT_FOUND'), 404);
		}

		// Use the options from the event
		$this->params->merge($event->params);

		if ($this->params->get('event_redirect_to_url', 0) && $event->url) {
			$this->app->redirect($event->url);
		}

		// Add router helpers.
		$event->slug = $event->alias ? ($event->id . ':' . $event->alias) : $event->id;

		// Check the access to the event
		$levels = JFactory::getUser()->getAuthorisedViewLevels();

		if (!in_array($event->access, $levels) ||
			((in_array($event->access, $levels) && (isset($event->category_access) && !in_array($event->category_access, $levels))))
		) {
			$this->setError(JText::_('JERROR_ALERTNOAUTHOR'));

			return false;
		}

		$event->tags = new JHelperTags();
		$event->tags->getItemTags('com_dpcalendar.event', $event->id);

		JPluginHelper::importPlugin('content');

		$event->text = $event->description;
		JFactory::getApplication()->triggerEvent(
			'onContentPrepare',
			array(
				'com_dpcalendar.event',
				&$event,
				&$event->params,
				0
			)
		);
		$event->description = $event->text;

		$event->displayEvent                    = new stdClass();
		$results                                = JFactory::getApplication()->triggerEvent(
			'onContentAfterTitle',
			['com_dpcalendar.event', &$event, &$event->params, 0]
		);
		$event->displayEvent->afterDisplayTitle = trim(implode("\n", $results));

		$results                                   = JFactory::getApplication()->triggerEvent(
			'onContentBeforeDisplay',
			['com_dpcalendar.event', &$event, &$event->params, 0]
		);
		$event->displayEvent->beforeDisplayContent = trim(implode("\n", $results));

		$results                                  = JFactory::getApplication()->triggerEvent(
			'onContentAfterDisplay',
			['com_dpcalendar.event', &$event, &$event->params, 0]
		);
		$event->displayEvent->afterDisplayContent = trim(implode("\n", $results));

		$this->event = $event;

		$model = $this->getModel();

		if ($this->params->get('event_count_clicks', 1)) {
			$model->hit();
		}

		$this->roomTitles = [];
		if ($event->locations && !empty($this->event->rooms)) {
			foreach ($event->locations as $location) {
				if (empty($location->rooms)) {
					continue;
				}

				foreach ($this->event->rooms as $room) {
					list($locationId, $roomId) = explode('-', $room, 2);

					foreach ($location->rooms as $lroom) {
						if ($locationId != $location->id || $roomId != $lroom->id) {
							continue;
						}

						$this->roomTitles[$locationId][$room] = $lroom->title;
					}
				}
			}
		}

		$this->avatar     = '';
		$this->authorName = '';
		$author           = JFactory::getUser($event->created_by);
		if ($author) {
			$this->authorName = $event->created_by_alias ? $event->created_by_alias : $author->name;

			if (JFile::exists(JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php')) {
				// Set the community builder username as content
				include_once(JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php');
				$cbUser = CBuser::getInstance($event->created_by);
				if ($cbUser) {
					$this->authorName = $cbUser->getField('formatname', null, 'html', 'none', 'list', 0, true);
				}
			}

			$this->avatar = DPCalendarHelper::getAvatar($author->id, $author->email, $this->params);
		}

		$this->event->contact_link = '';
		if (!empty($event->contactid)) {
			JLoader::register('ContactHelperRoute', JPATH_SITE . '/components/com_contact/helpers/route.php');
			$this->event->contact_link = JRoute::_(
				ContactHelperRoute::getContactRoute($event->contactid . ':' . $event->contactalias, $event->contactcatid)
			);
		}
		$this->displayData['event'] = $this->event;

		$this->seriesEvents      = [];
		$this->seriesEventsTotal = 0;
		$this->originalEvent     = null;

		if ($event->original_id > 0 || $event->original_id == '-1') {
			$seriesModel             = $model->getSeriesEventsModel($this->event);
			$this->seriesEvents      = $seriesModel->getItems();
			$this->seriesEventsTotal = $seriesModel->getTotal();
			$this->originalEvent     = $model->getItem($event->original_id);
		}

		$this->noBookingMessage = $this->getBookingMessage($event);

		if ($this->originalEvent && $this->originalEvent->booking_series) {
			$this->noBookingMessage = $this->getBookingMessage($this->originalEvent);
		}

		// Taxes stuff
		$this->taxRate = null;
		if ($this->country = \DPCalendar\Helper\Location::getCountryForIp()) {
			\JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_dpcalendar/models', 'DPCalendarModel');
			$model         = \JModelLegacy::getInstance('Taxrate', 'DPCalendarModel', ['ignore_request' => true]);
			$this->taxRate = $model->getItemByCountry($this->country->id);

			$this->app->getLanguage()->load('com_dpcalendar.countries', JPATH_ADMINISTRATOR . '/components/com_dpcalendar');
		}

		return parent::init();
	}

	/**
	 * Returns a booking message. If the string is null, then booking is possible.
	 * If it is an empty string then no booking is activated. If it is a string, then
	 * no booking is possible while the string represents the warning message.
	 *
	 * @param $event
	 *
	 * @return string|null
	 */
	private function getBookingMessage($event)
	{
		// Handle no event
		if (!$event) {
			return '';
		}

		// When booking is disabled or not available
		if ($event->capacity == '0' || \DPCalendarHelper::isFree()) {
			return '';
		}

		// Check permissions
		$calendar = \DPCalendarHelper::getCalendar($event->catid);
		if (!$calendar || !$calendar->canBook) {
			return '';
		}

		// Check if full
		if ($event->capacity > 0 && $event->capacity_used >= $event->capacity) {
			return $this->translate('COM_DPCALENDAR_VIEW_EVENT_BOOKING_MESSAGE_CAPACITY_FULL');
		}

		// Check if registration ended
		$now                = \DPCalendarHelper::getDate();
		$regstrationEndDate = \DPCalendar\Helper\Booking::getRegistrationEndDate($event);
		if ($regstrationEndDate->format('U') < $now->format('U')) {
			return JText::sprintf(
				'COM_DPCALENDAR_VIEW_EVENT_BOOKING_MESSAGE_REGISTRATION_END',
				$regstrationEndDate->format($this->params->get('event_date_format', 'm.d.Y'), true),
				$regstrationEndDate->format('H:i') != '00:00' ? $regstrationEndDate->format($this->params->get('event_time_format', 'h:i a'),
					true) : ''
			);
		}

		// All fine
		return null;
	}

	protected function prepareDocument()
	{
		if ($this->getLayout() == 'empty') {
			return;
		}

		parent::prepareDocument();

		$app     = JFactory::getApplication();
		$menus   = $app->getMenu();
		$pathway = $app->getPathway();

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($title = $this->params->get('page_title', '')) {
			$this->document->setTitle($title);
		}

		$id = $menu && array_key_exists('id', $menu->query) ? (int)$menu->query['id'] : 0;

		// If the menu item does not concern this newsfeed
		if ($menu && ($menu->query['option'] != 'com_dpcalendar' || $menu->query['view'] != 'event' || $id != $this->event->id)) {
			// If this is not a single event menu item, set the page title to
			// the event title
			if ($this->event->title) {
				$this->document->setTitle($this->event->title);
			}

			$path     = array(array('title' => $this->event->title, 'link' => ''));
			$category = DPCalendarHelper::getCalendar($this->event->catid);
			while ($category != null && ($menu->query['option'] != 'com_dpcalendar' || $menu->query['view'] == 'event' || $id != $category->id) &&
				$category->id > 1) {
				$path[]   = array('title' => $category->title, 'link' => DPCalendarHelperRoute::getCalendarRoute($category->id));
				$category = $category->getParent();
			}
			$path = array_reverse($path);
			foreach ($path as $item) {
				$pathway->addItem($item['title'], $item['link']);
			}
		}

		$metadesc = trim($this->event->metadata->get('metadesc'));
		if (!$metadesc) {
			$metadesc = JHtmlString::truncate($this->event->description, 100, true, false);
		}
		if ($metadesc) {
			$this->document->setDescription($this->event->title . ' '
				. DPCalendarHelper::getDateStringFromEvent($this->event, $this->params->get('event_date_format', 'm.d.Y'),
					$this->params->get('event_time_format', 'g:i a'), true) . ' ' . $metadesc);
		}

		if ($this->event->metakey) {
			$this->document->setMetadata('keywords', $this->event->metakey);
		}

		if ($app->get('MetaAuthor') == '1' && !empty($this->event->author)) {
			$this->document->setMetaData('author', $this->event->author);
		}

		$mdata = $this->event->metadata->toArray();
		foreach ($mdata as $k => $v) {
			if ($v) {
				$this->document->setMetadata($k, $v);
			}
		}
	}
}
