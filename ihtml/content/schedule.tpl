<div class="article">
<h2><span>{$category[0].name}</span></h2>
<table border="1" width="100%" id="roster">
  <tr class='table_header'>
	<th>Date</th>
	<th>Opponent</th>
	<th>Location</th>
	<th>Time</th>
	<th>Pepband</th>
	<th>TV</th>
	<th>Details</th>
  </tr>
{php}
$i = 0;
{/php}
{section name=i loop=$schedule}
  <tr>
{php}

if( $i % 2 == 0 )
{
{/php}
	<td>{$schedule[i].date|date_format:"%A %B %e, %Y"}</td>
	<td>{$schedule[i].opponent}</td>
	<td>{$schedule[i].location}</td>
	<td>{$schedule[i].time}</td>
	<td>{$schedule[i].pepband}</td>
	<td>{$schedule[i].tv}</td>
	<td>{$schedule[i].details}</td>
{php}
}
else
{
{/php}
	<td class="table_alt">{$schedule[i].date|date_format:"%A %B %e, %Y"}</td>
	<td class="table_alt">{$schedule[i].opponent}</td>
	<td class="table_alt">{$schedule[i].location}</td>
	<td class="table_alt">{$schedule[i].time}</td>
	<td class="table_alt">{$schedule[i].pepband}</td>
	<td class="table_alt">{$schedule[i].tv}</td>
	<td class="table_alt">{$schedule[i].details}</td>
{php}
}

$i++;
{/php}
  </tr>
{/section}
</table>
</div> <!-- end article -->