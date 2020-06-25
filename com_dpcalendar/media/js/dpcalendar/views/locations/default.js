(function () {
	'use strict';

	/**
	 * @package   DPCalendar
	 * @author    Digital Peak http://www.digital-peak.com
	 * @copyright Copyright (C) 2007 - 2020 Digital Peak. All rights reserved.
	 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
	 */

	document.addEventListener('DOMContentLoaded', () => {
		loadDPAssets(['/com_dpcalendar/js/dpcalendar/dpcalendar.js']);
		if (document.querySelector('.com-dpcalendar-locations__map')) {
			loadDPAssets(['/com_dpcalendar/js/dpcalendar/map.js'], () => {
				if (document.querySelector('.com-dpcalendar-locations__resource')) {
					loadDPAssets(['/com_dpcalendar/js/dpcalendar/calendar.js']);
				}
			});
		} else if (document.querySelector('.com-dpcalendar-locations__resource')) {
			loadDPAssets(['/com_dpcalendar/js/dpcalendar/calendar.js']);
		}
	});

}());
//# sourceMappingURL=default.js.map
