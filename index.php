<?php
session_start();
include('init.php');
$classPeople = new $strConfDbClass($arrConfDb[$strConfDbClass]);

if (!isset($_SESSION['user_info']))
{
	header('location: login.php');
}

$intPage = 0;
if (isset($_GET['page']))
{
	if (!is_int($_GET['page']))
	{
		$intPage = 0;
	}
	else
	{
		$intPage = int($_GET['page']);
	}
}
$strMsg = '';
if (isset($_GET['message']))
	$strMsg = "<p>" . $_GET['message'] . "</p>";
$arrPeople = $classPeople->fetchAll(null, null);
$intTotal = $arrPeople['total'];
$arrPeople = $arrPeople['data'];
$smarty->assign('User', $_SESSION['user_info']);
$smarty->assign('Title', 'Index');
$smarty->assign('People', $arrPeople);
$smarty->assign('Total', $intTotal);
$smarty->assign('Page', $intPage);
$smarty->assign('Message', $strMsg);
$smarty->display('index.tpl');
