<?php
class PeopleJson implements People
{
	private $arrDb = array();
	private $strDbFileName = "";
	private function _writeBack()
	{
		file_put_contents($this->strDbFileName, json_encode($this->arrDb));
	}
	public function __construct($arrConfDb)
	{
		$this->strDbFileName = $arrConfDb['file_name'];
		$strDbFileContent = file_get_contents($this->strDbFileName);
		if ($strDbFileContent == false)
		{
			throw new MyException(get_class($this), __LINE__, "invalid db file name");
		}
		$this->arrDb = json_decode($strDbFileContent, true);
	}
	
	public function add($arrInfo, $intId = null)
	{
		// id checking
		if ($intId === null)
		{
			$intNewId = $this->arrDb['next_id'];
		}
		else
		{
			$intNewId = $intId;
		}

		if (!is_int($intNewId))
		{
			throw new MyException(get_class($this), __LINE__, "invalid id");
		}
		if (array_key_exists($intNewId, $this->arrDb['data']) && $intId !== null)
		{
			throw new MyException(get_class($this), __LINE__, "duplicate id");
		}
		while (array_key_exists($intNewId, $this->arrDb['data']))
		{
			$intNewId ++;
		}
		$arrInfo['id'] = $intNewId;
		$this->arrDb['data'][$intNewId] = $arrInfo;
		if ($intId != null)
		{
			$this->arrDb['next_id'] = $intNextId + 1;
		}
		$this->_writeBack();
		return $arrInfo['id'];
	}
	public function update($arrInfo, $intId)
	{
		if (!is_int($intId))
		{
			throw new MyException(get_class($this), __LINE__, "invalid id");
		}
		if (!array_key_exists($intId, $this->arrDb['data']))
		{
			throw new MyException(get_class($this), __LINE__, "none exist id");
		}

		$arrInfo['id'] = $intId;
		$this->arrDb['data'][$intId] = $arrInfo;
		$this->_writeBack();

	}
	public function del($intId)
	{
		if (!is_int($intId))
		{
			throw new MyException(get_class($this), __LINE__, "invalid id");
		}
		if (!array_key_exists($intId, $this->arrDb['data']))
		{
			throw new MyException(get_class($this), __LINE__, "none exist id");
		}

		unset($this->arrDb['data'][$intId]);
		$this->_writeBack();

	}
	public function fetch($mixKey, $mixValue)
	{
		$arrRet = array();
		foreach ($this->arrDb['data'] as $arrEntry)
		{
			if (isset($arrEntry[$mixKey]))
			{
				if ($arrEntry[$mixKey] == $mixValue)
				{
					$arrRet[] = $arrEntry;
				}
			}
		}
		return $arrRet;
	}
	public function fetchAll($intPage = 0, $intLimit = 10)
	{
		if ($intLimit === null && $intPage === null)
		{
			return array('data' => $this->arrDb['data'], 'total' => null);
		}
		if (!is_int($intPage) || !is_int($intLimit))
		{
			throw new MyException(get_class($this), __LINE__, "invalid parameter");
		}
		if (sizeof($this->arrDb['data']) < $intPage * $intLimit)
		{
			return null;
		}
		$arrRet = array();
		$arrKeys = array_keys($this->arrDb['data']);
		for ($intI = $intPage * $intLimit; $intI < sizeof($arrKeys); $intI ++)
		{
			$arrRet[] = $this->arrDb['data'][$intI];
		}
		$intTotal = floor(sizeof($arrKeys) / $intLimit);
		$intTotal += sizeof($arrKeys) % $intLimit != 0;
		return array('total' => $intTotal, 'data' => $arrRet);
	}
	public static function setup($arrConfDb)
	{
		$arrDb = array(
			'next_id' => 0,
			'data' => array(),
		);
		file_put_contents($arrConfDb['file_name'], json_encode($arrDb));
	}
}
