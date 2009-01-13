<?php
require_once "include/dumb_util.php";
require_once "include/html_util.php";
global $db;

$func = "permissions";


?>
<div id="options">
<h3 class="center">&nbsp;</h3>
<div class="border">
<h4 class="center">Website Setup</h4>
<p>&#149; <a href="members.php?loc=setup&func=permisisons">Permissions</a><br />
<hr width="75%" size="1" />
</p>
</div>
</div> <!-- end options -->

<div id="main_app">

<?php

if($func == "permissions")
{
   echo "<h3 class='center'>Officer Permissions</h3>";
   
   startTable(0,1,1,"100%");
   startClassRow("table_header");;
   startHeaderColumn();
   echo "Officer";
   nextHeaderColumn();
   echo "Permissions";
   endHeaderColumn();
   endRow();
   
   $officers = $db->getAll("select * from dumb_officers");
   $i=0;
   while(list($tmp, $off_row) = each($officers))
   {
      if($i%2 != 0)
         startClassRow("table_alt");
      else
         startRow();
         
      startColumn();
      echo $off_row['title'];
      nextColumn();
      
      $priv = $db->getAll("select * from dumb_officer_priv where officer_id = ?", array($off_row['officer_id']));
      
      while(list($tmp, $priv_row) = each($priv))
      {
         echo $priv_row['priv']."<br />";
      }
      $i++;
      endColumn();
      endRow();
   }
   
   endTable();   
}

?>
</div> <!-- end main app -->