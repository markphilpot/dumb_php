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

{section name=i loop=$front}
<div class="article">
<h2><span>{$front[i].title}</span></h2>

{$front[i].content}

</div> <!-- end article -->
<hr class="noscreen">
{/section}