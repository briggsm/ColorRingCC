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

define("OUT_COLORED5S_COLOR", 0);
define("IN_COLORED5S_COLOR", 1);
define("HANDCOLOR_HOUR", 2);
define("HANDCOLOR_MIN", 3);
define("HANDCOLOR_SEC", 4);

// COLOR_USAGE
define("COLOR_USAGE_NONE", 0);
define("COLOR_USAGE_OUT_ECM", 1);
define("COLOR_USAGE_IN_ECM", 2);
define("COLOR_USAGE_OUT_COLORED5S", 3);
define("COLOR_USAGE_IN_COLORED5S", 4);
define("COLOR_USAGE_HOUR_HAND", 5);
define("COLOR_USAGE_MIN_HAND", 6);
define("COLOR_USAGE_SEC_HAND", 7);

// CmdTypes
define("CMDTYPE_SSP", 0);
//define("CMDTYPE_BCG", 1);
define("CMDTYPE_SHIFT", 2);
define("CMDTYPE_FLOW", 3);

define("MAX_STRIPCMD_SIZE", 32);


function getCmdTable ($cmdBytes, $cmdPos) {
	// Note: assumes $cmdBytes are all in INTEGER format!
	
	$table = "<table border=1>";
	$cmdPosPrefix = "cmdPos" . str_pad($cmdPos, 3, "0", STR_PAD_LEFT);  // e.g. cmdPos001, 009, 010, etc.
	$cmdType = $cmdBytes[0];
	
	$table .= '<input type="hidden" id="' . $cmdPosPrefix . 'cmdType" value="' . $cmdType . '" />';
	
	switch ($cmdType) {
		case 0:  // SetSeqPixels
			$table .= getRowStartPixelNum($cmdBytes, $cmdPos, 1);
			$table .= getRowNumPixelsEachColor($cmdBytes, $cmdPos, 2);
			$table .= getRowColorSeriesNumIter($cmdBytes, $cmdPos, 3);
			$table .= getRowNumPixelsToSkip($cmdBytes, $cmdPos, 4);
			$table .= getRowNumIter($cmdBytes, $cmdPos, 5);
			$table .= getRowAnimDelay($cmdBytes, $cmdPos, 7);
			$table .= getRowPauseAfter($cmdBytes, $cmdPos, 9);
			$table .= getRowBoolBits($cmdBytes, $cmdPos, 11, array(0,1,3,4,5,6));
			$table .= getRowNumColorsInSeries($cmdBytes, $cmdPos, 12);
			for ($x = 0; $x < 6; $x++) {
				$table .= getRowColorSeriesArrX($cmdBytes, $cmdPos, 13+($x*3), $x);
			}
			break;
		case 2:  // Shift
			$table .= getRowStartPixelNum($cmdBytes, $cmdPos, 1);
			$table .= getRowEndPixelNum($cmdBytes, $cmdPos, 2);
			$table .= getRowNumPixelsToSkip($cmdBytes, $cmdPos, 3);
			$table .= getRowNumIter($cmdBytes, $cmdPos, 4);
			$table .= getRowAnimDelay($cmdBytes, $cmdPos, 6);
			$table .= getRowPauseAfter($cmdBytes, $cmdPos, 8);
			$table .= getRowBoolBits($cmdBytes, $cmdPos, 10, array(1,2,3));
			break;
		case 3:  // Flow
			$table .= getRowStartPixelNum($cmdBytes, $cmdPos, 1);
			$table .= getRowEndPixelNum($cmdBytes, $cmdPos, 2);
			$table .= getRowNumSection($cmdBytes, $cmdPos, 3);
			$table .= getRowNumPixelsEachColor($cmdBytes, $cmdPos, 4);
			$table .= getRowColorSeriesNumIter($cmdBytes, $cmdPos, 5);
			$table .= getRowNumPixelsToSkip($cmdBytes, $cmdPos, 6);
			$table .= getRowAnimDelay($cmdBytes, $cmdPos, 7);
			$table .= getRowPauseAfter($cmdBytes, $cmdPos, 9);
			$table .= getRowBoolBits($cmdBytes, $cmdPos, 11, array(1,4,5,6));
			$table .= getRowNumColorsInSeries($cmdBytes, $cmdPos, 12);
			for ($x = 0; $x < 6; $x++) {
				$table .= getRowColorSeriesArrX($cmdBytes, $cmdPos, 13+($x*3), $x);
			}
			break;
		default:
			// Overwrite 'table' and return right away
			$table = "";
			return $table;
			
			break;
	}
	
	$table .= "</table>";

	return $table;
}

