{section name=i loop=$front}
<div class="article">
<h2><span>{$front[i].title}</span></h2>

<p class="info noprint>
	<span class="date">{$front[i].date|date_format:"%A, %B %e, %Y"}</span><span class="noscreen">,</span>
</p>

{$front[i].content}

</div> <!-- end article -->
<hr class="noscreen">
{/section}