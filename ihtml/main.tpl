{include file="libs/header.tpl"}

<div id="main" class="box">

{include file="libs/banner.tpl"}

<div id="tabs" class="noprint">
{include file="libs/menu.tpl"}
</div> <!-- end tabs -->

<div id="page" class="box">
<div id="page-in" class="box">

<div id="strip" class="box noprint">
	<p id="breadcrumbs">You are here: <strong>{$breadcrumb}</strong></p>
	<hr class="noscreen" />
</div> <!-- end strip -->

<div id="content">
{include file=$include_file}

<!-- <div style="clear: both;">&nbsp;</div> -->

</div> <!-- end content -->

<div id="col" class="noprint">
<div id="col-in">
{if isset($sidebar)}
	{include file=$sidebar}
{/if}

<h3><span>Links</span></h3>
<ul id="links">
	<li><a href="http://www.dukealumni.com/olc/pub/DUKE/cpages/affinities/home.jsp?chapter=45&org=DUKE">DUMB Alumni</a></li> 
    <li><a href="http://www.goduke.com">GoDuke.com</a></li>
    <li><a href="http://maps.duke.edu">Duke Maps</a></li> 
</ul>
<hr class="noscreen">

</div> <!-- end col-in -->
</div> <!-- end col -->

</div> <!-- end page-in -->
</div> <!-- end page -->

{include file="libs/copyright.tpl"}


</div> <!-- end main -->

{include file="libs/footer.tpl"}