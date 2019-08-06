<?php
if ($_SERVER['HTTP_X_FORWARDED_PROTO'] == "https")
{
	header ("Location: http://".$_SERVER['HTTP_HOST']);
}
@session_start ();
ini_set('display_errors', 1);
require_once 'application/bootstrap.php';