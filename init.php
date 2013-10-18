<?php

include('config.php');

require 'smarty/libs/Smarty.class.php';
$smarty = new Smarty;

//$smarty->debugging = true;
//$smarty->caching = true;
//$smarty->cache_lifetime = 120;
function my_autoload($strClassName)
{
	require_once($strClassName . ".php");
}
spl_autoload_register('my_autoload'); 

