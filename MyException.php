<?php

class MyException extends Exception
{
	public function __construct($strClassName, $intLineNumber, $strMsg)
	{	
		parent::__construct("$strClassName ($intLineNumber): $strMsg");
	}
}
