<?php
/*
 * Created on Sep 29, 2005
 *
 * Custom Error Handling
 * 
 * Assisted by http://www.zend.com/zend/spotlight/error.php
 */
 
function ErrorHandler( $errno, $errstr, $errfile, $errline )
{
	$error_msg = " $errstr occured in $errfile on line $errline at <i>".date("D M j G:i:s Y")."</i> (Problem has been emailed to admin)";
	$email_address = "admin@mcstudios.net";
	$host = "localhost"; // Pager/dbg host
	$logfile = "";
	
	// Where to send error
	$email = false; // Set to true for production
	$stdlog = true;
	$remote = false;
	$display = true; // Set to false for production
	
	// How to handle error
	$notify = true;
	$halt = true;
	
	switch($errno)
	{
		case E_USER_NOTICE:
		case E_NOTICE:
			
			$halt = false;
			$notify = false;
			$type = "Notice :: ";
			break;
			
		case E_USER_WARNING:
		case E_COMPILE_WARNING:
		case E_CORE_WARNING:
		case E_WARNING:
		
			$halt = false;
			$type = "Warning :: ";
			break;
			
		case E_USER_ERROR:
		case E_COMPILE_ERROR:
		case E_CORE_ERROR:
		case E_ERROR:
		
			$type = "Fatal Error :: ";
			break;
			
		case E_PARSE:
		
			$type = "Parse Error :: ";
			break;
		
		case E_STRICT:
		
			$halt = false;
			$notify = false;
			$type = "Strict Error :: ";
			break;
			
		default:
		
			$type = "Unknown Error ($errno) :: ";
	}
	
	if( $notify )
	{
		$error_msg = "<b>" . $type . "</b>" . $error_msg;
		
		if( $email ) error_log($error_msg, 1, $email_address);
		if( $remote ) error_log($error_msg, 2, $host );
		
		if( $display )
		{
			print $error_msg . "<br />";
		}
		else
		{
			// Give user basic indication that an error occurred.
			print "<b>An error occurred.</b> The admin has been notified.<br />";
		}
		
		if( $stdlog )
		{
			if($logfile = "")
			{
				error_log($error_msg, 0);
			}
			else
			{
				error_log($error_msg, 3, $log_file);
			}
		}
	}
	
	if( $halt )
	{
		exit -1;
	}
}
?>
