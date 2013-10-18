<?php

interface People
{
	/* add a new entry into db.
	   caller should verify the data before inserting
	 */
	public function add($arrInfo, $intId = null);
	/* update the entry in db specified by $intId
	   caller should verify the data.
	 */
	public function update($arrInfo, $intId);
	/* delete the entry specified by $intId
	 */
	public function del($intId);
	/* constructor
	 */
	public function __construct($arrConfDb);
	/* initialize the db
	 */
	public static function setup($arrConfDb);
}
