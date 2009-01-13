<div id="front_content">
{section name=i loop=$front}
<div class="front_entry">
<div class="title">{$front[i].title}</div>
<div class="date">{$front[i].date|date_format:"%A, %B %e, %Y"}</div>
<div class="content">{$front[i].content}</div>
</div> <!-- end front_entry -->
{/section}
</div> <!-- end front_content -->