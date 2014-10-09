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
	include "appInit.php";
	include "siteGenFunctions.php";
	include "siteDbFunctions.php";
	
	$conn = connect_to_DB();
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
				<td><input type="number" min="0" max="1" id="oecmMode" value="<?php echo getVarFromColorRing("outExternalCtrlMode"); ?>" onkeyup="ifEnterClickBtn(event, 'oecmBtn')" /></td>
				<td><input type="number" min="0" max="255" id="oecmFlowSpeed" value="<?php echo getVarFromColorRing("outExternalCtrlModeFlowSpeed"); ?>" onkeyup="ifEnterClickBtn(event, 'oecmBtn')" /></td>
				<td><input type="number" min="1" max="60" id="oecmFlowNumFlows" value="<?php echo getVarFromColorRing("outExternalCtrlModeFlowNumSections"); ?>" onkeyup="ifEnterClickBtn(event, 'oecmBtn')" /></td>
				<td><input type="button" id="oecmBtn" value="Submit" onClick="oecmSubmit()" /></td>
			</tr>
		</table>
	
		<h3>Inside External Ctrl Mode (iecm)</h3>
		<table border=1>
			<tr>
				<th>Mode</th> <th>Speed (if Flow)</th> <th>Num Sections (if Flow)</th>
			</tr>
			<tr>
				<td><input type="number" min="0" max="1" id="iecmMode" value="<?php echo getVarFromColorRing("inExternalCtrlMode"); ?>" onkeyup="ifEnterClickBtn(event, 'iecmBtn')" /></td>
				<td><input type="number" min="0" max="255" id="iecmFlowSpeed" value="<?php echo getVarFromColorRing("inExternalCtrlModeFlowSpeed"); ?>" onkeyup="ifEnterClickBtn(event, 'iecmBtn')" /></td>
				<td><input type="number" min="1" max="60" id="iecmFlowNumFlows" value="<?php echo getVarFromColorRing("inExternalCtrlModeFlowNumSections"); ?>" onkeyup="ifEnterClickBtn(event, 'iecmBtn')" /></td>
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
					<input type="number" min="-12" max="12" maxlength="3" size="3" id="tzAdj" value="<?php echo getVarFromColorRing("tzAdj"); ?>" onkeyup="ifEnterClickBtn(event, 'tzAdjBtn')" />
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
				<td><input type="number" min="0" max="23" maxlength="2" size="2" id="timeHours" value="<?php echo $crTime[0]; ?>" onkeyup="ifEnterClickBtn(event, 'setTimeBtn')" />:</td>
				<td><input type="number" min="0" max="59" maxlength="2" size="2" id="timeMinutes" value="<?php echo $crTime[1]; ?>" onkeyup="ifEnterClickBtn(event, 'setTimeBtn')" />:</td>
				<td><input type="number" min="0" max="59" maxlength="2" size="2" id="timeSeconds" value="<?php echo $crTime[2]; ?>" onkeyup="ifEnterClickBtn(event, 'setTimeBtn')" /></td>
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
				<td><input type="number" min="0" max="30" maxlength="2" size="2" id="hourHandSize" value="<?php echo getVarFromColorRing("handSizeHour"); ?>" onkeyup="ifEnterClickBtn(event, 'handPropsBtn')" /></td>
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
				<td><input type="number" min="0" max="30" maxlength="2" size="2" id="minHandSize" value="<?php echo getVarFromColorRing("handSizeMin"); ?>" onkeyup="ifEnterClickBtn(event, 'handPropsBtn')" /></td>
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
				<td><input type="number" min="0" max="30" maxlength="2" size="2" id="secHandSize" value="<?php echo getVarFromColorRing("handSizeSec"); ?>" onkeyup="ifEnterClickBtn(event, 'handPropsBtn')" /></td>
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
			<td><input type="number" min="0" max="59" id="clapAmpThreshold" value="<?php echo getVarFromColorRing("clapAmpThreshold"); ?>" onkeyup="ifEnterClickBtn(event, 'clapBtn')" /></td>
		</tr>
		<tr>
			<td>Minimum Delay Until Next Clap (cs) [10-255] (def: 20):</td>
			<td><input type="number" min="10" max="255" id="clapMinDelayUntilNext" value="<?php echo getVarFromColorRing("clapMinDelayUntilNext"); ?>" onkeyup="ifEnterClickBtn(event, 'clapBtn')" /></td>
		</tr>
		<tr>
			<td>Window for Next Clap (cs) [0-255] (def: 20):</td>
			<td><input type="number" min="0" max="255" id="clapWindowForNext" value="<?php echo getVarFromColorRing("clapWindowForNext"); ?>" onkeyup="ifEnterClickBtn(event, 'clapBtn')" /></td>
		</tr>
		<tr>
			<td>Show Time for this Number of Seconds [0-255]:</td>
			<td><input type="number" min="0" max="255" id="clapShowTimeNumSeconds" value="<?php echo getVarFromColorRing("clapShowTimeNumSeconds"); ?>" onkeyup="ifEnterClickBtn(event, 'clapBtn')" /></td>
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
		
		echo '<tr><th>ColorRing</th><th>Database</th></tr>';
		echo '<tr>';
			echo '<td valign="top"><input type="button" id="sendAllCmdsBtn" value="Send All Cmds" onClick="sendAllCmdsSubmit()" /></td>';

			echo '<td>';
				echo '<table border=1>';
		
				// Saved LightShows DD
				echo '<tr><td>';
				$whereCondArrArr = "*";
				$result = dbSelect("LightShow", $whereCondArrArr, "ORDER BY `lightShowName` ASC");
				$numRecords = mysql_num_rows($result);
				echo 'Saved Light Shows: <select id="savedLightShowsDD" onChange="savedLightShowsDDChanged()" >';
				//$sel = $isLightShowInDB ? "" : "selected";
				$sel = "selected";
				echo '<option value="na" ' . $sel . '>- - - - -</option>';
		
				for ($i = 0; $i < $numRecords; $i++) {
					$row = mysql_fetch_assoc($result);
			
					$lightShowIdx = $row['idx'];
					$lightShowName = $row['lightShowName'];
			
					//$sel = $lsIdx == $dbLightShowIdx ? "selected" : "";
					$sel = "";
					echo '<option value="' . $lightShowIdx . '" ' . $sel . '>' . $lightShowName . '</option>';
				}
				echo '</select>';
				echo '</td></tr>';
		
				// Saved Light Shows Area
				echo '<tr><td>Light Show Name: <input type="text" size="40" id="lightShowName" value="" onkeyup="ifEnterClickBtn(event, \'saveLightShowBtn\')" /> <input type="button" id="saveLightShowBtn" value="Save Entire Light Show" onClick="saveLightShowSubmit()" /></td></tr>';
				echo '<tr><td><p id="saveLightShowResult"></p></td></tr>';
		
				echo '</td></tr>';
				echo '</table>';
			echo '</td>';
		echo '</tr>';
		
		// For Each CMD
		for ($cmdPos = 0; $cmdPos < $maxNumStripCmds * 2; $cmdPos++) {
			$cmdPosPrefix = "cmdPos" . str_pad($cmdPos, 3, "0", STR_PAD_LEFT);  // e.g. cmdPos001, 009, 010, etc.

			$paramsStr = $cmdPos;
			$cmdBytesArr = getByteArrayFromColorRing("setHackNameToCmd", $paramsStr);
			$cmdBytesStr = cmdBytesArr2Str($cmdBytesArr);
			
			// Check if this retrieved cmd is also in MySQL DB & set appropriate variables
			$whereCondArrArr = array(array("cmdBytesStr", "=", $cmdBytesStr));
			$result = dbSelect("SingleCmd", $whereCondArrArr);
			$numRecords = mysql_num_rows($result);
			
			// Init
			$isCmdInDB = false;
			$dbCmdIdx = 1;  // "Invalid"
			$dbCmdName = "";
			
			if ($numRecords > 0) {
				$row = mysql_fetch_assoc($result);  // just get the 1st record (if more exist, they'll be ignored)
				$dbCmdIdx = $row['idx'];
				if ($dbCmdIdx != 1) {  // if not "Invalid"
					$isCmdInDB = true;
					$dbCmdName = $row['cmdName'];
				}
			}
			
			$cmdType = $cmdBytesArr[0];
		
			$table = getCmdTable($cmdBytesArr, $cmdPos);
		
			//$cmdPosPrefix = "cmdPos" . str_pad($cmdPos, 3, "0", STR_PAD_LEFT);  // e.g. cmdPos001, 009, 010, etc.
		
			// === Display Cmd Table ===
			echo '<tr>';
				echo '<td>';
			
					// Display Cmd # & "sendOneCmd" button
					echo '<h2>Cmd: ' . $cmdPos . ' <input type="button" id="' . $cmdPosPrefix . 'sendOneCmdBtn" value="Send Just This Cmd" onClick="sendOneCmdSubmit(' . $cmdPos . ')" /></h2>';
			
					// Cmd Type DD
					echo 'Cmd Type: <select id="' . $cmdPosPrefix . 'cmdTypeDD" onChange="cmdTypeDDChanged(' . $cmdPos . ')" >';
					$sel = ($cmdType < 0 || $cmdType > 3) ? "selected" : "";
					echo '<option value="-1" ' . $sel . '>None</option>';
					$sel = $cmdType == CMDTYPE_SSP ? "selected" : "";
					echo '<option value="' . CMDTYPE_SSP . '" ' . $sel . '>Set Sequential Pixels</option>';
					//$sel = $cmdType == 1 ? "selected" : "";
					//echo '<option value="bcg" ' . $sel . '>Build Color Gradient</option>';
					$sel = $cmdType == CMDTYPE_SHIFT ? "selected" : "";
					echo '<option value="' . CMDTYPE_SHIFT . '" ' . $sel . '>Shift</option>';
					$sel = $cmdType == CMDTYPE_FLOW ? "selected" : "";
					echo '<option value="' . CMDTYPE_FLOW . '" ' . $sel . '>Flow</option>';
					echo '</select>';
		
					// Cmd Table itself
					echo '<div id="' . $cmdPosPrefix . 'div">';
					echo $table;
					echo '</div>';

				echo '</td>';
			
				echo '<td valign="top">';
					echo '<table border=1>';
						echo '<tr><td>';
							// Saved Cmds DD
							$whereCondArrArr = "*";
							$result = dbSelect("SingleCmd", $whereCondArrArr, "ORDER BY `cmdName` ASC");
							$numRecords = mysql_num_rows($result);
							echo 'Saved Cmds: <select id="' . $cmdPosPrefix . 'savedCmdsDD" onChange="savedCmdsDDChanged(' . $cmdPos . ')" >';
							$sel = $isCmdInDB ? "" : "selected";
							echo '<option value="-1" ' . $sel . '>- - - - -</option>';
			
							for ($i = 0; $i < $numRecords; $i++) {
								$row = mysql_fetch_assoc($result);
				
								$cmdIdx = $row['idx'];
								$cmdName = $row['cmdName'];
								//$cmdBytesStr = $row['cmdBytesStr'];
				
								if ($cmdIdx != 1) {  // If not "Invalid"
									$sel = $cmdIdx == $dbCmdIdx ? "selected" : "";
									echo '<option value="' . $cmdIdx . '" ' . $sel . '>' . $cmdName . '</option>';
								}
							}
							echo '</select>';
						echo '</td></tr>';
			
						// Save Cmd Area.
						echo '<tr><td>Cmd Name: <input type="text" size="40" id="' . $cmdPosPrefix . 'cmdName" value="' . $dbCmdName . '" onkeyup="ifEnterClickBtn(event, \'' . $cmdPosPrefix . 'saveCmdBtn\')" /> <input type="button" id="' . $cmdPosPrefix . 'saveCmdBtn" value="Save Cmd" onClick="saveCmdSubmit(' . $cmdPos . ')" /></td></tr>';
						echo '<tr><td><p id="' . $cmdPosPrefix . 'saveCmdResult"></p></td></tr>';
					echo '</table>';
				echo '</td>';
			echo '</tr>';

			// Send ALL cmds Button
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
