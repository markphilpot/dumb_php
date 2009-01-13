<h1>Officers & Very Important People</h1>
<table border="1" width="100%" id="officers">
  <tr class="table_header">
	<th>Officers</th>
	<th>Drum Majors & Section Leaders</th>
  </tr>
  <tr>
    <td>
    <p>
{section name=i loop=$officers}
	<b>{$officers[i].title}</b> - {$officers[i].firstname} {$officers[i].lastname} 
	({if $officers[i].email ne '0'}{mailto address=$officers[i].email encode="javascript"}{else}&nbsp;{/if})<br />
{/section}
    </p>
    </td>
    <td>
{section name=i loop=$drum_majors}
<b>{$drum_majors[i].title}</b> - {$drum_majors[i].firstname} {$drum_majors[i].lastname} 
	({if $drum_majors[i].email ne '0'}{mailto address=$drum_majors[i].email encode="javascript"}{else}&nbsp;{/if})<br />
{/section}
<br />
{section name=i loop=$section_leaders}
<b>{$section_leaders[i].title}</b> - {$section_leaders[i].firstname} {$section_leaders[i].lastname} 
	({if $section_leaders[i].email ne '0'}{mailto address=$section_leaders[i].email encode="javascript"}{else}&nbsp;{/if})<br />
{/section}
    </td>
  </tr>
</table>