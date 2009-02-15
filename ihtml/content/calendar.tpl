{literal}
<script type="text/javascript">
<!--
function Open(x)
{
   window.open('event.php?'+x,'Open','menubar=no,scrollbars=yes,resizable=yes,toolbar=no,width=400,height=450');
}
// -->
</script>
{/literal}
{php}
//require_once 'Date/Date.php';
/*
require_once 'Calendar/Decorator/Uri.php';
require_once 'Calendar/Month/Weekdays.php';
require_once 'Calendar/Day.php';
require_once 'include/html_util.php';

$week_names = array( 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

global $t;

$month = $t->get_template_vars('month');
$year = $t->get_template_vars('year');
$day = 1;
$events = $t->get_template_vars('events');

$date = new Date();
$date->setYear( $year );
$date->setMonth( $month );
$date->setDay( $day );

$calendar = new Calendar_Month_Weekdays($year, $month, 0); // 0 for Sunday start of week
$calendar->build();

$day = new Calendar_Day( $year, $month, $day );
$Uri = & new Calendar_Decorator_Uri($day);
$Uri->setFragments('year', 'month');

print "<div id='calendar'>\n";

// Output header
print "<h1 class='center'>". $date->getMonthName() .", ". $date->getYear() ."</h1>";
print "<p class='center'><a href='calendar.php?". $Uri->prev('month') ."'>previous</a> - ";
print "<a href='calendar.php?". $Uri->next('month') ."'>next</a></p>";

startTable(1, 1, 0, '100%');

startClassRow("table_header");
for( $i = 0; $i < count($week_names); $i++ )
{
	startHeaderClassColumn("center");
	print $week_names[$i];
	endHeaderColumn();
}
endRow();

while( $Day = $calendar->fetch() )
{
	if( $Day->isFirst() )
	{
		// Sunday... start row
		startRow();
	}
		
	if( $Day->isEmpty() )
	{
		startColumn();
		print "&nbsp;";
		endColumn();
	}
	else
	{
		startColumn();
		
		startTable(0,0,0,'100%');
		startRow();
		startHeaderClassColumn("left content");
		print $Day->thisDay();
		endHeaderColumn();
		startHeaderClassColumn("content");
		print "&nbsp;";
		endHeaderColumn();
		endRow();
		
		startRow();
		startClassColumn("content");
		print "&nbsp;";
		nextClassColumn("content");
		
		// Insert Content
		$entry = new Date( $Day->thisDay(true) );
		
		if( array_key_exists( $entry->format('%Y-%m-%d'), $events) )
		{
			$this_date =& $events[$entry->format('%Y-%m-%d')];
			
			print "<p>";
			while( list($temp, $e) = each($this_date) )
			{
				if( array_key_exists('calendar_id', $e ) )
				{
					print "&#149; ";
					print "<a href=\"javascript:Open('cal_id=". $e['calendar_id'] ."');\">";
					print $e['title'] . "</a><br />";
				}
				else
				{
					print "&#149; ";
					print "<a href=\"javascript:Open('sch_id=". $e['schedule_id'] ."');\">";
					print $e['category'] . " vs. " . $e['opponent'] . "</a><br />";
				}
			}
			print "</p>";
		}
		else
		{
			print "&nbsp;";
		}
		
		// End Insert Content
		
		endColumn();
		endRow();
		endTable();
		
		endColumn();
	}
	
	if( $Day->isLast() )
	{
		// Saturday... end row
		endRow();
	}
}

endTable();

print "</div> <!-- end calendar -->"
*/
{/php}
<div id='calendar'>
<iframe src="//www.google.com/calendar/embed?
title=Duke%20Band%20Calendar&amp;height=600&amp;wkst=1&amp;bgcolor=%23ffffff&amp;src=dukeathleticbands%40gmail.com&amp;color=%2329527A
&amp;ctz=America%2FNew_York" style=" border-width:0 " width="740" height="600" frameborder="0" scrolling="no"></iframe>
</div>