<?php
session_start();
include('init.php');
$classPeople = new $strConfDbClass($arrConfDb[$strConfDbClass]);


if (!isset($_SESSION['user_info']))
{
	header("location: login.php");
	die();
}

if($_SESSION['user_info']['privilege'] != 0)
{
	header("location: index.php?message=Permission Denied");
	die();
}


$intId = $_GET['id'];
if (!is_numeric($intId))
{
	header("location: index.php?message=Invalid Parameter");
	die();
}
if ($_SESSION['user_info']['id'] == intval($intId) || intval($intId) == 0)
{
	header("location: index.php?message=Permission Denied");
	die();
}
try
{
	$classPeople->del(intval($intId));
}
catch (Exception $e)
{
	header("location: index.php?message=" . $e->getMessage());
	die();
}

header("location: index.php?message=Success!");

