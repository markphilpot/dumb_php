<?php
require_once "Spreadsheet/Excel/Writer.php";
require_once "include/database.php";

class selection
{
	/*
	 * Selection sheets options
	 *  - TP = Total Points
	 *  - I = Instrument
	 *  - E = Expierence
	 */
	var $FRESHMEN_TP = 1;
	var $FRESHMEN_ITP = 2;
	var $SOPH_TP = 3;
	var $SOPH_ITP = 4;
	var $JUNIOR_TP = 5;
	var $JUNIOR_ITP = 6;
	var $SENIOR_TP = 7;
	var $SENIOR_ITP = 8;
	
	var $OP1_ETP = 10;
	var $OP1_IETP = 11;
	var $OP2_ETP = 12;
	var $OP2_IETP = 13;
	var $OP3_ETP = 14;
	var $OP3_IETP = 15;
	var $OP4_ETP = 16;
	var $OP4_IETP = 17;
	var $OP5_ETP = 18;
	var $OP5_IETP = 19;
	
	var $COMMENTS = 20;
	var $ALL_IETP = 21;
	var $ALL_ITP  = 22; // No tournament data
	
	/*
	 * Private
	 */
	var $query = '';
	var $sheet = -1;
	
	/*
	 * Queries - Initialized on construction
	 */
	var $year_tp;
	var $year_itp;
	var $all;
	var $allexp;
	
	/*
	 * Where Clauses
	 */
	var $year;
	var $option;
	
