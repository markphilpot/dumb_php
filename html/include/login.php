<?php

//global $status;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<style type="text/css" media="screen"><!-- @import "stylesheet.css"; --></style>
<script type="text/javascript" src="drop_down.js"></script>
<script type="text/javascript" src="login.js"></script>

<style type="text/css">

#form
{
	position: relative;
	top: 0px;
	left: 0px;
	margin-top: 30%;
	margin-left: 30%;
}
body
{
	background-image: none;
	background-color: #FFFFFF;
}

</style>

</head>
<body>

<div id="form">
<h4>Please enter your username & password</h4>
<p><?php print $status; ?></p>
<form method="post" action="members.php">
	Username: <input type="text" name="username" /> <br />
	Password: <input type="password" name="password" maxlength=10 /> <br />
	<input type="hidden" name="login" value="1">
	<input type="submit" name="submit" value="Submit" />
</form>

<h5 class="center"><a href="javascript:self.close();">Close Window</a></h5>

</div> <!-- end form -->

</body>
</html>