<?php

function expandDate($mysql_date)
{
	$date_array = explode("-",$mysql_date);
	$ret = array ( "Year"=>$date_array[0], "Month"=>$date_array[1], "Day"=>$date_array[2] );
	return $ret;
}

//////////////
// TABLE	//
//////////////
function startTable($cp, $sp, $b, $w)
{
	echo "<table width=\"".$w."\" border=\"".$b."\" cellspacing=\"".
					$sp."\" cellpadding=\"".$cp."\">\n";
}

function startColumn()
{
	echo "<td>\n";
}

function startColumnVA($va)
{
	echo "<td valign='$va'>\n";
}

function nextColumn()
{
	echo "</td>\n";
	echo "<td>\n";
}

function nextClassColumn($class)
{
	echo "</td>\n";
	echo "<td class='".$class."'>\n";
}

function endColumn()
{
	echo "</td>\n";
}

function startRow()
{
	echo "<tr>\n";
}

function nextRow()
{
	echo "</tr>\n";
	echo "<tr>\n";
}

function endRow()
{
	echo "</tr>\n";
}

function endTable()
{
	echo "</table>\n";
}

function startColorRow($color)
{
	echo "<tr bgcolor='".$color."'>\n";
}

function startClassRow($class)
{
	echo "<tr class='".$class."'>\n";
}

function startColorColumn($color)
{
	echo "<td bgcolor='".$color."'>\n";
}

function startClassColumn($class)
{
	echo "<td class='".$class."'>\n";
}

function startHeaderColumn()
{
	echo "<th>\n";
}

function startHeaderClassColumn($class)
{
	echo "<th class='$class'>\n";
}

function nextHeaderColumn()
{
	echo "</th><th>\n";
}

function endHeaderColumn()
{
	echo "</th>\n";
}

function startColumnColSpan($c)
{
	echo "<td colspan='".$c."'>\n";
}
?>