function getRowStartPixelNum($cmdBytes, $cmdPos, $idx) {
	// $idx => Index into $cmdBytes array.
	
	$table = "<tr>";
	$table .= "<td>startPixelNum: </td>";
	$table .= '<td><input type="number" min="0" max="59" id="' . getCmdPosPrefix($cmdPos) . 'startPixelNum" value="' . $cmdBytes[$idx] . '" onfocusout="updateCbArr(' . $cmdPos . ')" onkeyup="stripCmdTextInputKeyUp(' . $cmdPos . ')" /></td>';
	$table .= "</tr>";
	
	return $table;
}

function getRowEndPixelNum($cmdBytes, $cmdPos, $idx) {
	
	$table = "<tr>";
	$table .= "<td>endPixelNum: </td>";
	$table .= '<td><input type="number" min="0" max="59" id="' . getCmdPosPrefix($cmdPos) . 'endPixelNum" value="' . $cmdBytes[$idx] . '" onfocusout="updateCbArr(' . $cmdPos . ')" onkeyup="stripCmdTextInputKeyUp(' . $cmdPos . ')" /></td>';
	$table .= "</tr>";
	
	return $table;
}

function getRowStartPixelColor($cmdBytes, $cmdPos, $idx) {
	// Note: $idx is 1st of 3 bytes (R). $idx+1 is G. $idx+2 is B.

	$table = "<tr>";
	$table .= "<td>startPixelColor: </td>";
	$table .= '<td><input id="' . getCmdPosPrefix($cmdPos) . 'startPixelColor" value="' . sprintf("%02X", intval($cmdBytes[$idx], 0)) . sprintf("%02X", intval($cmdBytes[$idx+1], 0)) . sprintf("%02X", intval($cmdBytes[$idx+2], 0)) . '" /></td>';
	$table .= "</tr>";
	$table .= '<script>initStripCmdColorPicker(' . $cmdPos . ', "startPixelColor");</script>';
	
	return $table;
}

function getRowEndPixelColor($cmdBytes, $cmdPos, $idx) {
	// Note: $idx is 1st of 3 bytes (R). $idx+1 is G. $idx+2 is B.

	$table = "<tr>";
	$table .= "<td>endPixelColor: </td>";
	$table .= '<td><input id="' . getCmdPosPrefix($cmdPos) . 'endPixelColor" value="' . sprintf("%02X", intval($cmdBytes[$idx], 0)) . sprintf("%02X", intval($cmdBytes[$idx+1], 0)) . sprintf("%02X", intval($cmdBytes[$idx+2], 0)) . '" /></td>';
	$table .= "</tr>";
	$table .= '<script>initStripCmdColorPicker(' . $cmdPos . ', "endPixelColor");</script>';
	
	return $table;
}

function getRowNumPixelsToSkip($cmdBytes, $cmdPos, $idx) {

	$table = "<tr>";
	$table .= "<td>numPixelsToSkip: </td>";
	$table .= '<td><input type="number" min="0" max="59" id="' . getCmdPosPrefix($cmdPos) . 'numPixelsToSkip" value="' . $cmdBytes[$idx] . '" onfocusout="updateCbArr(' . $cmdPos . ')" onkeyup="stripCmdTextInputKeyUp(' . $cmdPos . ')" /></td>';
	$table .= "</tr>";
	
	return $table;
}

