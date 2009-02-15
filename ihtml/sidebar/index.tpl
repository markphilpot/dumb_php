<h3><span>News</span></h3>
<ul id="category">
{section name=i loop=$news}
	<li><a href="current.php">{$news[i].title}</a></li>
{/section}
</ul>

<h3><span>Upcoming Events</span></h3>
<ul id="dates">
{section name=i loop=$upcoming}	
	<li><a href="javascript:Open('cal_id={$upcoming[i].calendar_id}');">{$upcoming[i].date|date_format:"%m/%d"} {$upcoming[i].title}</a></li>
{/section}
</ul>

<h3><span>Upcoming Games</span></h3>
<ul id="dates">
{section name=i loop=$events}
	{if $events[i].location eq 'Cameron'} 
	<li><a href="javascript:Open('sch_id={$events[i].schedule_id}');">{$events[i].date|date_format:"%m/%d"} - {$events[i].name} vs. {$events[i].opponent}</a></li>
	{else}
	<li><a href="javascript:Open('sch_id={$events[i].schedule_id}');">{$events[i].date|date_format:"%m/%d"} - {$events[i].name} @ {$events[i].opponent}</a></li>
	{/if}
{/section}
</ul>