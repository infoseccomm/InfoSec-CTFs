<?php

	include('client.php');

	function getIP()
	{
	    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
	    {
	      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	    }
	    else
	    {
	      $ip=$_SERVER['REMOTE_ADDR'];
	    }
	    return $ip;
	}

	if (getIP() == '127.0.0.1') {
		if (!isset($_COOKIE['client'])) {
			setcookie("client", base64_encode(serialize(new Client('Guest'))));
		} else {
			unserialize(base64_decode($_COOKIE['client']));
		}
	}
	else {
		header('HTTP/1.0 403 Forbidden');
    	die('You are not allowed to access.');
	}
?>