	function selection()
	{
		$this->initialize();
	}
	
	
	/*
	 * Generate Excel file
	 * 
	 * @return Excel File
	 */
	function generate_excel_workbook()
	{
		/*
		 * Retrieve all information ahead of time to make sure there
		 * are no errors.  Die if there are.
		 */
		global $db;
		
		 /*
		  * Create Workbook
		  */
		$workbook = new Spreadsheet_Excel_Writer();
		
		/*
		 * Creat formats
		 */
		$bold =& $workbook->addFormat();
		$bold->setBold();
		
		// Send HTTP headers
		$workbook->send("selection.xls");
		
		// All Utility sheet
		$ws_all =& $workbook->addWorksheet('All by ITP');
		$ws_all->write(0,0, 'Name', $bold);
		$ws_all->write(0,1, 'Instrument', $bold);
		$ws_all->write(0,2, 'Points', $bold);
		
		$results = $db->getAll($this->all);
		$i = 1;
		while( list($temp, $row) = each($results) )
		{
			$j = 0;
			$name = $row['lastname'].", ".$row['firstname'];
			$ws_all->write($i,$j++, $name);
			$ws_all->write($i,$j++, $row['instrument']);
			$ws_all->write($i,$j++, $row['points']);
			$i++;
		}
		
		// All Utility sheet (w/ Experience)
		$ws_all =& $workbook->addWorksheet('All by IETP');
		$ws_all->write(0,0, 'Name', $bold);
		$ws_all->write(0,1, 'Expierence', $bold);
		$ws_all->write(0,2, 'Instrument', $bold);
		$ws_all->write(0,3, 'Points', $bold);
		for($o = 1; $o <= 8; $o++)
		{
			$ws_all->write(0,3+$o, 'Option '.$o.' Rank', $bold);
		}
		
		$results = $db->getAll($this->alleo);
		$i = 1;
		while( list($temp, $row) = each($results) )
		{
			$j = 0;
			$name = $row['lastname'].", ".$row['firstname'];
			$ws_all->write($i,$j++, $name);
			$ws_all->write($i,$j++, $row['exp']);
			$ws_all->write($i,$j++, $row['instrument']);
			$ws_all->write($i,$j++, $row['points']);
			for($o = 1; $o <= 8; $o++)
			{
				$option = "option_".$o;
				$ws_all->write($i, $j++, $row[$option]);
			}
			$i++;
		}
		
		//Freshmen by TP
//		$ws_all =& $workbook->addWorksheet('Freshmen by TP');
//		$ws_all->write(0,0, 'Name', $bold);
//		$ws_all->write(0,1, 'Expierence', $bold);
//		$ws_all->write(0,2, 'Instrument', $bold);
//		$ws_all->write(0,3, 'Prefer', $bold);
//		$ws_all->write(0,4, 'Abroad', $bold);
//		$ws_all->write(0,5, 'Joined', $bold);
//		$ws_all->write(0,6, 'Points', $bold);
//		
//		$results = $db->getAll($this->year_tp, array(1));
//		$i = 1;
//		while( list($temp, $row) = each($results) )
//		{
//			$j = 0;
//			$name = $row['lastname'].", ".$row['firstname'];
//			$ws_all->write($i,$j++, $name);
//			$ws_all->write($i,$j++, $row['exp']);
//			$ws_all->write($i,$j++, $row['instrument']);
//			$ws_all->write($i,$j++, $row['prefer']);
//			$ws_all->write($i,$j++, $row['abroad']);
//			$ws_all->write($i,$j++, $row['joined']);
//			$ws_all->write($i,$j++, $row['points']);
//			$i++;
//		}
//		
//		//Freshmen by ITP
//		$ws_all =& $workbook->addWorksheet('Freshmen by ITP');
//		$ws_all->write(0,0, 'Name', $bold);
//		$ws_all->write(0,1, 'Expierence', $bold);
//		$ws_all->write(0,2, 'Instrument', $bold);
//		$ws_all->write(0,3, 'Prefer', $bold);
//		$ws_all->write(0,4, 'Abroad', $bold);
//		$ws_all->write(0,5, 'Joined', $bold);
//		$ws_all->write(0,6, 'Points', $bold);
//		
//		$results = $db->getAll($this->year_itp, array(1));
//		$i = 1;
//		while( list($temp, $row) = each($results) )
//		{
//			$j = 0;
//			$name = $row['lastname'].", ".$row['firstname'];
//			$ws_all->write($i,$j++, $name);
//			$ws_all->write($i,$j++, $row['exp']);
//			$ws_all->write($i,$j++, $row['instrument']);
//			$ws_all->write($i,$j++, $row['prefer']);
//			$ws_all->write($i,$j++, $row['abroad']);
//			$ws_all->write($i,$j++, $row['joined']);
//			$ws_all->write($i,$j++, $row['points']);
//			$i++;
//		}
//		
//		//Soph by TP
//		$ws_all =& $workbook->addWorksheet('Soph by TP');
//		$ws_all->write(0,0, 'Name', $bold);
//		$ws_all->write(0,1, 'Expierence', $bold);
//		$ws_all->write(0,2, 'Instrument', $bold);
//		$ws_all->write(0,3, 'Prefer', $bold);
//		$ws_all->write(0,4, 'Abroad', $bold);
//		$ws_all->write(0,5, 'Joined', $bold);
//		$ws_all->write(0,6, 'Points', $bold);
//		
//		$results = $db->getAll($this->year_tp, array(2));
//		$i = 1;
//		while( list($temp, $row) = each($results) )
//		{
//			$j = 0;
//			$name = $row['lastname'].", ".$row['firstname'];
//			$ws_all->write($i,$j++, $name);
//			$ws_all->write($i,$j++, $row['exp']);
//			$ws_all->write($i,$j++, $row['instrument']);
//			$ws_all->write($i,$j++, $row['prefer']);
//			$ws_all->write($i,$j++, $row['abroad']);
//			$ws_all->write($i,$j++, $row['joined']);
//			$ws_all->write($i,$j++, $row['points']);
//			$i++;
//		}
//		
//		//Soph by ITP
//		$ws_all =& $workbook->addWorksheet('Soph by ITP');
//		$ws_all->write(0,0, 'Name', $bold);
//		$ws_all->write(0,1, 'Expierence', $bold);
//		$ws_all->write(0,2, 'Instrument', $bold);
//		$ws_all->write(0,3, 'Prefer', $bold);
//		$ws_all->write(0,4, 'Abroad', $bold);
//		$ws_all->write(0,5, 'Joined', $bold);
//		$ws_all->write(0,6, 'Points', $bold);
//		
//		$results = $db->getAll($this->year_itp, array(2));
//		$i = 1;
//		while( list($temp, $row) = each($results) )
//		{
//			$j = 0;
//			$name = $row['lastname'].", ".$row['firstname'];
//			$ws_all->write($i,$j++, $name);
//			$ws_all->write($i,$j++, $row['exp']);
//			$ws_all->write($i,$j++, $row['instrument']);
//			$ws_all->write($i,$j++, $row['prefer']);
//			$ws_all->write($i,$j++, $row['abroad']);
//			$ws_all->write($i,$j++, $row['joined']);
//			$ws_all->write($i,$j++, $row['points']);
//			$i++;
//		}
//		
//		//Junior by TP
//		$ws_all =& $workbook->addWorksheet('Junior by TP');
//		$ws_all->write(0,0, 'Name', $bold);
//		$ws_all->write(0,1, 'Expierence', $bold);
//		$ws_all->write(0,2, 'Instrument', $bold);
//		$ws_all->write(0,3, 'Prefer', $bold);
//		$ws_all->write(0,4, 'Abroad', $bold);
//		$ws_all->write(0,5, 'Joined', $bold);
//		$ws_all->write(0,6, 'Points', $bold);
//		
//		$results = $db->getAll($this->year_tp, array(3));
//		$i = 1;
//		while( list($temp, $row) = each($results) )
//		{
//			$j = 0;
//			$name = $row['lastname'].", ".$row['firstname'];
//			$ws_all->write($i,$j++, $name);
//			$ws_all->write($i,$j++, $row['exp']);
//			$ws_all->write($i,$j++, $row['instrument']);
//			$ws_all->write($i,$j++, $row['prefer']);
//			$ws_all->write($i,$j++, $row['abroad']);
//			$ws_all->write($i,$j++, $row['joined']);
//			$ws_all->write($i,$j++, $row['points']);
//			$i++;
//		}
//		
//		//Junior by ITP
//		$ws_all =& $workbook->addWorksheet('Junior by ITP');
//		$ws_all->write(0,0, 'Name', $bold);
//		$ws_all->write(0,1, 'Expierence', $bold);
//		$ws_all->write(0,2, 'Instrument', $bold);
//		$ws_all->write(0,3, 'Prefer', $bold);
//		$ws_all->write(0,4, 'Abroad', $bold);
//		$ws_all->write(0,5, 'Joined', $bold);
//		$ws_all->write(0,6, 'Points', $bold);
//		
//		$results = $db->getAll($this->year_itp, array(3));
//		$i = 1;
//		while( list($temp, $row) = each($results) )
//		{
//			$j = 0;
//			$name = $row['lastname'].", ".$row['firstname'];
//			$ws_all->write($i,$j++, $name);
//			$ws_all->write($i,$j++, $row['exp']);
//			$ws_all->write($i,$j++, $row['instrument']);
//			$ws_all->write($i,$j++, $row['prefer']);
//			$ws_all->write($i,$j++, $row['abroad']);
//			$ws_all->write($i,$j++, $row['joined']);
//			$ws_all->write($i,$j++, $row['points']);
//			$i++;
//		}
//		
//		//Senior by TP
//		$ws_all =& $workbook->addWorksheet('Senior by TP');
//		$ws_all->write(0,0, 'Name', $bold);
//		$ws_all->write(0,1, 'Expierence', $bold);
//		$ws_all->write(0,2, 'Instrument', $bold);
//		$ws_all->write(0,3, 'Prefer', $bold);
//		$ws_all->write(0,4, 'Abroad', $bold);
//		$ws_all->write(0,5, 'Joined', $bold);
//		$ws_all->write(0,6, 'Points', $bold);
//		
//		$results = $db->getAll($this->year_tp, array(4));
//		$i = 1;
//		while( list($temp, $row) = each($results) )
//		{
//			$j = 0;
//			$name = $row['lastname'].", ".$row['firstname'];
//			$ws_all->write($i,$j++, $name);
//			$ws_all->write($i,$j++, $row['exp']);
//			$ws_all->write($i,$j++, $row['instrument']);
//			$ws_all->write($i,$j++, $row['prefer']);
//			$ws_all->write($i,$j++, $row['abroad']);
//			$ws_all->write($i,$j++, $row['joined']);
//			$ws_all->write($i,$j++, $row['points']);
//			$i++;
//		}
//		
//		//Senior by ITP
//		$ws_all =& $workbook->addWorksheet('Senior by ITP');
//		$ws_all->write(0,0, 'Name', $bold);
//		$ws_all->write(0,1, 'Expierence', $bold);
//		$ws_all->write(0,2, 'Instrument', $bold);
//		$ws_all->write(0,3, 'Prefer', $bold);
//		$ws_all->write(0,4, 'Abroad', $bold);
//		$ws_all->write(0,5, 'Joined', $bold);
//		$ws_all->write(0,6, 'Points', $bold);
//		
//		$results = $db->getAll($this->year_itp, array(4));
//		$i = 1;
//		while( list($temp, $row) = each($results) )
//		{
//			$j = 0;
//			$name = $row['lastname'].", ".$row['firstname'];
//			$ws_all->write($i,$j++, $name);
//			$ws_all->write($i,$j++, $row['exp']);
//			$ws_all->write($i,$j++, $row['instrument']);
//			$ws_all->write($i,$j++, $row['prefer']);
//			$ws_all->write($i,$j++, $row['abroad']);
//			$ws_all->write($i,$j++, $row['joined']);
//			$ws_all->write($i,$j++, $row['points']);
//			$i++;
//		}
							
		$option_p1 = "SELECT sum(dumb_sec_codes.points) AS points," .
				"dumb_members.username, dumb_members.firstname, dumb_members.lastname, " .
				"dumb_instruments.instrument, dumb_tournament_form.exp, dumb_tournament_form.prefer, " .
				"dumb_tournament_form.abroad, dumb_tournament_form.joined, dumb_tournament_form.option_";
		$option_p12 = " FROM dumb_members, dumb_instruments, dumb_sec_codes, dumb_sec_attendance, dumb_tournament_form " .
				"WHERE dumb_members.username = dumb_sec_attendance.username " .
				"AND dumb_sec_attendance.code_id = dumb_sec_codes.code_id " .
				"AND dumb_members.instrument_id = dumb_instruments.instrument_id " .
				"AND dumb_members.username = dumb_tournament_form.username " .
				"AND dumb_tournament_form.option_";
		$option_p2ept = " > 0 GROUP BY dumb_members.username " .
				"ORDER BY dumb_tournament_form.exp DESC, points DESC";
		$option_p2iept = " > 0 GROUP BY dumb_members.username " .
				"ORDER BY dumb_instruments.instrument, dumb_tournament_form.exp DESC, points DESC";
		
		for($k = 1; $k <= 8; $k++)
		{
			//Opiton x by ETP
			$title = "Option $k by ETP";
			$ws_all =& $workbook->addWorksheet($title);
			$ws_all->write(0,0, 'Name', $bold);
			$ws_all->write(0,1, 'Expierence', $bold);
			$ws_all->write(0,2, 'Instrument', $bold);
			$ws_all->write(0,3, 'Prefer', $bold);
			$ws_all->write(0,4, 'Abroad', $bold);
			$ws_all->write(0,5, 'Joined', $bold);
			$ws_all->write(0,6, 'Option Rank', $bold);
			$ws_all->write(0,7, 'Points', $bold);
			
			$query = $option_p1.$k.$option_p12.$k.$option_p2ept;
			$option = "option_".$k;
			
			$results = $db->getAll($query);
			$i = 1;
			while( list($temp, $row) = each($results) )
			{
				$j = 0;
				$name = $row['lastname'].", ".$row['firstname'];
				$ws_all->write($i,$j++, $name);
				$ws_all->write($i,$j++, $row['exp']);
				$ws_all->write($i,$j++, $row['instrument']);
				$ws_all->write($i,$j++, $row['prefer']);
				$ws_all->write($i,$j++, $row['abroad']);
				$ws_all->write($i,$j++, $row['joined']);
				$ws_all->write($i,$j++, $row[$option]);
				$ws_all->write($i,$j++, $row['points']);
				$i++;
			}
			
			//Option x by IETP
			$title = "Option $k by IETP";
			$ws_all =& $workbook->addWorksheet($title);
			$ws_all->write(0,0, 'Name', $bold);
			$ws_all->write(0,1, 'Expierence', $bold);
			$ws_all->write(0,2, 'Instrument', $bold);
			$ws_all->write(0,3, 'Prefer', $bold);
			$ws_all->write(0,4, 'Abroad', $bold);
			$ws_all->write(0,5, 'Joined', $bold);
			$ws_all->write(0,6, 'Option Rank', $bold);
			$ws_all->write(0,7, 'Points', $bold);
			
			$query = $option_p1.$k.$option_p12.$k.$option_p2iept;
			$option = "option_".$k;
			
			$results = $db->getAll($query);
			$i = 1;
			while( list($temp, $row) = each($results) )
			{
				$j = 0;
				$name = $row['lastname'].", ".$row['firstname'];
				$ws_all->write($i,$j++, $name);
				$ws_all->write($i,$j++, $row['exp']);
				$ws_all->write($i,$j++, $row['instrument']);
				$ws_all->write($i,$j++, $row['prefer']);
				$ws_all->write($i,$j++, $row['abroad']);
				$ws_all->write($i,$j++, $row['joined']);
				$ws_all->write($i,$j++, $row[$option]);
				$ws_all->write($i,$j++, $row['points']);
				$i++;
			}
		}
		
		//Opiton 6
//		$ws_all =& $workbook->addWorksheet('Option 6');
//		$ws_all->write(0,0, 'Name', $bold);
//		$ws_all->write(0,1, 'Expierence', $bold);
//		$ws_all->write(0,2, 'Instrument', $bold);
//		$ws_all->write(0,3, 'Prefer', $bold);
//		$ws_all->write(0,4, 'Abroad', $bold);
//		$ws_all->write(0,5, 'Joined', $bold);
//		$ws_all->write(0,6, 'Points', $bold);
//		
//		$query = $option_p1."6".$option_p12."6".$option_p2iept;
//		$option = "option_6";
//		
//		$results = $db->getAll($query);
//		$i = 1;
//		while( list($temp, $row) = each($results) )
//		{
//			$j = 0;
//			$name = $row['lastname'].", ".$row['firstname'];
//			$ws_all->write($i,$j++, $name);
//			$ws_all->write($i,$j++, $row['exp']);
//			$ws_all->write($i,$j++, $row['instrument']);
//			$ws_all->write($i,$j++, $row['prefer']);
//			$ws_all->write($i,$j++, $row['abroad']);
//			$ws_all->write($i,$j++, $row['joined']);
//			$ws_all->write($i,$j++, $row['points']);
//			$i++;
//		}

		//Comments
		$query = "SELECT * from dumb_members, dumb_tournament_form WHERE dumb_members.username = dumb_tournament_form.username " .
				"ORDER BY dumb_members.lastname";
				
		$ws_all =& $workbook->addWorksheet('Comments');
		$ws_all->write(0,0, 'Name', $bold);
		$ws_all->write(0,1, 'Comments', $bold);
		$ws_all->write(0,2, 'Phone', $bold);
		
		$results = $db->getAll($query);
		$i = 1;
		while( list($temp, $row) = each($results) )
		{
			$j = 0;
			$name = $row['lastname'].", ".$row['firstname'];
			$ws_all->write($i,$j++, $name);
			$ws_all->write($i,$j++, $row['comments']);
			$ws_all->write($i,$j++, $row['phone']);
			$i++;
		}
		
		// Absenses, Late, Tardy
		// -- Note: This will have to be tailored each year depending on if there
		// have been changes to the sec_codes
		$codes = $db->getAll("select * from dumb_members, dumb_sec_attendance, dumb_sec_codes where dumb_members.username = dumb_sec_attendance.username and dumb_sec_codes.code_id = dumb_sec_attendance.code_id");
		
		$absences = array();
		$lates = array();
		$tardies = array();
		
		while( list($temp, $row) = each($codes) )
		{
			$username = $row['username'];
			$code = $row['code'];
			
			if(preg_match("/UA.*/", $code))
			{
				if(array_key_exists($username, $absences))
				{
					$absences[$username]++;
				}
				else
				{
					$absences[$username] = 1;
				}
			}
			else if(preg_match("/UL.*/", $code))
			{
				if(array_key_exists($username, $lates))
				{
					$lates[$username]++;
				}
				else
				{
					$lates[$username] = 1;
				}
			}
			else if(preg_match("/UT.*/", $code))
			{
				if(array_key_exists($username, $tardies))
				{
					$tardies[$username]++;
				}
				else
				{
					$tardies[$username] = 1;
				}
			}
		}
		
		$members = $db->getAll("select sum(dumb_sec_codes.points) AS points, " .
				"dumb_members.username, dumb_members.lastname, dumb_members.firstname " .
				"from dumb_members, dumb_sec_codes, dumb_sec_attendance " .
				"where dumb_members.username = dumb_sec_attendance.username " .
				"AND dumb_sec_codes.code_id = dumb_sec_attendance.code_id " .
				"GROUP BY dumb_members.username ORDER BY dumb_members.lastname");
		
		$ws_all =& $workbook->addWorksheet('Absenses, Lates, Tardies');
		$ws_all->write(0,0, 'Name', $bold);
		$ws_all->write(0,1, 'Points', $bold);
		$ws_all->write(0,2, 'Absences', $bold);
		$ws_all->write(0,3, 'Lates', $bold);
		$ws_all->write(0,4, 'Tardies', $bold);
		
		$i = 1;
		while( list($tmp, $row) = each($members) )
		{
			$j = 0;
			$username = $row['username'];
			$name = $row['lastname'].", ".$row['firstname'];
			$ws_all->write($i, $j++, $name);
			$ws_all->write($i, $j++, $row['points']);
			$ws_all->write($i, $j++, $absences[$username]);
			$ws_all->write($i, $j++, $lates[$username]);
			$ws_all->write($i, $j++, $tardies[$username]);
			$i++;			
		}
			
		// Close file
		$workbook->close();
	}
	
