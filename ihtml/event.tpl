{include file="libs/header.tpl"}

{section name=i loop=$event}
<div id="front_content">
{if $which == "calendar"}

<div class="title">{$event[i].title}</div>
<div class="date">{$event[i].date|date_format:"%A, %B %e, %Y"}</div>
<div class="content">{$event[i].details}</div>

{else}

<div class="title">{$event[i].name}</div>
<div class="date">{$event[i].date|date_format:"%A, %B %e, %Y"}<br />&nbsp;::&nbsp;{$event[i].time}</div>
<table>
   <tr>
      <th class="form_header">Opponent</th>
      <td>{$event[i].opponent}</td>
   </tr>
   <tr>
      <th class="form_header">Location</th>
      <td>{$event[i].location}</td>
   </tr>
   <tr>
      <th class="form_header">Pepband</th>
      <td>{$event[i].pepband}</td>
   </tr>
   <tr>
      <th class="form_header">TV</th>
      <td>{$event[i].tv}</td>
   </tr>
   <tr>
      <th class="form_header">Details</th>
      <td>{$event[i].details}</td>
   </tr>
</table>

{/if}
</div> <!-- end front_content -->
{/section}

{include file="libs/footer.tpl"}