function getRowNumIter($cmdBytes, $cmdPos, $idx) {

	$table = "<tr>";
	$table .= "<td>numIter: </td>";
	$word = ($cmdBytes[$idx] << 8) + $cmdBytes[$idx+1];
	$table .= '<td><input type="number" min="0" max="65535" id="' . getCmdPosPrefix($cmdPos) . 'numIter" value="' . $word . '" onfocusout="updateCbArr(' . $cmdPos . ')" onkeyup="stripCmdTextInputKeyUp(' . $cmdPos . ')" /></td>';
	$table .= "</tr>";
	
	return $table;
}

function getRowNumSection($cmdBytes, $cmdPos, $idx) {

	$table = "<tr>";
	$table .= "<td>numSections: </td>";
	$table .= '<td><input type="number" min="1" max="60" id="' . getCmdPosPrefix($cmdPos) . 'numSections" value="' . $cmdBytes[$idx] . '" onfocusout="updateCbArr(' . $cmdPos . ')" onkeyup="stripCmdTextInputKeyUp(' . $cmdPos . ')" /></td>';
	$table .= "</tr>";
	
	return $table;
}

function getRowNumPixelsEachColor($cmdBytes, $cmdPos, $idx) {

	$table = "<tr>";
	$table .= "<td>numPixelsEachColor: </td>";
	$table .= '<td><input type="number" min="1" max="60" id="' . getCmdPosPrefix($cmdPos) . 'numPixelsEachColor" value="' . $cmdBytes[$idx] . '" onfocusout="updateCbArr(' . $cmdPos . ')" onkeyup="stripCmdTextInputKeyUp(' . $cmdPos . ')" /></td>';
	$table .= "</tr>";
	
	return $table;
}

function getRowColorSeriesNumIter($cmdBytes, $cmdPos, $idx) {

	$table = "<tr>";
	$table .= "<td>colorSeriesNumIter: </td>";
	$table .= '<td><input type="number" min="1" max="255" id="' . getCmdPosPrefix($cmdPos) . 'colorSeriesNumIter" value="' . $cmdBytes[$idx] . '" onfocusout="updateCbArr(' . $cmdPos . ')" onkeyup="stripCmdTextInputKeyUp(' . $cmdPos . ')" /></td>';
	$table .= "</tr>";

	return $table;
}

function getRowNumColorsInSeries($cmdBytes, $cmdPos, $idx) {

	$table = "<tr>";
	$table .= "<td>numColorsInSeries: </td>";
	$table .= '<td><input type="number" min="1" max="6" id="' . getCmdPosPrefix($cmdPos) . 'numColorsInSeries" value="' . $cmdBytes[$idx] . '" onfocusout="updateCbArr(' . $cmdPos . ')" onkeyup="stripCmdTextInputKeyUp(' . $cmdPos . ')" /></td>';
	$table .= "</tr>";
	
	return $table;
}

function getRowColorSeriesArrX($cmdBytes, $cmdPos, $idx, $x) {
	// Note: $idx is 1st of 3 bytes (R). $idx+1 is G. $idx+2 is B.

	$table = "<tr>";
	$table .= "<td>colorSeriesArr" . $x . ": </td>";
	$table .= '<td><input id="' . getCmdPosPrefix($cmdPos) . 'colorSeriesArr' . $x . '" value="' . sprintf("%02X", intval($cmdBytes[$idx], 0)) . sprintf("%02X", intval($cmdBytes[$idx+1], 0)) . sprintf("%02X", intval($cmdBytes[$idx+2], 0)) . '" /></td>';
	$table .= "</tr>";
	$table .= '<script>initStripCmdColorPicker(' . $cmdPos . ', "colorSeriesArr' . $x . '");</script>';
	
	return $table;
}

function getRowAnimDelay($cmdBytes, $cmdPos, $idx) {
	$table = "<tr>";
	$table .= "<td>animDelay (0-65535): </td>";
	$word = ($cmdBytes[$idx] << 8) + $cmdBytes[$idx+1];
	$table .= '<td><input type="number" min="0" max="65535" id="' . getCmdPosPrefix($cmdPos) . 'animDelay" value="' . $word . '" onfocusout="updateCbArr(' . $cmdPos . ')" onkeyup="stripCmdTextInputKeyUp(' . $cmdPos . ')" /></td>';
	$table .= "</tr>";
	
	return $table;
}

