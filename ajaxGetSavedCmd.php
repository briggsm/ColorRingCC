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

include "appInit.php";
include "siteGenFunctions.php";
include "siteDbFunctions.php";

if (!isset($_GET['cmdIdx']) && !isset($_GET['cmdName']) && !isset($_GET['cmdBytesStr'])) {
	echo "<p>Error: 1 (and only 1) of these must be set: cmdIdx, cmdName, cmdBytesStr</p>";
	exit();
}

$conn = connect_to_DB();

$cmdIdx = false;
$cmdName = false;
$cmdBytesStr = false;

$whereCondArrArr = "";  // Selects NOTHING from DB table
if (isset($_GET['cmdIdx'])) {
	$cmdIdx = $_GET['cmdIdx'];
	$whereCondArrArr = array(array("idx", "=", $cmdIdx));
} elseif (isset($_GET['cmdName'])) {
	$cmdName = $_GET['cmdName'];
	$whereCondArrArr = array(array("cmdName", "=", $cmdName));
} elseif (isset($_GET['cmdBytesStr'])) {
	$cmdBytesStr = $_GET['cmdBytesStr'];
	$whereCondArrArr = array(array("cmdBytesStr", "=", $cmdBytesStr));
}

$result = dbSelect("SingleCmd", $whereCondArrArr);
$numRecords = mysql_num_rows($result);

if ($numRecords > 0) {
	$row = mysql_fetch_assoc($result); 
	
	// Build JSON response
	echo '{"idx": "' . $row['idx'] . '", ';
	echo '"cmdName": "' . $row['cmdName'] . '", ';
	echo '"cmdBytesStr": "' . $row['cmdBytesStr'] . '"}';
} else {
	echo '{"idx": "-1"}';
}

?>