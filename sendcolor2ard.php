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
	Send 4-byte (of data) UDP packet to Arduino (1 byte to specify strip, other 3 for R,G,B)
*/

include "ColorRingConnectionInfo.php";  // defines $colorringIP, $udpPort

//$isOutside = 1;
$colorUsage = 0;
$color = "000000";

/*
if (isset($_GET['isOutside'])) {
	$isOutside = $_GET['isOutside'];  // Should be "1" or "0"
}
*/
if (isset($_GET['colorUsage'])) {
	$colorUsage = $_GET['colorUsage'];  // Should be an int from 0 to 255
}

if (isset($_GET['color'])) {
	$color = $_GET['color'];
}

$fp = stream_socket_client("udp://" . $colorringIP . ":" . $udpPort, $errno, $errstr);

if (!$fp) {
    echo "ERROR: $errno - $errstr<br />\n";
} else {
	
	$r = intval(substr($color, 0, 2), 16);
	$g = intval(substr($color, 2, 2), 16);
	$b = intval(substr($color, 4, 2), 16);
	//echo "r: " . $r . ", g: " . $g . ", b: " . $b;
	
	//fwrite($fp, chr($isOutside) . chr($r) . chr($g) . chr($b));
	fwrite($fp, chr($colorUsage) . chr($r) . chr($g) . chr($b));
    fclose($fp);
}
	
?>