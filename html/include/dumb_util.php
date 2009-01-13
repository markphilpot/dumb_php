<?php

function displayInstrumentSelect()
{
	global $db;
	
	$result = $db->getAll("select * from dumb_instruments order by instrument_id");
	
	print "<select name='instrument'\n";
	print "<option value='null'>Select Instrument...</option>\n";
	print "<option value='all'>All Instruments</option>\n";
	while( list($temp, $inst) = each($result) )
	{
		print "<option value='".$inst['instrument_id']."'>".$inst['instrument']."</option>\n";
	}
	print "</select>\n";
}

function getInstrument($i)
{
	global $db;
	
	$result = $db->getAll("select * from dumb_instruments where instrument_id = ?", array($i));
	
	list($t, $inst) = each($result);
	
	return $inst['instrument'];
}
?>
