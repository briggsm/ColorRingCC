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

function connect_to_DB() {
	global $DB_HOST;
	global $DB_NAME;
	global $DB_USER;
	global $DB_PASSWORD;
	
	
	// Connect to DB
	//$serverName = $_SERVER['SERVER_NAME'];  //kampusweb.com or localhost. Note: "" if using SSH or Cron or Pipe!
	$conn = mysql_pconnect($DB_HOST, $DB_USER, $DB_PASSWORD) OR DIE (mysql_error());
	mysql_select_db ($DB_NAME, $conn) OR DIE (mysql_error());
	
	mysql_query("SET NAMES utf8");
	
	return $conn;
}

function dbInsert($tableName, $fieldNameValueArr, $funcName="") {
	// Example Usage: dbInsert("WaitingRoom", array("submitter" => $submitter, "gender" => $gender, "city" => $city), "privateRoom");

	global $KWDEBUG;
	
	// Build Query
	$query = "INSERT INTO `" . mysql_real_escape_string($tableName) . "` (";
	
	foreach ($fieldNameValueArr as $fieldName => $fieldValue) {
		$query .= "`" . mysql_real_escape_string($fieldName) . "`, ";
	}
	$query = rtrim($query, ", ");  // Take off the last ", "
	
	$query .= ") VALUES (";
	
	foreach ($fieldNameValueArr as $fieldName => $fieldValue) {
		$query .= "'" . mysql_real_escape_string($fieldValue) . "', ";
	}
	$query = rtrim($query, ", ");  // Take off the last ", "
	
	$query .= ")";
	
	//dbgOut("dbInsert query: " . $query);
	if ($KWDEBUG) { outDebugFile("dbInsert query: " . $query); }
	$result = mysql_query($query);
	if ($result == 0) {
		dieFromQueryFailure("INSERT INTO", $tableName, $query, $funcName);
	}
	
	return $result;
}

function dbSelect ($tableName, $whereCondArrArr="", $restOfClauses="", $funcName="") {
	/* Example Usage:
	$whereCondArrArr = array(
		array("idx", "=", $idx, "OR", "("),
		array("idx", "=", $idx2, "AND", ")"),
		array("username", "=", $username, "OR", "("),
		array("username", "=", $username2, "", ")")
	);
	$result = dbSelect("WaitingRoom", $whereCondArrArr, "ORDER BY `idx` ASC", "main");
	
	Note: $whereCondArrArr = "*"  ==>  ALL records in DB!
	*/
	// Afterwards, you can use:
		// mysql_num_rows($result)
		// while ($row = mysql_fetch_assoc($result)) { $row['dbField'] ... }
	
	global $KWDEBUG;
		
	// === Build WHERE Clause
	$whereClause = buildWhereClause($whereCondArrArr);
	
	// Pad the Clauses w/ spaces, just incase they're not already
	if ($whereClause != "") { $whereClause = " " . $whereClause; }
	if ($restOfClauses != "") { $restOfClauses = " " . $restOfClauses; }
	
	$query = "SELECT * FROM `" . mysql_real_escape_string($tableName) . "`" . $whereClause . $restOfClauses;
	//dbgOut("dbSelect query: " . $query);
	if ($KWDEBUG) { outDebugFile("dbSelect query: " . $query); }

	$result = mysql_query($query);
	if ($result == 0) {
		dieFromQueryFailure("SELECT * FROM", $tableName, $query, $funcName);
	}
	
	return $result;
}

