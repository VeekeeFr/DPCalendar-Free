(function () {
	'use strict';

	/**
	 * @package   DPCalendar
	 * @author    Digital Peak http://www.digital-peak.com
	 * @copyright Copyright (C) 2007 - 2020 Digital Peak. All rights reserved.
	 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
	 */

	document.addEventListener('DOMContentLoaded', () => {
		if (document.querySelector('.com-dpcalendar-calendar__map')) {
			loadDPAssets(['/com_dpcalendar/js/dpcalendar/map.js'], () => {
				loadDPAssets(['/com_dpcalendar/js/dpcalendar/calendar.js']);
			});
		} else {
			loadDPAssets(['/com_dpcalendar/js/dpcalendar/calendar.js']);
		}

		const noLink = document.querySelector('.com-dpcalendar-calendar_printable');
		if (noLink) {
			setInterval(() => {
				[].slice.call(noLink.querySelectorAll('a')).forEach((link) => {
					link.removeAttribute('href');
				});
			}, 2000);
		}

		const quickAdd = document.querySelector('.com-dpcalendar-calendar__quickadd');
		if (quickAdd == null) {
			return;
		}

		loadDPAssets(['/com_dpcalendar/js/dpcalendar/layouts/block/datepicker.js', '/com_dpcalendar/js/dpcalendar/layouts/block/timepicker.js']);

		document.onkeydown = (evt) => {
			const event = evt || window.event;
			let isEscape = false;
			if ('key' in evt) {
				isEscape = (event.key == 'Escape' || event.key == 'Esc');
			} else {
				isEscape = (event.keyCode == 27);
			}
			if (isEscape) {
				quickAdd.style.display = 'none';
			}
		};

		window.addEventListener('hashchange', () => {
			quickAdd.querySelector('input[name=urlhash]').value = window.location.hash;
		});
		quickAdd.querySelector('input[name=urlhash]').value = window.location.hash;

		quickAdd.querySelector('.dp-quickadd__button-submit').addEventListener('click', () => {
			quickAdd.querySelector('input[name=task]').value = 'event.save';
			quickAdd.querySelector('.dp-form').submit();
		});
		quickAdd.querySelector('.dp-quickadd__button-edit').addEventListener('click', () => {
			quickAdd.querySelector('.dp-form').submit();
		});
		quickAdd.querySelector('.dp-quickadd__button-cancel').addEventListener('click', () => {
			quickAdd.querySelector('input[name="jform[title]"]').value = '';
			quickAdd.style.display = 'none';
		});
	});

}());
//# sourceMappingURL=default.js.map