function getRowPauseAfter($cmdBytes, $cmdPos, $idx) {
	$table = "<tr>";
	$table .= "<td>pauseAfter (0-65535) [ms]: </td>";
	$word = ($cmdBytes[$idx] << 8) + $cmdBytes[$idx+1];
	$table .= '<td><input type="number" min="0" max="65535" id="' . getCmdPosPrefix($cmdPos) . 'pauseAfter" value="' . $word . '" onfocusout="updateCbArr(' . $cmdPos . ')" onkeyup="stripCmdTextInputKeyUp(' . $cmdPos . ')" /></td>';
	$table .= "</tr>";
	
	return $table;
}

function getRowBoolBits($cmdBytes, $cmdPos, $idx, $bitArr) {
	
	$table = "<tr><td><h3>boolBits</h3></td></tr>";
	
	$bb = $cmdBytes[$idx];
	
	if (in_array(0, $bitArr)) {
		$chkd = (($bb & 0x01) >> 0) == 1 ? "checked" : "";
		$table .= '<tr><td><label><input type="checkbox" id="' . getCmdPosPrefix($cmdPos) . 'bbDestructive" value="" ' . $chkd . ' onchange="updateCbArrAndSend(' . $cmdPos . ')" /> Destructive</label></td></tr>';
	}
	
	if (in_array(1, $bitArr)) {
		$chkd = (($bb & 0x02) >> 1) == 1 ? "checked" : "";
		$table .= '<tr><td><label><input type="checkbox" id="' . getCmdPosPrefix($cmdPos) . 'bbDirection" value="" ' . $chkd . ' onchange="updateCbArrAndSend(' . $cmdPos . ')" /> Direction(checked=CW)</label></td></tr>';
	}

	if (in_array(2, $bitArr)) {
		$chkd = (($bb & 0x04) >> 2) == 1 ? "checked" : "";
		$table .= '<tr><td><label><input type="checkbox" id="' . getCmdPosPrefix($cmdPos) . 'bbWrap" value="" ' . $chkd . ' onchange="updateCbArrAndSend(' . $cmdPos . ')" /> Wrap</label></td></tr>';
	}
	
	if (in_array(3, $bitArr)) {
		$chkd = (($bb & 0x08) >> 3) == 1 ? "checked" : "";
		$table .= '<tr><td><label><input type="checkbox" id="' . getCmdPosPrefix($cmdPos) . 'bbIsAnim" value="" ' . $chkd . ' onchange="updateCbArrAndSend(' . $cmdPos . ')" /> Animated</label></td></tr>';
	}
	
	if (in_array(4, $bitArr)) {
		$chkd = (($bb & 0x10) >> 4) == 1 ? "checked" : "";
		$table .= '<tr><td><label><input type="checkbox" id="' . getCmdPosPrefix($cmdPos) . 'bbClearStrip" value="" ' . $chkd . ' onchange="updateCbArrAndSend(' . $cmdPos . ')" /> Clear Strip</label></td></tr>';
	}
	
	if (in_array(5, $bitArr)) {
		$chkd = (($bb & 0x20) >> 5) == 1 ? "checked" : "";
		$table .= '<tr><td><label><input type="checkbox" id="' . getCmdPosPrefix($cmdPos) . 'bbGradiate" value="" ' . $chkd . ' onchange="updateCbArrAndSend(' . $cmdPos . ')" /> Gradiate</label></td></tr>';
	}
	
	if (in_array(6, $bitArr)) {
		$chkd = (($bb & 0x40) >> 6) == 1 ? "checked" : "";
		$table .= '<tr><td><label><input type="checkbox" id="' . getCmdPosPrefix($cmdPos) . 'bbGradiateLastPixelFirstColor" value="" ' . $chkd . ' onchange="updateCbArrAndSend(' . $cmdPos . ')" /> Gradiate (Last Pixel)<br />chkd => Last Pixel is First Color<br />unchkd => Last Pixel is Last Color</label></td></tr>';
	}
	
	return $table;
}

