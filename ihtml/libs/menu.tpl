<h3 class="noscreen">Navigation</h3>

<ul class="box">
	<li{if $loc == 'home'} id="active"{/if}><a href="index.php">Home<span class="tab-l"></span><span class="tab-r"></span></a></li>
	<li{if $loc == 'current'} id="active"{/if}><a href="current.php">Prospective Members<span class="tab-l"></span><span class="tab-r"></span></a></li>
	<li{if $loc == 'about'} id="active"{/if}><a href="about.php">About Us<span class="tab-l"></span><span class="tab-r"></span></a></li>
	<li{if $loc == 'leadership'} id="active"{/if}><a href="leadership.php">Leadership<span class="tab-l"></span><span class="tab-r"></span></a></li>
  <li{if $loc == 'calendar'} id="active"{/if}><a href="calendar.php">Calendar<span class="tab-l"></span><span class="tab-r"></span></a></li>
	<li{if $loc == 'login'} id="active"{/if}><a href="javascript:Login();">Login<span class="tab-l"></span><span class="tab-r"></span></a></li>
</ul>

<hr class="noscreen" />