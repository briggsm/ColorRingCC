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

include "ColorRingConnectionInfo.php";  // defines $colorringIP, $udpPort

// Get data
$target = "http://" . $colorringIP;

// We should receive just 1 of these options: packet, varRequest, setHackName
$packet = NULL;
$varRequest = NULL;
$setHackName = NULL;

$isVarRequest = false;
$isSetHackName = false;
if (isset($_GET['varRequest'])) {
	$varRequest = $_GET['varRequest'];  // variable requests (e.g. temperature)
	$isVarRequest = true;
} elseif (isset($_GET['setHackName'])) {
	$setHackName = $_GET['setHackName'];
	$isSetHackName = true;
} else {
	$packet = $_GET['packet']; // Should start with the first byte of the packet. 
}

// Create cURL call
if ($isVarRequest) {
	$service_url = $target . '/' . $varRequest;
} elseif ($isSetHackName) {
	$service_url = $target . '/setHackName?params=' . $setHackName;
} else {
	// Packet
	$service_url = $target . '/packet?params=' . $packet;
}
$curl = curl_init($service_url);

// Send cURL to board
curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 ); 
//curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 120);  // was 0.5, needs to be big'ish 'cuz sending 20+ cmds to Arduino takes long time (little less than 1 sec. each)
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);  // But if we send commands one at a time (sequentially), should never take more than 5 seconds each.
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$curl_response = curl_exec($curl);
curl_close($curl);

//Print answer
if ($curl_response == ""){
	echo "{\"connected\": false, \"service_url\": \"" . $service_url . "\"}";
}

//echo "curl_response: " . $curl_response;
echo $curl_response;

?>