function getCmdPosPrefix($cmdPos) {
	return "cmdPos" . str_pad($cmdPos, 3, "0", STR_PAD_LEFT);  // e.g. cmdPos001, 009, 010, etc.
}

function getTimestamp() {
	list($usec, $sec) = explode(" ", microtime());
	return date("H:i:s", $sec) . " - " . $usec;
}

function getVarFromColorRing($varRequest) {
	global $colorringIP;
	$target = $colorringIP;
	$requestUrl = $target . '/' . $varRequest;
	
	$curl = curl_init($requestUrl);
	curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 ); 
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);  // was 0.5
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$curl_response = curl_exec($curl);
	curl_close($curl);
	$jsonA = json_decode($curl_response, true);
	
	//echo "jsonA:";
	//print_r($jsonA);
	
	$val = $jsonA[$varRequest];
	
	return $val;
}

function getByteArrayFromColorRing($fnName, $fnParamsStr) {
	global $colorringIP;
	$target = $colorringIP;
	
	//$requestUrl = $target . '/' . $fnName . '?params=' . $fnParamsStr;
	
	$requestUrl = $target . '/' . $fnName;
	if ($fnParamsStr != "") {
		$requestUrl .= '?params=' . $fnParamsStr;
	}
	
	
	$curl = curl_init($requestUrl);
	curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 ); 
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);  // was 0.5
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$curl_response = curl_exec($curl);
	curl_close($curl);

	$jsonB = json_decode($curl_response, true);
	//echo "jsonB: "; print_r($jsonB);
	//$cmdBytesStr = $jsonB["name"];
	$byteArrayStr = $jsonB["name"];
	//echo "cmdBytesStr: " . $cmdBytesStr;
	$byteArray = explode(",", $byteArrayStr);
	
	return $byteArray;
}

function cmdBytesArr2Str($cmdBytes) {
	$cmdBytesStr = "";
	for ($i = 0; $i < MAX_STRIPCMD_SIZE; $i++) {
		$cmdBytesStr .= $cmdBytes[$i] . ",";
	}
	
	$cmdBytesStr = rtrim($cmdBytesStr, ",");  // Strip off last comma
	
	return $cmdBytesStr;
}

function outLogFile($str) {
	global $LOG_FILE;

	$fh = fopen($LOG_FILE, 'a') or die("can't open file");
	fwrite($fh, $str . "\n");
	fclose($fh);
	
	dbgOut($str);  // Output to debug area too, if global $DEBUG is true.
}

function outDebugFile($str) {
	global $DEBUG_FILE;

	$fh = fopen($DEBUG_FILE, 'a') or die("can't open file");
	fwrite($fh, $str . "\n");
	fclose($fh);
}

function dbgOut($str) {
	// Debug - prints $str to screen (or log file, in the case CRON is running this)
	global $DEBUG;
	global $SSH_CONNECTION;
	global $USING_CRON_OR_PIPE;
	
	if ($DEBUG) {
	
		if ($SSH_CONNECTION) {
			echo $str . "\n";
		} elseif ($USING_CRON_OR_PIPE) {
			outDebugFile($str);  // Write to file, instead of screen
		} else {
			// Coming from Browser - output to screen AND outDebugFile
			//outDebugFile($str);
			echo $str . "<br />";
		}
	}
}

function convertStrToUtf8($str, $fromCharset) {
	if (stripos($fromCharset, "utf-8") !== false) { return $str; }
	
	if (stripos($fromCharset, "ascii") !== false) { $fromCharset = "ascii"; } // e.g. "us-ascii" => "ascii" - Outlook, and maybe others, sometimes send us-ascii (which is not a valid charset for mb_convert_encoding())
	$str = mb_convert_encoding($str, "UTF-8", $fromCharset);
	
	return $str;
}


?>