	/*
	 * Initialize Query Fields
	 */
	function initialize()
	{
		$this->year_tp = "SELECT " .
			"sum(dumb_sec_codes.points) AS points, " .
			"dumb_members.username, " .
			"dumb_members.firstname, " .
			"dumb_members.lastname, " .
			"dumb_instruments.instrument, " .
			"dumb_tournament_form.prefer, " .
			"dumb_tournament_form.exp, " .
			"dumb_tournament_form.abroad, " .
			"dumb_tournament_form.joined " .
			"FROM " .
			"dumb_members, " .
			"dumb_instruments, " .
			"dumb_sec_codes, " .
			"dumb_sec_attendance, " .
			"dumb_tournament_form " .
			"WHERE " .
			"dumb_members.username = dumb_sec_attendance.username AND " .
			"dumb_members.instrument_id = dumb_instruments.instrument_id AND " .
			"dumb_sec_attendance.code_id = dumb_sec_codes.code_id AND " .
			"dumb_members.username = dumb_tournament_form.username AND " .
			"dumb_tournament_form.exp = ? " .
			"GROUP BY dumb_members.username " .
			"ORDER BY points desc";
		
		$this->year_itp = "SELECT " .
			"sum(dumb_sec_codes.points) AS points, " .
			"dumb_members.username, " .
			"dumb_members.firstname, " .
			"dumb_members.lastname, " .
			"dumb_instruments.instrument, " .
			"dumb_tournament_form.prefer, " .
			"dumb_tournament_form.exp, " .
			"dumb_tournament_form.abroad, " .
			"dumb_tournament_form.joined " .
			"FROM " .
			"dumb_members, " .
			"dumb_instruments, " .
			"dumb_sec_codes, " .
			"dumb_sec_attendance, " .
			"dumb_tournament_form " .
			"WHERE " .
			"dumb_members.username = dumb_sec_attendance.username AND " .
			"dumb_members.instrument_id = dumb_instruments.instrument_id AND " .
			"dumb_sec_attendance.code_id = dumb_sec_codes.code_id AND " .
			"dumb_members.username = dumb_tournament_form.username AND " .
			"dumb_tournament_form.exp = ? " .
			"GROUP BY dumb_members.username " .
			"ORDER BY dumb_instruments.instrument, points desc";
		
		$this->all = "SELECT sum(dumb_sec_codes.points) as points, " .
						"dumb_members.username, dumb_members.firstname, " .
						"dumb_members.lastname, dumb_instruments.instrument " .
						"FROM dumb_members, dumb_sec_codes, dumb_sec_attendance, dumb_instruments " .
						"WHERE " .
						"dumb_members.username = dumb_sec_attendance.username and " .
						"dumb_sec_attendance.code_id = dumb_sec_codes.code_id and " .
						"dumb_members.instrument_id = dumb_instruments.instrument_id " .
						"GROUP BY dumb_members.username " .
						"ORDER BY dumb_instruments.instrument, points desc";
		
		$this->alle = "SELECT sum(dumb_sec_codes.points) as points, " .
						"dumb_members.username, dumb_members.firstname, " .
						"dumb_members.lastname, dumb_instruments.instrument, dumb_tournament_form.exp " .
						"FROM dumb_members, dumb_sec_codes, dumb_sec_attendance, dumb_instruments, dumb_tournament_form " .
						"WHERE " .
						"dumb_members.username = dumb_tournament_form.username and " .
						"dumb_members.username = dumb_sec_attendance.username and " .
						"dumb_sec_attendance.code_id = dumb_sec_codes.code_id and " .
						"dumb_members.instrument_id = dumb_instruments.instrument_id " .
						"GROUP BY dumb_members.username " .
						"ORDER BY dumb_instruments.instrument, dumb_tournament_form.exp desc, points desc";
		
		$this->alleo = "SELECT sum(dumb_sec_codes.points) as points, " .
						"dumb_members.username, dumb_members.firstname, " .
						"dumb_members.lastname, dumb_instruments.instrument, dumb_tournament_form.exp, dumb_tournament_form.option_1, dumb_tournament_form.option_2, dumb_tournament_form.option_3, dumb_tournament_form.option_4, dumb_tournament_form.option_5, dumb_tournament_form.option_6, dumb_tournament_form.option_7, dumb_tournament_form.option_8 " .
						"FROM dumb_members, dumb_sec_codes, dumb_sec_attendance, dumb_instruments, dumb_tournament_form " .
						"WHERE " .
						"dumb_members.username = dumb_tournament_form.username and " .
						"dumb_members.username = dumb_sec_attendance.username and " .
						"dumb_sec_attendance.code_id = dumb_sec_codes.code_id and " .
						"dumb_members.instrument_id = dumb_instruments.instrument_id " .
						"GROUP BY dumb_members.username " .
						"ORDER BY dumb_instruments.instrument, dumb_tournament_form.exp desc, points desc";
	}
};
?>
