<?php
session_start();
include('init.php');
$classPeople = new $strConfDbClass($arrConfDb[$strConfDbClass]);


$arrRet = array();
$arrRet['errno'] = 0;
$arrRet['err_msg'] = '';
if (!isset($_SESSION['user_info']))
{
	$arrRet['errno'] = 1;
	$arrRet['err_msg'] .= "<p>Login expired</p>";
}
if (!isset($_POST['id']) && $_SESSION['user_info']['privilege'] != 0)
{
	$arrRet['errno'] = 1;
	$arrRet['err_msg'] .= "<p>Permission Denied</p>";
}
if ($_SESSION['user_info']['privilege'] != 0 &&
	isset($_POST['id']) &&
	$_SESSION['user_info']['id'] != $_POST['id'])
{
	$arrRet['errno'] = 1;
	$arrRet['err_msg'] .= "<p>Permission Denied</p>";
}

function verify_data($arrData)
{
	global $classPeople;
	global $arrRet;
	if (isset($arrData['id']))
	{
		$arrR = $classPeople->fetch('id', $arrData['id']);
		if (sizeof($arrR) != 1)
		{
			$arrRet['errno'] = 1;
			$arrRet['err_msg'] .= "<p>Updating non-exist entry</p>";
			return null;
		}
		$arrData['pwd'] = $arrR[0]['pwd'];
		if (!is_numeric($arrData['privilege']))
		{
			$arrRet['errno'] = 1;
			$arrRet['err_msg'] .= "<p>Type should be integer</p>";
			return null;
		}
		$arrData['privilege'] = intval($arrData['privilege']);

		if ($arrData['id'] == 0 && $arrData['privilege'] != $arrR[0]['privilege'])
		{
			$arrRet['errno'] = 1;
			$arrRet['err_msg'] .= "<p>Cannot update root user privilege</p>";
			return null;
		}
		if ($_SESSION['user_info']['privilege'] > 0 && $arrData['privilege'] != $arrR[0]['privilege'])
		{
			$arrRet['errno'] = 1;
			$arrRet['err_msg'] .= "<p>Cannot update user privilege</p>";
			return null;
		}
		$arrR = $classPeople->fetch('name', $arrData['name']);
		if (sizeof($arrR) > 0)
		{
			foreach ($arrR as $arrEach)
			{
				if ($arrEach['id'] != $arrData['id'])
				{
					$arrRet['errno'] = 1;
					$arrRet['err_msg'] .= "<p>user name exists</p>";
					return null;
				}
			}
		}
		if ($arrData['name'] == "")
		{
			$arrRet['errno'] = 1;
			$arrRet['err_msg'] .= "<p>empty user name</p>";
			return null;
		}
		return $arrData;
	}
	else
	{
		$arrR = $classPeople->fetch('name', $arrData['name']);
		if (sizeof($arrR) > 0)
		{
			$arrRet['errno'] = 1;
			$arrRet['err_msg'] .= "<p>user name exists</p>";
			return null;
		}
		if ($arrData['name'] == "")
		{
			$arrRet['errno'] = 1;
			$arrRet['err_msg'] .= "<p>empty user name</p>";
			return null;
		}
		if (!is_numeric($arrData['privilege']))
		{
			$arrRet['errno'] = 1;
			$arrRet['err_msg'] .= "<p>Type should be integer</p>";
			return null;
		}
		if (strlen($arrData['pwd']) < 6)
		{
			$arrRet['errno'] = 1;
			$arrRet['err_msg'] .= "<p>Password is too short</p>";

			return null;
		}
		$arrData['privilege'] = intval($arrData['privilege']);
		return $arrData;

	}
}

if (!isset($_POST['name']) || !isset($_POST['desc']) || !isset($_POST['type']))
{
	die();
}

$arrData = array();
if (isset($_POST['id']))
{
	if (!is_numeric($_POST['id']))
	{
		$arrRet['errno'] = 1;
		$arrRet['err_msg'] .= "<p>Invalid id provided!</p>";
	}
	else
	{
		$arrData['id'] = intval($_POST['id']);
	}
}
else
{
	if (!isset($_POST['password']) || !isset($_POST['confirm_pwd']))
	{
		die();
	}
	if ($_POST['password'] != $_POST['confirm_pwd'])
	{
		$arrRet['errno'] = 1;
		$arrRet['err_msg'] .= "<p>password missmatch</p>";
		$arrData['pwd'] = '';
	}
	else
	{
		$arrData['pwd'] = $_POST['password'];
	}
}

$arrData['name'] = $_POST['name'];
$arrData['other'] = $_POST['desc'];
$arrData['privilege'] = $_POST['type'];
$arrData = verify_data($arrData);
if ($arrData !== null && $arrRet['errno'] == 0)
{
	// try update
	try
	{
		if (isset($arrData['id']))
			$classPeople->update($arrData, $arrData['id']);
		else
			$arrData['id'] = $classPeople->add($arrData);
	}
	catch (Exception $e)
	{
		$arrRet['errno'] = 1;
		$arrRet['err_msg'] .= $e->getMessage();
	}
}


$arrRet['name'] = $arrData['name'];
$arrRet['type'] = $arrData['privilege'];
$arrRet['desc'] = $arrData['other'];
if (isset($arrData['id']))
	$arrRet['id'] = $arrData['id'];

echo json_encode($arrRet);
