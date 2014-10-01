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

/*
include "functions.php";
include "appInit.php";
include "siteGenFunctions.php";
include "siteDbFunctions.php";

$conn = connect_to_DB();

$result = dbSelect("LightShow", $whereCondArrArr);
$numRecords = mysql_num_rows($result);

for ($i = 0; $i < $numRecords; $i++) {
	$row = mysql_fetch_assoc($result);
	
	// Build JSON response
	if ($i > 0) { echo ","; }
	echo '{"idx": "' . $row['idx'] . '", ';
	echo '"lightShowName": "' . $row['lightShowName'] . '", ';
	echo '"singleCmdIdxStr": "' . $row['singleCmdIdxStr'] . '"}';
}
*/

include "appInit.php";
include "siteGenFunctions.php";
include "siteDbFunctions.php";

if (!isset($_GET['lightShowIdx']) && !isset($_GET['lightShowName']) && !isset($_GET['singleCmdIdxStr'])) {
	echo "<p>Error: 1 (and only 1) of these must be set: lightShowIdx, lightShowName, singleCmdIdxStr</p>";
	exit();
}

$conn = connect_to_DB();

$lightShowIdx = false;
$lightShowName = false;
$singleCmdIdxStr = false;

$whereCondArrArr = "";  // Selects NOTHING from DB table
if (isset($_GET['lightShowIdx'])) {
	$lightShowIdx = $_GET['lightShowIdx'];
	$whereCondArrArr = array(array("idx", "=", $lightShowIdx));
} elseif (isset($_GET['lightShowName'])) {
	$lightShowName = $_GET['lightShowName'];
	$whereCondArrArr = array(array("lightShowName", "=", $lightShowName));
} elseif (isset($_GET['singleCmdIdxStr'])) {
	$singleCmdIdxStr = $_GET['singleCmdIdxStr'];
	$whereCondArrArr = array(array("singleCmdIdxStr", "=", $singleCmdIdxStr));
}

$result = dbSelect("LightShow", $whereCondArrArr);
$numRecords = mysql_num_rows($result);

if ($numRecords > 0) {
	$row = mysql_fetch_assoc($result); 
	
	// Build JSON response
	echo '{"idx": "' . $row['idx'] . '", ';
	echo '"lightShowName": "' . $row['lightShowName'] . '", ';
	echo '"singleCmdIdxStr": "' . $row['singleCmdIdxStr'] . '"}';
} else {
	echo '{"idx": "-1"}';
}
?>