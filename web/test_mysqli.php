#!/usr/bin/env php
<?php
$link = mysqli_connect('127.0.0.1', 'borrmant', 'sqlpsswd', 'atlas');
if (!$link)   
	{   
	  $error = 'Unable to connect to the database server.';   
	  echo $error; 
	  exit(); 
	 }