function dbUpdate ($tableName, $setArr, $whereCondArrArr="", $restOfClauses="", $funcName="") {
	// Example Usage:
	/*
	// SET
	$setArr = array("answeredBy" => $answeredBy, "answerDate" => $answerDate, "answerSubject" => $answerSubject, "answerText" => $answerText);
	
	// WHERE
	$whereCondArrArr = array(
		array("idx", "=", $idx, "OR", "("),
		array("idx", "=", $idx2, "AND", ")"),
		array("username", "=", $username, "OR", "("),
		array("username" "=", $username2, "", ")")
	);
	dbUpdate("qatable", $setArr, $whereCondArrArr);
	
	Note: $whereCondArrArr = "*"  ==>  ALL records in DB!
	*/

	// Note: use "mysql_affected_rows()" to get number of row affected by the UPDATE query (don't need '$result')

	global $KWDEBUG;
	
	// Begin the query
	$query = "UPDATE `" . mysql_real_escape_string($tableName) . "`";

	// === Build SET Clause ===
	$setClause = buildSetClause($setArr);
	
	// === Build WHERE Clause
	$whereClause = buildWhereClause($whereCondArrArr);

	if ($setClause != "") { $setClause = " " . $setClause; }
	if ($whereClause != "") { $whereClause = " " . $whereClause; }
	if ($restOfClauses != "") { $restOfClauses = " " . $restOfClauses; }

	$query .= $setClause . $whereClause . $restOfClauses;
	//dbgOut("dbUpdate query: " . $query);
	if ($KWDEBUG) { outDebugFile("dbUpdate query: " . $query); }

	$result = mysql_query($query);
	if ($result == 0) {
		dieFromQueryFailure("UPDATE", $tableName, $query, $funcName);
	}
	
	return $result;
}

function buildSetClause($setArr) {
	$setClause = "SET ";
	
	foreach ($setArr as $setField => $setValue) {
		$setClause .= "`" . mysql_real_escape_string($setField) . "` = '" . mysql_real_escape_string($setValue) . "', ";
	}
	
	$setClause = rtrim($setClause, ", ");  // Take off the last ", "
	
	return $setClause;
}

function buildWhereClause($whereCondArrArr) {
	// e.g.: array( "idx", "=", $idx, "OR", "(" )
	if ($whereCondArrArr == "*") {
		// This will update ALL records!
		$whereClause = "";
	} elseif ($whereCondArrArr == "") {
		// Probably forgot to enter it, so be safe...
		$whereClause = "WHERE 1 = 2";
	} else {
		// Normal Case
		$whereClause = "WHERE ";
		
		foreach($whereCondArrArr as $whereCondArr) {
			$fieldName = "";
			$comparisonOp = "";  // e.g. >, <, >=, <=, ==, !=
			$fieldValue = "";
			$logicalOp = ""; // e.g. AND, OR
			$paren = ""; // e.g. "(", ")", or ""
			
			if (isset($whereCondArr[0])) { $fieldName = $whereCondArr[0]; }
			if (isset($whereCondArr[1])) { $comparisonOp = $whereCondArr[1]; }
			if (isset($whereCondArr[2])) { $fieldValue = $whereCondArr[2]; }
			if (isset($whereCondArr[3])) { $logicalOp = $whereCondArr[3]; }
			if (isset($whereCondArr[4])) { $paren = $whereCondArr[4]; }

			if ($paren == "(") {
				$whereClause .= "( ";
			}

			if ($fieldName != "") { $whereClause .= "`" . mysql_real_escape_string($fieldName) . "`" . " "; }
			if ($comparisonOp != "") { $whereClause .= mysql_real_escape_string($comparisonOp) . " "; }
			if ($fieldValue != "") { $whereClause .= "'" . mysql_real_escape_string($fieldValue) . "'" . " "; }
			
			if ($paren == ")") {
				$whereClause .= ") ";
			}
			
			$whereClause .= $logicalOp . " ";
		}
	}
	
	return $whereClause;
}

function dieFromQueryFailure($queryType, $tableName, $query, $funcName) {
	// Note: $queryType = "INSERT INTO", "SELECT * FROM", etc.
	
	$scriptFuncStr = " [" . basename($_SERVER["SCRIPT_NAME"]) . ", " . $funcName . "()]";
	$datetime = date('Y-m-d H:i:s');  // Current Date/Time
	$failStr = "(" . $datetime . ") The " . $queryType . " `" . $tableName . "` Query FAILED!" . $scriptFuncStr;
	outLogFile($failStr . " <query: " . $query . ">");
	//dbgOut($failStr . " <query: " . $query . ">");
	die($failStr);
}

?>