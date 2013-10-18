<?php
session_start();
include('init.php');
$classPeople = new $strConfDbClass($arrConfDb[$strConfDbClass]);

$arrMessage = array();
if (isset($_POST['name']))
{
	$arrRet = $classPeople->fetch('name', $_POST['name']);
	if (sizeof($arrRet) != 1 || $arrRet[0]['pwd'] != $_POST['password'])
	{
		$arrMessage[] = "User name and password missmatch";
	}
	if (sizeof($arrMessage) === 0)
	{
		$_SESSION['user_info'] = array();
		$_SESSION['user_info']['id'] = $arrRet[0]['id'];
		$_SESSION['user_info']['name'] = $_POST['name'];
		$_SESSION['user_info']['privilege'] = $arrRet[0]['privilege'];
		header("location: index.php");
	}
}

$smarty->assign('Title', 'Login');
$smarty->assign('Message', $arrMessage);
$smarty->display('login.tpl');

