<?php

// https://stackoverflow.com/a/21129068

$url = 'http://' . $_SERVER['HTTP_HOST'];            // Get the server
$url .= rtrim(dirname($_SERVER['PHP_SELF']), '/\\'); // Get the current directory
$url .= '/web/index.php';
header('Location: ' . $url, true, 302);              // Use either 301 or 302

?>