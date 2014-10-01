<?php
/*
	Copyright 2014 Mark Briggs

	This file is part of ColorRing.

	ColorRing is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	any later version.

	ColorRing is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	There is a copy of the GNU General Public License in the
	accompanying COPYING file
*/

require_once("appInit.php");
require_once("siteGenFunctions.php");
require_once("siteDbFunctions.php");

if (isset($_GET['cmdName']) && isset($_GET['cmdBytesStr'])) {
	$cmdName = $_GET['cmdName'];
	$cmdBytesStr = $_GET['cmdBytesStr']; // Should start with the first byte of the packet. 
	
	$conn = connect_to_DB();
	
	// If exists, update record. If not, Insert new record.
	$whereCondArrArr = array(array("cmdName", "=", $cmdName));
	$result = dbSelect("SingleCmd", $whereCondArrArr);
	$numRecords = mysql_num_rows($result);
	if ($numRecords > 0) {
		// Update cmdBytesStr
		$row = mysql_fetch_assoc($result);
		$idx = $row['idx'];
		
		$setArr = array("cmdBytesStr" => $cmdBytesStr);
		$whereCondArrArr = array(array("idx", "=", $idx));
		$result = dbUpdate("SingleCmd", $setArr, $whereCondArrArr);
		if ($result) {
			echo '{"result": "1", ';
			echo '"statusMsg": "Updated!", ';
			echo '"idx": "' . $idx . '"}';
		} else {
			echo '{"result": "-1", ';
			echo '"statusMsg": "Not Updated!"}';
		}
	} else {
		// Check for exact match of cmdBytesStr. If exists, update the cmdName. (so there won't be duplicate cmds with different names). If doesn't exist, Insert new record
		$whereCondArrArr = array(array("cmdBytesStr", "=", $cmdBytesStr));
		$result = dbSelect("SingleCmd", $whereCondArrArr);
		$numRecords = mysql_num_rows($result);
		if ($numRecords > 0) {
			// Update cmdName
			$row = mysql_fetch_assoc($result);
			$idx = $row['idx'];
		
			$setArr = array("cmdName" => $cmdName);
			$whereCondArrArr = array(array("idx", "=", $idx));
			$result = dbUpdate("SingleCmd", $setArr, $whereCondArrArr);
			if ($result) {
				echo '{"result": "1", ';
				echo '"statusMsg": "Updated cmdName!", ';
				echo '"idx": "' . $idx . '"}';
			} else {
				echo '{"result": "-1", ';
				echo '"statusMsg": "cmdName Not Updated!"}';
			}
		} else {
			// Insert new record
			$result = dbInsert("SingleCmd", array("cmdName" => $cmdName, "cmdBytesStr" => $cmdBytesStr));
			$idx = mysql_insert_id();
			if ($result) {
				echo '{"result": "1", ';
				echo '"statusMsg": "Saved!", ';
				echo '"idx": "' . $idx . '"}';
			} else {
				echo '{"result": "-1", ';
				echo '"statusMsg": "Not Saved!"}';
			}
		}
	}
	
} else {
	echo '{"result": "-1", ';
	echo '"statusMsg": "Both \'cmdName\' and \'cmdBytesStr\' must be passed.<br />"}';
}

?>