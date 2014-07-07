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
	include "functions.php";
	?>

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
		<tr>
			<td>
				<select id="opModeOutsideDD" onChange="opModeDDChanged()">
					<?php $sel = $opModeOutside == 0 ? "selected" : ""; ?>
					<option value=0 <?php echo $sel; ?>>Internal</option>
					<?php $sel = $opModeOutside == 1 ? "selected" : ""; ?>
					<option value=1 <?php echo $sel; ?>>External</option>
					<?php $sel = $opModeOutside == 2 ? "selected" : ""; ?>
					<option value=2 <?php echo $sel; ?>>Clock (not implemented yet)</option>
					<?php $sel = $opModeOutside == 3 ? "selected" : ""; ?>
					<option value=3 <?php echo $sel; ?>>White 5's (not implemented yet)</option>
				</select>
			</td>
			<td>
				<select id="opModeInsideDD" onChange="opModeDDChanged()">
					<?php $sel = $opModeInside == 0 ? "selected" : ""; ?>
					<option value=0 <?php echo $sel; ?>>Internal</option>
					<?php $sel = $opModeInside == 1 ? "selected" : ""; ?>
					<option value=1 <?php echo $sel; ?>>External</option>
					<?php $sel = $opModeInside == 2 ? "selected" : ""; ?>
					<option value=2 <?php echo $sel; ?>>Clock (not implemented yet)</option>
					<?php $sel = $opModeInside == 3 ? "selected" : ""; ?>
					<option value=3 <?php echo $sel; ?>>White 5's (not implemented yet)</option>
				</select>
			</td>
		</tr>
	</table>
	
	<!--input type="text" id="opModeText" value="<?php echo $opMode; ?>" onkeyup="ifEnterClickBtn(event, 'opModeBtn')" />
	<input type="button" id="opModeBtn" value="Set Mode" onClick="setOpMode()" /><br /-->
	
	
	
	<h3>Out External Ctrl Mode (oecm)</h3>
	<p>
		<strong>Mode</strong><br />
		0 = Strip Color<br />
		1 = Flow<br />
	</p>
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
	
	<h3>In External Ctrl Mode (iecm)</h3>
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
			<td><input id="oecmStripColor" value="FF0000" /></td>
			<script>
				initEcmColorPicker('oecmStripColor', 1);  // 1 => Outside
			</script>
		</tr>
	</table>
	
	<h3>Inside External Ctrl Mode Color</h3>
	<table border=1>
		<tr>
			<td><input id="iecmStripColor" value="00FF00" /></td>
			<script>
				initEcmColorPicker('iecmStripColor', 0);  // 0 => Inside
			</script>
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
				$target = "192.168.5.85";
				$service_url = $target . '/setHackNameToCmd?params=' . $cmdPos;
			
				$curl = curl_init($service_url);
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
