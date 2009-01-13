<h1>Roster</h1>
<table border="1" width="100%" id="roster">
  <tr class="table_header">
	<th><a href="roster.php?orderby=firstname">First Name</a></th>
	<th><a href="roster.php?orderby=lastname">Last Name</a></th>
	<th><a href="roster.php?orderby=instrument">Instrument</a></th>
	<th><a href="roster.php?orderby=pepband">Pep Band</a></th>
	<th><a href="roster.php?orderby=year">Year</a></th>
	<th>Email</th>
  </tr>
{php}
$i = 0;
{/php}
{section name=i loop=$roster}
  <tr>
  {if ($smarty.section.i.index % 2) == 0}
	<td>{$roster[i].firstname}</td>
	<td>{$roster[i].lastname}</td>
	<td>{$roster[i].instrument}</td>
	<td>{$roster[i].pepband}</td>
	<td>{$roster[i].year}</td>
	<td>{if $roster[i].email ne '0'}{mailto address=$roster[i].email encode="javascript"}{else}&nbsp;{/if}</td>
  {else}
	<td class="table_alt">{$roster[i].firstname}</td>
	<td class="table_alt">{$roster[i].lastname}</td>
	<td class="table_alt">{$roster[i].instrument}</td>
	<td class="table_alt">{$roster[i].pepband}</td>
	<td class="table_alt">{$roster[i].year}</td>
	<td class="table_alt">{if $roster[i].email ne '0'}{mailto address=$roster[i].email encode="javascript"}{else}&nbsp;{/if}</td>
  {/if}
  </tr>
{/section}
</table>