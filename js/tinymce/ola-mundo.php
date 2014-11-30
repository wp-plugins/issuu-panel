<?php

$dir = dirname(__FILE__);
$pos = strpos($dir, 'wp-content');
$abspath = substr($dir, 0, $pos);

require($abspath . 'wp-load.php');

if (function_exists('get_issuu_message'))
{
	echo 'Existe';
}
else
{
	echo 'Não existe';
}