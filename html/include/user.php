<?php
require_once 'include/database.php';

class User
{
	var $username = '';
	
	var $priv_map = '';
	var $info_map = '';
	
	var $is_admin = false;
	
	function User($user)
	{
		global $dbUser, $dbPass, $dbServer, $dbDatabase, $dbType;
		$this->username = $user;
		
		$db = get_db_connection();
		
		$statement = $db->prepare('select * from dumb_priv where username = ?');                        
		$result =& $db->execute($statement, $this->username);
		
		while( $result->fetchInto($row) )
		{
			$this->priv_map[$row['priv']] = "true";
		}
		
		$statement = $db->prepare('select * from dumb_members where username = ?');
		$result =& $db->execute($statement, $this->username);
		
		if( $result->numRows() > 0 )
		{
			while( $result->fetchInto($row) )
			{
				$this->info_map['username'] = $row['username'];
				$this->info_map['firstname'] = $row['firstname'];
				$this->info_map['lastname'] = $row['lastname'];
				$this->info_map['instrument_id'] = $row['instrument_id'];
				$this->info_map['pepband'] = $row['pepband'];
				$this->info_map['year'] = $row['year'];
				$this->info_map['phone'] = $row['phone'];
				$this->info_map['email'] = $row['email'];
			}
		}
		else
		{
			$is_admin = true;
		}
	}
	
	function has_priv( $privledge )
	{
		if( $this->priv_map[$privledge] == "true" )
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
};

?>
