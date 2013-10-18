<?php

include('init.php');


$strConfDbClass::setup($arrConfDb[$strConfDbClass]);


$classPeople = new $strConfDbClass($arrConfDb[$strConfDbClass]);
$classPeople->add(array('name' => 'admin', 'pwd' => 'password', 'privilege' => 0, 'other' => 'this is an administrator'), 0);
