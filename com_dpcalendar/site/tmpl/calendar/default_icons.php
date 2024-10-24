<?php
/**
 * @package   DPCalendar
 * @copyright Copyright (C) 2020 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */

\defined('_JEXEC') or die();

use DigitalPeak\Component\DPCalendar\Administrator\HTML\Block\Icon;
?>
<div class="com-dpcalendar-calendar__icons">
	<?php echo $this->layoutHelper->renderLayout('block.icon', ['icon' => Icon::COMPRESS]); ?>
	<?php echo $this->layoutHelper->renderLayout('block.icon', ['icon' => Icon::DELETE]); ?>
	<?php echo $this->layoutHelper->renderLayout('block.icon', ['icon' => Icon::EDIT]); ?>
	<?php echo $this->layoutHelper->renderLayout('block.icon', ['icon' => Icon::EXPAND]); ?>
	<?php echo $this->layoutHelper->renderLayout('block.icon', ['icon' => Icon::PLUS]); ?>
	<?php echo $this->layoutHelper->renderLayout('block.icon', ['icon' => Icon::PRINTING]); ?>
	<?php echo $this->layoutHelper->renderLayout('block.icon', ['icon' => Icon::CALENDAR]); ?>
	<?php echo $this->layoutHelper->renderLayout('block.icon', ['icon' => Icon::NEXT]); ?>
	<?php echo $this->layoutHelper->renderLayout('block.icon', ['icon' => Icon::BACK]); ?>
	<?php echo $this->layoutHelper->renderLayout('block.icon', ['icon' => Icon::USERS]); ?>
	<?php echo $this->layoutHelper->renderLayout('block.icon', ['icon' => Icon::MONEY]); ?>
	<?php echo $this->layoutHelper->renderLayout('block.icon', ['icon' => Icon::FILTER]); ?>
</div>
