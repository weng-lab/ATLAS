<?php
function database_connect() {
	$config['user'] = 'borrmant';
	$config['pass'] = rtrim(file_get_contents("/run/secrets/atlas_db_user_passwd"));
	$config['db'] = 'atlas';

	$link = mysqli_connect('db', $config['user'],
		  $config['pass'], $config['db']);
	if (!$link)
	{
	  $error = 'Unable to connect to the database server.';
	  echo $error;
	  exit();
	}
	if (!mysqli_set_charset($link, 'utf8'))
	{
	  $error = 'Unable to set database connection encoding.';
	  echo $error;
	  exit();
	}
	if (!mysqli_select_db($link, 'atlas'))
	{
	  $error3 = 'Unable to locate the atlas database.';
	  echo $error;
	  exit();
	}
	return $link;
}

function load_data($data_path, $table_name, $conn) {
	$sql = "LOAD DATA LOCAL INFILE '" . $data_path . "' INTO TABLE " . $table_name . ";";
	if (mysqli_query($conn, $sql)) {
		echo "Data loaded successfully\n";
	}
	else {
		echo "Error loading " . $table_name . " data: " . mysqli_error($conn) . "\n";
	}
}
?>