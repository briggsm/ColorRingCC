<!DOCTYPE html>
<html>
<head>
	<script src="jquery-2.0.3.min.js"></script>
	<script src="script.js"></script>
    <script src='spectrum.js'></script>
    <link rel='stylesheet' href='spectrum.css' />
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>Color Ring</title>
</head>

<body>
	<?php
	include "ColorRingConnectionInfo.php";
	include "functions.php";
	?>
	<table border="1" cellpadding="10"><tr><td>
		<h3>OpMode</h3>
		<?php
		$opMode = getVarFromColorRing("opMode");
		//echo "opMode is: " . $opMode . "<br />";
		$opModeOutside = ($opMode & 0xF0) >> 4;
		$opModeInside = $opMode & 0x0F;
		?>
		<table border=1>
			<tr>
				<th>Outside Strip</th>
				<th>Inside Strip</th>
			</tr>
			<tr>
				<!-- Note: these are also defined in the ColorRing's "AllDefs.h" file -->
				<td>
					<select id="opModeOutsideDD" onChange="opModeDDChanged()">
						<?php $sel = $opModeOutside == 0 ? "selected" : ""; ?>
						<option value=0 <?php echo $sel; ?>>Internal</option>
						<?php $sel = $opModeOutside == 1 ? "selected" : ""; ?>
						<option value=1 <?php echo $sel; ?>>External</option>
						<?php $sel = $opModeOutside == 2 ? "selected" : ""; ?>
						<option value=2 <?php echo $sel; ?>>Clock</option>
						<?php $sel = $opModeOutside == 3 ? "selected" : ""; ?>
						<option value=3 <?php echo $sel; ?>>Audio Visualizer</option>
						<?php $sel = $opModeOutside == 4 ? "selected" : ""; ?>
						<option value=4 <?php echo $sel; ?>>Audio Level</option>
					</select>
				</td>
				<td>
					<select id="opModeInsideDD" onChange="opModeDDChanged()">
						<?php $sel = $opModeInside == 0 ? "selected" : ""; ?>
						<option value=0 <?php echo $sel; ?>>Internal</option>
						<?php $sel = $opModeInside == 1 ? "selected" : ""; ?>
						<option value=1 <?php echo $sel; ?>>External</option>
						<?php $sel = $opModeInside == 2 ? "selected" : ""; ?>
						<option value=2 <?php echo $sel; ?>>Clock</option>
						<?php $sel = $opModeInside == 3 ? "selected" : ""; ?>
						<option value=3 <?php echo $sel; ?>>Audio Visualizer</option>
						<?php $sel = $opModeInside == 4 ? "selected" : ""; ?>
						<option value=4 <?php echo $sel; ?>>Audio Level</option>
					</select>
				</td>
			</tr>
		</table>
	</td></tr></table>
	
	<!--input type="text" id="opModeText" value="<?php echo $opMode; ?>" onkeyup="ifEnterClickBtn(event, 'opModeBtn')" />
	<input type="button" id="opModeBtn" value="Set Mode" onClick="setOpMode()" /><br /-->
	
	
	<table border="1" cellpadding="10"><tr><td>
		<p>
			<strong>Mode</strong><br />
			0 = Strip Color<br />
			1 = Flow<br />
		</p>
		<h3>Outside External Ctrl Mode (oecm)</h3>
		<table border=1>
			<tr>
				<th>Mode</th> <th>Speed (if Flow)</th> <th>Num Sections (if Flow)</th>
			</tr>
			<tr>
				<td><input type="text" id="oecmMode" value="<?php echo getVarFromColorRing("outExternalCtrlMode"); ?>" onkeyup="ifEnterClickBtn(event, 'oecmBtn')" /></td>
				<td><input type="text" id="oecmFlowSpeed" value="<?php echo getVarFromColorRing("outExternalCtrlModeFlowSpeed"); ?>" onkeyup="ifEnterClickBtn(event, 'oecmBtn')" /></td>
				<td><input type="text" id="oecmFlowNumFlows" value="<?php echo getVarFromColorRing("outExternalCtrlModeFlowNumSections"); ?>" onkeyup="ifEnterClickBtn(event, 'oecmBtn')" /></td>
				<td><input type="button" id="oecmBtn" value="Submit" onClick="oecmSubmit()" /></td>
			</tr>
		</table>
	
		<h3>Inside External Ctrl Mode (iecm)</h3>
		<table border=1>
			<tr>
				<th>Mode</th> <th>Speed (if Flow)</th> <th>Num Sections (if Flow)</th>
			</tr>
			<tr>
				<td><input type="text" id="iecmMode" value="<?php echo getVarFromColorRing("inExternalCtrlMode"); ?>" onkeyup="ifEnterClickBtn(event, 'iecmBtn')" /></td>
				<td><input type="text" id="iecmFlowSpeed" value="<?php echo getVarFromColorRing("inExternalCtrlModeFlowSpeed"); ?>" onkeyup="ifEnterClickBtn(event, 'iecmBtn')" /></td>
				<td><input type="text" id="iecmFlowNumFlows" value="<?php echo getVarFromColorRing("inExternalCtrlModeFlowNumSections"); ?>" onkeyup="ifEnterClickBtn(event, 'iecmBtn')" /></td>
				<td><input type="button" id="iecmBtn" value="Submit" onClick="iecmSubmit()" /></td>
			</tr>
		</table>
	

		<h3>Outside External Ctrl Mode Color</h3>
		<table border=1>
			<tr>
				<td><input id="oecmColor" value="FF0000" /></td>
				<script>
					//initEcmColorPicker('oecmStripColor', 1);  // 1 => Outside
					initRealTimeColorPicker('oecmColor', <?php echo COLOR_USAGE_OUT_ECM; ?>);
				</script>
			</tr>
		</table>
	
		<h3>Inside External Ctrl Mode Color</h3>
		<table border=1>
			<tr>
				<td><input id="iecmColor" value="00FF00" /></td>
				<script>
					//initEcmColorPicker('iecmStripColor', 0);  // 0 => Inside
					initRealTimeColorPicker('iecmColor', <?php echo COLOR_USAGE_IN_ECM; ?>);
				</script>
			</tr>
		</table>
	</td></tr></table>
	
	
	
	
	<table border="1" cellpadding="10"><tr><td>
		<h3>Clock</h3>
		<table border="1"><tr><td>
			<p><strong>Automatic</strong></p>
			<table><tr>
				<td>
					<?php
					$chkd = getVarFromColorRing("useNtpServer") == "1" ? "checked" : "";
					?>
					<label><input type="checkbox" id="useNtpServerCB" value="" <?php echo $chkd ?> onchange="useNtpServerSubmit()" /> Use NTP Time Server</label>
				</td>
				<!--td><input type="button" id="useNtpServerBtn" value="Submit" onClick="useNtpServerSubmit()" /></td-->
			</tr></table>

			<table><tr>
				<td>Time Zone Adjustment: </td>
				<td>
					<input type="text" maxlength="3" size="3" id="tzAdj" value="<?php echo getVarFromColorRing("tzAdj"); ?>" onkeyup="ifEnterClickBtn(event, 'tzAdjBtn')" />
				</td>
				<td>(Positive or <br />Negative Integer)</td>
				<td><input type="button" id="tzAdjBtn" value="Submit" onClick="tzAdjSubmit()" /></td>
			</tr></table>
		
			<table><tr>
				<td>
					<?php
					$chkd = getVarFromColorRing("isDst") == "1" ? "checked" : "";
					?>
					<label><input type="checkbox" id="isDstCB" value="" <?php echo $chkd ?> onchange="isDstSubmit()" /> DST (+1 hour)</label>
				</td>
			</tr></table>
		</td></tr></table>
		
		<table border="1"><tr><td>
			<p><strong>Manual</strong> (Uncheck "Use NTP Time Server")</p>
			<table><tr>
				<?php
				$crTime = getByteArrayFromColorRing("setHackNameToTime", "");
				?>
				<!--td><input type="button" id="getTimeBtn" value="Get Time" onClick="getTimeSubmit()" /></td-->
				<td><input type="text" maxlength="2" size="2" id="timeHours" value="<?php echo $crTime[0]; ?>" onkeyup="ifEnterClickBtn(event, 'setTimeBtn')" />:</td>
				<td><input type="text" maxlength="2" size="2" id="timeMinutes" value="<?php echo $crTime[1]; ?>" onkeyup="ifEnterClickBtn(event, 'setTimeBtn')" />:</td>
				<td><input type="text" maxlength="2" size="2" id="timeSeconds" value="<?php echo $crTime[2]; ?>" onkeyup="ifEnterClickBtn(event, 'setTimeBtn')" /></td>
				<td><input type="button" id="setTimeBtn" value="Set Time" onClick="setTimeSubmit()" /></td>
			</tr></table>
		</td></tr></table>
		
		<p></p>
		
		<p><strong>Hand Properties</strong></p>
		<table border="1"><tr><td>
			<tr>
				<th></th><th>Color</th><th>Size</th>
			</tr>
			<tr>
				<?php
				$paramsStr = "" . HANDCOLOR_HOUR;
				$colorBA = getByteArrayFromColorRing("setHackNameToColor", $paramsStr);
				?>
				<td>Hour Hand</td>
				<td><input id="hourHandColor" value="<?php echo sprintf("%02X", intval($colorBA[0], 0)) . sprintf("%02X", intval($colorBA[1], 0)) . sprintf("%02X", intval($colorBA[2], 0)) ?>" /></td>
				<script>
					initRealTimeColorPicker('hourHandColor', <?php echo COLOR_USAGE_HOUR_HAND; ?>);
				</script>
				<td><input type="text" maxlength="2" size="2" id="hourHandSize" value="<?php echo getVarFromColorRing("handSizeHour"); ?>" onkeyup="ifEnterClickBtn(event, 'handPropsBtn')" /></td>
			</tr>
			<tr>
				<?php
				$paramsStr = "" . HANDCOLOR_MIN;
				$colorBA = getByteArrayFromColorRing("setHackNameToColor", $paramsStr);
				?>
				<td>Minute Hand</td>
				<td><input id="minHandColor" value="<?php echo sprintf("%02X", intval($colorBA[0], 0)) . sprintf("%02X", intval($colorBA[1], 0)) . sprintf("%02X", intval($colorBA[2], 0)) ?>" /></td>
				<script>
					initRealTimeColorPicker('minHandColor', <?php echo COLOR_USAGE_MIN_HAND; ?>);
				</script>
				<td><input type="text" maxlength="2" size="2" id="minHandSize" value="<?php echo getVarFromColorRing("handSizeMin"); ?>" onkeyup="ifEnterClickBtn(event, 'handPropsBtn')" /></td>
			</tr>
			<tr>
				<?php
				$paramsStr = "" . HANDCOLOR_SEC;
				$colorBA = getByteArrayFromColorRing("setHackNameToColor", $paramsStr);
				?>
				<td>Second Hand</td>
				<td><input id="secHandColor" value="<?php echo sprintf("%02X", intval($colorBA[0], 0)) . sprintf("%02X", intval($colorBA[1], 0)) . sprintf("%02X", intval($colorBA[2], 0)) ?>" /></td>
				<script>
					initRealTimeColorPicker('secHandColor', <?php echo COLOR_USAGE_SEC_HAND; ?>);
				</script>
				<td><input type="text" maxlength="2" size="2" id="secHandSize" value="<?php echo getVarFromColorRing("handSizeSec"); ?>" onkeyup="ifEnterClickBtn(event, 'handPropsBtn')" /></td>
			</tr>
			<tr>
				<td colspan="3"><input type="button" id="handPropsBtn" value="Submit" onClick="handPropsSubmit()" />(to lock it in)</td>
			</tr>
			
		</td></tr></table>
		
		<p><strong>Which Strips?</strong></p>
		<table border=1>
			<tr>
				<th></th>
				<th>Outside</th>
				<th>Inside</th>
			</tr>
			<tr>
				<td>Hour Hand</td>
				<td>
					<?php $chkd = getVarFromColorRing("dispHourHandOut") == "1" ? "checked" : ""; ?>
					<input type="checkbox" id="dispHourHandOutCB" value="" <?php echo $chkd ?> onchange="dispClockHandOutInSubmit()" />
				</td>
				<td>
					<?php $chkd = getVarFromColorRing("dispHourHandIn") == "1" ? "checked" : ""; ?>
					<input type="checkbox" id="dispHourHandInCB" value="" <?php echo $chkd ?> onchange="dispClockHandOutInSubmit()" />
				</td>
			</tr>
			<tr>
				<td>Minute Hand</td>
				<td>
					<?php $chkd = getVarFromColorRing("dispMinHandOut") == "1" ? "checked" : ""; ?>
					<input type="checkbox" id="dispMinHandOutCB" value="" <?php echo $chkd ?> onchange="dispClockHandOutInSubmit()" />
				</td>
				<td>
					<?php $chkd = getVarFromColorRing("dispMinHandIn") == "1" ? "checked" : ""; ?>
					<input type="checkbox" id="dispMinHandInCB" value="" <?php echo $chkd ?> onchange="dispClockHandOutInSubmit()" />
				</td>
			</tr>
			<tr>
				<td>Second Hand</td>
				<td>
					<?php $chkd = getVarFromColorRing("dispSecHandOut") == "1" ? "checked" : ""; ?>
					<input type="checkbox" id="dispSecHandOutCB" value="" <?php echo $chkd ?> onchange="dispClockHandOutInSubmit()" />
				</td>
				<td>
					<?php $chkd = getVarFromColorRing("dispSecHandIn") == "1" ? "checked" : ""; ?>
					<input type="checkbox" id="dispSecHandInCB" value="" <?php echo $chkd ?> onchange="dispClockHandOutInSubmit()" />
				</td>
			</tr>
			
		</table>
		
		<!-- === Colored 5's === -->
		<p><strong>Colored 5's</strong></p>
		
		<table border="1" cellpadding="10"><tr><td>
			<table border=1>
				<tr>
					<th></th>
					<th>Outside</th>
					<th>Inside</th>
				</tr>
				<tr>
					<td>Enable</td>
					<td>
						<?php $chkd = getVarFromColorRing("colored5sEnableOut") == "1" ? "checked" : ""; ?>
						<input type="checkbox" id="colored5sEnableOutCB" value="" <?php echo $chkd ?> onchange="colored5sSubmit()" />
					</td>
					<td>
						<?php $chkd = getVarFromColorRing("colored5sEnableIn") == "1" ? "checked" : ""; ?>
						<input type="checkbox" id="colored5sEnableInCB" value="" <?php echo $chkd ?> onchange="colored5sSubmit()" />
					</td>
				</tr>
			</table>
			
			<h3>Outside</h3>
			<?php
			$paramsStr = "" . OUT_COLORED5S_COLOR;
			$colorBA = getByteArrayFromColorRing("setHackNameToColor", $paramsStr);
			?>
			<table border=1>
				<tr>
					<td><input id="outColored5sColor" value="<?php echo sprintf("%02X", intval($colorBA[0], 0)) . sprintf("%02X", intval($colorBA[1], 0)) . sprintf("%02X", intval($colorBA[2], 0)) ?>" /></td>
					<script>
						//initColored5sColorPicker("outColored5sColor");
						initRealTimeColorPicker('outColored5sColor', <?php echo COLOR_USAGE_OUT_COLORED5S; ?>);
					</script>
					<td><input type="button" id="outColored5sColorBtn" value="Submit" onClick="colored5sSubmit()" />(to lock it in)</td>
				</tr>
			</table>
	
			<h3>Inside</h3>
			<?php
			$paramsStr = "" . IN_COLORED5S_COLOR;
			$colorBA = getByteArrayFromColorRing("setHackNameToColor", $paramsStr);
			?>
			<table border=1>
				<tr>
					<td><input id="inColored5sColor" value="<?php echo sprintf("%02X", intval($colorBA[0], 0)) . sprintf("%02X", intval($colorBA[1], 0)) . sprintf("%02X", intval($colorBA[2], 0)) ?>" /></td>
					<script>
						//initColored5sColorPicker("inColored5sColor");
						initRealTimeColorPicker('inColored5sColor', <?php echo COLOR_USAGE_IN_COLORED5S; ?>);
					</script>
					<td><input type="button" id="inColored5sColorBtn" value="Submit" onClick="colored5sSubmit()" />(to lock it in)</td>
				</tr>
			</table>
		</td></tr></table>
		<!-- === end Colored 5's -->
		
	</td></tr></table>
	
	
	<!-- === Clap for Time === -->
	<h3>Clap for Time</h3>
	<table border=1>
		<tr>
			<td>Enable:</td>
			<td>
				<?php $chkd = getVarFromColorRing("enableClapOut") == "1" ? "checked" : ""; ?>
				<label><input type="checkbox" id="enableClapOutCB" value="" <?php echo $chkd ?> onchange="clapSubmit()" /> Outside</label>
				<?php $chkd = getVarFromColorRing("enableClapIn") == "1" ? "checked" : ""; ?>
				<label><input type="checkbox" id="enableClapInCB" value="" <?php echo $chkd ?> onchange="clapSubmit()" /> Inside</label>
			</td>
		</tr>
		
		<tr>
			<td>Amplitude Threshold [0-59]:</td>
			<td><input type="text" id="clapAmpThreshold" value="<?php echo getVarFromColorRing("clapAmpThreshold"); ?>" onkeyup="ifEnterClickBtn(event, 'clapBtn')" /></td>
		</tr>
		<tr>
			<td>Minimum Delay Until Next Clap (cs) [10-255] (def: 20):</td>
			<td><input type="text" id="clapMinDelayUntilNext" value="<?php echo getVarFromColorRing("clapMinDelayUntilNext"); ?>" onkeyup="ifEnterClickBtn(event, 'clapBtn')" /></td>
		</tr>
		<tr>
			<td>Window for Next Clap (cs) [0-255] (def: 20):</td>
			<td><input type="text" id="clapWindowForNext" value="<?php echo getVarFromColorRing("clapWindowForNext"); ?>" onkeyup="ifEnterClickBtn(event, 'clapBtn')" /></td>
		</tr>
		<tr>
			<td>Show Time for this Number of Seconds [0-255]:</td>
			<td><input type="text" id="clapShowTimeNumSeconds" value="<?php echo getVarFromColorRing("clapShowTimeNumSeconds"); ?>" onkeyup="ifEnterClickBtn(event, 'clapBtn')" /></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="button" id="clapBtn" value="Submit" onClick="clapSubmit()" /></td>
		</tr>
	</table>
	
	
	<h3>Get Variables</h3>
	<table border=1>
		<tr>
			<td>MAX_NUM_STRIPCMDS</td>
			<td><input type="button" id="maxNumStripCmdsBtn" value="Get" onClick="maxNumStripCmdsSubmit()" /></td>
		</tr>
		<tr>
			<td>MAX_STRIPCMD_SIZE</td>
			<td><input type="button" id="maxStripCmdSizeBtn" value="Get" onClick="maxStripCmdSizeSubmit()" /></td>
		</tr>
		<tr>
			<td>Dump EEPROM to Serial Monitor</td>
			<td><input type="button" id="dumpEepromSmBtn" value="Dump" onClick="dumpEepromSmSubmit()" /></td>
		</tr>
	</table>
	
	<h3>All Cmds</h3>
	<p>First half = Outside Strip Cmds<br />
		Second half = Inside Strip Cmds
	</p>
	<table border=1>
		<?php
		$maxNumStripCmds = getVarFromColorRing("maxNumStripCmds");
		//echo "maxNumStripCmds is: " . $maxNumStripCmds . "<br />";

		echo '<input type="hidden" id="maxNumStripCmds" value="' . $maxNumStripCmds . '" />';
		
		echo '<tr><td><input type="button" id="sendAllCmdsBtn" value="Send All Cmds" onClick="sendAllCmdsSubmit()" /></td></tr>';
		
		for ($cmdPos = 0; $cmdPos < $maxNumStripCmds * 2; $cmdPos++) {
		?>
			<tr><td>

				<?php
				/*
				//$target = "192.168.5.85";
				$target = $colorringIP;
				
				$request_url = $target . '/setHackNameToCmd?params=' . $cmdPos;
				*/
				
			
				//$cmdBytes = getRemoteByteArray($request_url);
				$paramsStr = $cmdPos;
				$cmdBytes = getByteArrayFromColorRing("setHackNameToCmd", $paramsStr);
			
			
				/*
				$curl = curl_init($request_url);
				curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 ); 
				curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);  // was 0.5
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				$curl_response = curl_exec($curl);
				curl_close($curl);
			
				$jsonB = json_decode($curl_response, true);
				//echo "jsonB: "; print_r($jsonB);
				$cmdBytesStr = $jsonB["name"];
				//echo "cmdBytesStr: " . $cmdBytesStr;
				$cmdBytes = explode(",", $cmdBytesStr);
				*/
				
				
				$cmdType = $cmdBytes[0];
			
				//echo "cmdBytes: <br />";
				//print_r($cmdBytes);
			
				$table = getCmdTable($cmdBytes, $cmdPos);
			
				// === Display Cmd Table ===
				echo "<h2>Cmd: " . $cmdPos . "</h2>";

				$cmdPosPrefix = "cmdPos" . str_pad($cmdPos, 3, "0", STR_PAD_LEFT);  // e.g. cmdPos001, 009, 010, etc.
				echo '<select id="' . $cmdPosPrefix . 'cmdTypeDD" onChange="cmdTypeDDChanged(this, ' . $cmdPos . ')" >';
				$sel = ($cmdType < 0 || $cmdType > 3) ? "selected" : "";
				echo '<option value="none" ' . $sel . '>None</option>';
				$sel = $cmdType == 0 ? "selected" : "";
				echo '<option value="ssp" ' . $sel . '>Set Sequential Pixels</option>';
				//$sel = $cmdType == 1 ? "selected" : "";
				//echo '<option value="bcg" ' . $sel . '>Build Color Gradient</option>';
				$sel = $cmdType == 2 ? "selected" : "";
				echo '<option value="shift" ' . $sel . '>Shift</option>';
				$sel = $cmdType == 3 ? "selected" : "";
				echo '<option value="flow" ' . $sel . '>Flow</option>';
				echo '</select>';
			
				echo '<div id="' . $cmdPosPrefix . 'div">';
				echo $table;
				echo '</div>';
				
				?>

			</td></tr>
	<?php
		echo '<tr><td><input type="button" id="' . $cmdPosPrefix . 'sendOneCmdBtn" value="Send Just This Cmd (' . $cmdPos . ')" onClick="sendOneCmdSubmit(' . $cmdPos . ')" /></td></tr>';
		echo '<tr><td><input type="button" id="sendAllCmdsBtn" value="Send All Cmds" onClick="sendAllCmdsSubmit()" /></td></tr>';
	}
	
	?>
		
	</table>	
	
	<h3>Status Area</h3>
	<table border=1 id="resulttable">
		<tr>
			<td><p id="result"></p></td>
		</tr>
	</table>
	

</body>

</html>
