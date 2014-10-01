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

// === Global Variables ===
var maxNumStripCmds;
var cbArr;  // cmdByte Array

// "Defines"
const CMD_TYPE_SSP = 0;
const CMD_TYPE_SHIFT = 2;
const CMD_TYPE_FLOW = 3;

const CMD_TYPE_STR_SSP = "ssp";
const CMD_TYPE_STR_SHIFT = "shift";
const CMD_TYPE_STR_FLOW = "flow";

function ifEnterClickBtn(event, btnId) {
	if (event.keyCode == 13) {
		$("#" + btnId).click();
	}
}

function stripCmdTextInputKeyUp(cmdPos) {
	//alert("event/cmdPos: " + cmdPos);
	var cmdPosPrefix = getCmdPosPrefix(cmdPos);
	if (event.keyCode == 13) { $("#" + cmdPosPrefix + "sendOneCmdBtn").click(); }
}

/*
function setOpMode() {
	// 0xAA => 170 	// Note: needs to be set as Integer (not as Hex)
	$.get("sendpkt2ard.php", {
		packet: "170," + $("#opModeText").val()
	}, function (data) {
		$("#result").html(data);
	});
}
*/

function oecmSubmit() {
	// oecm => Outside External Ctrl Mode
	// 0xBB => 187
	$.get("sendpkt2ard.php", {
		packet: "187," + $("#oecmMode").val() + "," + $("#oecmFlowSpeed").val() + "," + $("#oecmFlowNumFlows").val()
	}, function (data) {
		$("#result").html(data);
	});
}

function iecmSubmit() {
	// iecm => Inside External Ctrl Mode
	// 0xBC => 188
	$.get("sendpkt2ard.php", {
		packet: "188," + $("#iecmMode").val() + "," + $("#iecmFlowSpeed").val() + "," + $("#iecmFlowNumFlows").val()
	}, function (data) {
		$("#result").html(data);
	});
}

function colored5sSubmit() {
	// 0xBD => 189
	
	var chkdStrOut = $("#colored5sEnableOutCB").is(":checked") ? "1" : "0";
	var chkdStrIn = $("#colored5sEnableInCB").is(":checked") ? "1" : "0";
	
	$.get("sendpkt2ard.php", {
		packet: "189," + chkdStrOut + "," + chkdStrIn + "," + getColorStrCSV("outColored5sColor") + "," + getColorStrCSV("inColored5sColor")
	}, function (data) {
		$("#result").html(data);
	});
}

function clapSubmit() {
	// 0xE0 => 224
	
	var chkdStrOut = $("#enableClapOutCB").is(":checked") ? "1" : "0";
	var chkdStrIn = $("#enableClapInCB").is(":checked") ? "1" : "0";
	
	$.get("sendpkt2ard.php", {
		packet: "224," + chkdStrOut + "," + chkdStrIn + "," + $("#clapAmpThreshold").val() + "," + $("#clapMinDelayUntilNext").val() + "," + $("#clapWindowForNext").val() + "," + $("#clapShowTimeNumSeconds").val()
	}, function (data) {
		$("#result").html(data);
	});
}

function maxNumStripCmdsSubmit() {
	// Send a varRequest (variable request)
	$.get("sendpkt2ard.php", {
		varRequest: "maxNumStripCmds"
	}, function (data) {
		try {
			var obj = jQuery.parseJSON(data);
			var res = obj.maxNumStripCmds;
			if (res != null) {
				$("#result").html(res);
			} else {
				$("#result").html("Variable doesn't exist... Orig data: " + data);
			}
		} catch (err) {
			var errInfo = "parseJSON error: " + err + ". Orig data: " + data;
			$("#result").html(errInfo);
		}
	});
}

function maxStripCmdSizeSubmit() {
	// Send a varRequest (variable request)
	$.get("sendpkt2ard.php", {
		varRequest: "maxStripCmdSize"
	}, function (data) {
		try {
			var obj = jQuery.parseJSON(data);
			var res = obj.maxStripCmdSize;
			if (res != null) {
				$("#result").html(res);
			} else {
				$("#result").html("Variable doesn't exist... Orig data: " + data);
			}
		} catch (err) {
			var errInfo = "parseJSON error: " + err + ". Orig data: " + data;
			$("#result").html(errInfo);
		}
	});
}

function dumpEepromSmSubmit() {
	$.get("sendpkt2ard.php", {
		packet: "144"
	}, function (data) {
		$("#result").html(data);
	});
}

function sendOneCmdSubmit(cmdPos) {
	var cmdBytesStr = getCmdBytes(cmdPos);
	//alert ("sendOneCmdSubmit() cmdPos: " + cmdPos + ", cmdBytesStr: " + cmdBytesStr);
	
	if (cmdBytesStr == "") {
		cmdBytesStr = "255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255";
	}
	
	$.get("sendpkt2ard.php", {
		packet: "221," + cmdPos + "," + cmdBytesStr
	}, function (data) {
		$("#result").html($("#result").html() + "<br />" + data);
		$("#resulttable").hide().show(0); // Should force redraw, though doesn't seem to work in safari
	});
}

function sendAllCmdsSubmit() {
	// 0xDD => 221
	$("#result").html($("#result").html() + "<br />" + "Please Wait...");
	$("#resulttable").hide().show(0); // doesn't seem to work to force refresh - in Safari at least...
	
	jQuery.ajaxSetup({async:false});
	for (var cmdPos = 0; cmdPos < maxNumStripCmds * 2; cmdPos++) {
		sendOneCmdSubmit(cmdPos);
	}
	jQuery.ajaxSetup({async:true});
}

function saveCmdSubmit(cmdPos) {
	var cmdPosPrefix = getCmdPosPrefix(cmdPos);
	var cmdBytesStr = getCmdBytes(cmdPos);
	
	if (cmdBytesStr == "") {
		cmdBytesStr = "255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255";
		$("#" + cmdPosPrefix + "saveCmdResult").html("Cannot submit a blank/empty command.");
		return;
	}
	
	$.get("sendCmd2mysql.php", {
		cmdName: $("#" + cmdPosPrefix + "cmdName").val(),
		cmdBytesStr: cmdBytesStr
	}, function (data) {
		$("#" + cmdPosPrefix + "saveCmdResult").html(data);
	});
}

function saveLightShowSubmit() {
	// If a cmd already exists in DB, use it's idx for 'singleCmdIdxStr'.
	// If a cmd is NOT already in DB, create a new cmd, and then use it's idx. (name e.g.: ssp_<timestampMS>)
	
	var singleCmdIdxStr = "";
	
	// First, iterate through all cmds, and add their 'idx' into the 'singleCmdIdxStr' (if cmd not already in SingleCmd table in DB then create it!)
	for (var cmdPos = 0; cmdPos < maxNumStripCmds * 2; cmdPos++) {
		var cmdPosPrefix = getCmdPosPrefix(cmdPos);
		if ($("#" + cmdPosPrefix + "cmdTypeDD").val() == "-1") {  // "None"
			singleCmdIdxStr += "1,";  // "Invalid" cmd (all 255's)
			continue;  // to next for loop iteration.
		}
		
		var cmdBytesStr = getCmdBytes(cmdPos);
		
		jQuery.ajaxSetup({async:false});
		$.get("ajaxGetSavedCmd.php", {
			cmdBytesStr: cmdBytesStr
		}, function (data) {
			try {
				var obj = jQuery.parseJSON(data);
				var idx = obj.idx;

				if (idx != -1) {
					// Cmd must be in DB and 'idx' is it's Index!
					singleCmdIdxStr += idx + ",";
				} else {
					// Cmd not in DB, create new cmd, then use it's idx.
					var cmdPosPrefix = getCmdPosPrefix(cmdPos);
					var cmdType = $("#" + cmdPosPrefix + "cmdType").val();
					var cmdTypeInt = parseInt(cmdType, 10);  // String => int
					
					var cmdTypeStr = "";
					switch (cmdTypeInt) {
					case CMD_TYPE_SSP:
						cmdTypeStr = CMD_TYPE_STR_SSP;
						break;
					case CMD_TYPE_SHIFT:
						cmdTypeStr = CMD_TYPE_STR_SHIFT;
						break;
					case CMD_TYPE_FLOW:
						cmdTypeStr = CMD_TYPE_STR_FLOW;
						break;
					}
					
					var timestampMS = new Date().getTime();
					var newCmdName = cmdTypeStr + "_" + timestampMS;  // unique name
					
					// Insert the new record (single cmd) in the DB (SingleCmd table)
					$.get("sendCmd2mysql.php", {
						cmdName: newCmdName,
						cmdBytesStr: cmdBytesStr
					}, function (data) {
						try {
							var idx = -1;
							
							var obj = jQuery.parseJSON(data);
							var result = obj.result;
							if (result > 0) {
								idx = obj.idx;
							}
							
							singleCmdIdxStr += idx + ",";
						}  catch (err) {
							var errInfo = "parseJSON error: " + err + ". Orig data: " + data;
							$("#result").html(errInfo);
						}
					});
				}
			} catch (err) {
				var errInfo = "parseJSON error: " + err + ". Orig data: " + data;
				$("#result").html(errInfo);
			}
		});
		jQuery.ajaxSetup({async:true});
	}  // end for loop
	
	// Take off the last ","
	singleCmdIdxStr = singleCmdIdxStr.substring(0, singleCmdIdxStr.length-1);
	
	// Finally, insert whole LightShow record into DB
	$.get("sendLightShow2mysql.php", {
		lightShowName: $("#lightShowName").val(),
		singleCmdIdxStr: singleCmdIdxStr
	}, function (data) {
		try {
			var obj = jQuery.parseJSON(data);
			var statusMsg = obj.statusMsg;
			//$("#saveLightShowResult").html(statusMsg);  // Nicer
			$("#saveLightShowResult").html(data);  // Better for debug
			
		}  catch (err) {
			var errInfo = "parseJSON error: " + err + ". Orig data: " + data;
			$("#result").html(errInfo);
		}
	});
}

function useNtpServerSubmit() {
	// 0xC0 => 192
	var chkdStr = $("#useNtpServerCB").is(":checked") ? "1" : "0";
	
	$.get("sendpkt2ard.php", {
		packet: "192," + chkdStr
	}, function (data) {
		$("#result").html(data);
	});
}

function tzAdjSubmit() {
	// 0xC2 => 194
	$.get("sendpkt2ard.php", {
		packet: "194," + $("#tzAdj").val()
	}, function (data) {
		$("#result").html(data);
	});
}

function isDstSubmit() {
	// 0xC3 => 195
	var chkdStr = $("#isDstCB").is(":checked") ? "1" : "0";
	
	$.get("sendpkt2ard.php", {
		packet: "195," + chkdStr
	}, function (data) {
		$("#result").html(data);
	});
}

function setTimeSubmit() {
	// 0xC1 => 193
	$.get("sendpkt2ard.php", {
		packet: "193," + $("#timeHours").val() + "," + $("#timeMinutes").val() + "," + $("#timeSeconds").val()
	}, function (data) {
		$("#result").html(data);
	});
}

function handPropsSubmit() {
	// 0xC4 => 196 (Hand Sizes)
	// 0xC5 => 197 (Hand Colors)
	
	// Hand Sizes
	$.get("sendpkt2ard.php", {
		packet: "196," + $("#hourHandSize").val() + "," + $("#minHandSize").val() + "," + $("#secHandSize").val()
	}, function (data) {
		$("#result").html(data);
	});
	
	// Hand Colors
	$.get("sendpkt2ard.php", {
		packet: "197," + getColorStrCSV("hourHandColor") + "," + getColorStrCSV("minHandColor") + "," + getColorStrCSV("secHandColor")
	}, function (data) {
		$("#result").html(data);
	});
}

function dispClockHandOutInSubmit() {
	// 0xC6 => 198
	var hourOut = $("#dispHourHandOutCB").is(":checked") ? "1" : "0";
	var hourIn = $("#dispHourHandInCB").is(":checked") ? "1" : "0";
	var minOut = $("#dispMinHandOutCB").is(":checked") ? "1" : "0";
	var minIn = $("#dispMinHandInCB").is(":checked") ? "1" : "0";
	var secOut = $("#dispSecHandOutCB").is(":checked") ? "1" : "0";
	var secIn = $("#dispSecHandInCB").is(":checked") ? "1" : "0";
	
	$.get("sendpkt2ard.php", {
		packet: "198," + hourOut + "," + hourIn + "," + minOut + "," + minIn + "," + secOut + "," + secIn
	}, function (data) {
		$("#result").html(data);
	});
}



function getCmdPosPrefix(cmdPos) {
	return "cmdPos" + pad(cmdPos, 3);
}

function getBoolBits(cmdPos) {
	var boolBits = 0;

	idArr = ["bbDestructive", "bbDirection", "bbWrap", "bbIsAnim", "bbClearStrip", "bbGradiate", "bbGradiateLastPixelFirstColor"];

	for (var i = 0; i < idArr.length; i++) {
		var elem = $("#" + getCmdPosPrefix(cmdPos) + idArr[i]);
		if (elem.length) {  // if exists
			boolBits += elem.is(":checked") << i;
		}
	}
	
	return boolBits;
}

function getCmdBytes(cmdPos) {
	var cmdPosPrefix = getCmdPosPrefix(cmdPos);
	var cmdType = $("#" + cmdPosPrefix + "cmdType").val();
	
	//alert("getCmdBytes(" + cmdPos + "). cmdPosPrefix: " + cmdPosPrefix + ", cmdType: " + cmdType);
	
	var cmdBytes = cmdType + ",";
	
	switch (cmdType) {
	case "0":  // SetSeqPixels
		cmdBytes += $("#" + cmdPosPrefix + "startPixelNum").val() + ",";
		cmdBytes += $("#" + cmdPosPrefix + "numPixelsEachColor").val() + ",";
		cmdBytes += $("#" + cmdPosPrefix + "colorSeriesNumIter").val() + ",";
		cmdBytes += $("#" + cmdPosPrefix + "numPixelsToSkip").val() + ",";
		var numIter = $("#" + cmdPosPrefix + "numIter").val();
		cmdBytes += "" + ((numIter & 0xFF00) >> 8) + ",";  // MSB
		cmdBytes += "" + (numIter & 0x00FF)		   + ",";  // LSB
		
		var animDelay = $("#" + cmdPosPrefix + "animDelay").val();
		cmdBytes += "" + ((animDelay & 0xFF00) >> 8) + ",";  // MSB
		cmdBytes += "" + (animDelay & 0x00FF)		 + ",";  // LSB
		var pauseAfter = $("#" + cmdPosPrefix + "pauseAfter").val();
		cmdBytes += "" + ((pauseAfter & 0xFF00) >> 8) + ",";  // MSB
		cmdBytes += "" + (pauseAfter & 0x00FF)		  + ",";  // LSB
		
		cmdBytes += getBoolBits(cmdPos) + ",";
		
		cmdBytes += $("#" + cmdPosPrefix + "numColorsInSeries").val() + ",";
		for (var i = 0; i < 6; i++) {
			cmdBytes += getColorStrCSV(cmdPosPrefix + "colorSeriesArr" + i) + ",";
		}
		cmdBytes = cmdBytes.substring(0, cmdBytes.length-1);  // Take off the last ","
		
		cmdBytes = add255sToEnd(cmdBytes);
		
		break;
	case "2":  // Shift
		cmdBytes += $("#" + cmdPosPrefix + "startPixelNum").val() + ",";
		cmdBytes += $("#" + cmdPosPrefix + "endPixelNum").val() + ",";
		cmdBytes += $("#" + cmdPosPrefix + "numPixelsToSkip").val() + ",";
		var numIter = $("#" + cmdPosPrefix + "numIter").val();
		cmdBytes += "" + ((numIter & 0xFF00) >> 8) + ",";  // MSB
		cmdBytes += "" + (numIter & 0x00FF)		   + ",";  // LSB

		var animDelay = $("#" + cmdPosPrefix + "animDelay").val();
		cmdBytes += "" + ((animDelay & 0xFF00) >> 8) + ",";  // MSB
		cmdBytes += "" + (animDelay & 0x00FF) 		 + ",";  // LSB
		var pauseAfter = $("#" + cmdPosPrefix + "pauseAfter").val();
		cmdBytes += "" + ((pauseAfter & 0xFF00) >> 8) + ",";  // MSB
		cmdBytes += "" + (pauseAfter & 0x00FF)		  + ",";  // LSB
		
		cmdBytes += getBoolBits(cmdPos);
		
		cmdBytes = add255sToEnd(cmdBytes);
		
		break;
		
	case "3":  // Flow
		cmdBytes += $("#" + cmdPosPrefix + "startPixelNum").val() + ",";
		cmdBytes += $("#" + cmdPosPrefix + "endPixelNum").val() + ",";
		cmdBytes += $("#" + cmdPosPrefix + "numSections").val() + ",";
		cmdBytes += $("#" + cmdPosPrefix + "numPixelsEachColor").val() + ",";
		cmdBytes += $("#" + cmdPosPrefix + "colorSeriesNumIter").val() + ",";
		cmdBytes += $("#" + cmdPosPrefix + "numPixelsToSkip").val() + ",";

		var animDelay = $("#" + cmdPosPrefix + "animDelay").val();
		cmdBytes += "" + ((animDelay & 0xFF00) >> 8) + ",";  // MSB
		cmdBytes += "" + (animDelay & 0x00FF) 		 + ",";  // LSB
		var pauseAfter = $("#" + cmdPosPrefix + "pauseAfter").val();
		cmdBytes += "" + ((pauseAfter & 0xFF00) >> 8) + ",";  // MSB
		cmdBytes += "" + (pauseAfter & 0x00FF)		  + ",";  // LSB
		
		cmdBytes += getBoolBits(cmdPos) + ",";
		
		cmdBytes += $("#" + cmdPosPrefix + "numColorsInSeries").val() + ",";
		
		
		for (var i = 0; i < 6; i++) {
			cmdBytes += getColorStrCSV(cmdPosPrefix + "colorSeriesArr" + i) + ",";
		}
		cmdBytes = cmdBytes.substring(0, cmdBytes.length-1);  // Take off the last ","
		
		cmdBytes = add255sToEnd(cmdBytes);
		break;
		
	default:
		return "";
	}
	
	//alert("cmdBytes: " + cmdBytes);
	
	return cmdBytes;
}

function add255sToEnd(cmdBytes) {
	var numCommas = (cmdBytes.match(/,/g) || []).length;
	while (numCommas < 31) {
		cmdBytes += ",255";
		numCommas++;
	}

	return cmdBytes;
}

function pad(str, max) {
	str = str.toString();
	return str.length < max ? pad("0" + str, max) : str;
}

function savedCmdsDDChanged(cmdPos) {
	//alert('savedcmdDD');
	var cmdPosPrefix = getCmdPosPrefix(cmdPos);
	var cmdIdx = parseInt($("#" + cmdPosPrefix + "savedCmdsDD").val(), 10);
	
	if (cmdIdx == -1) {
		$("#" + cmdPosPrefix + "cmdTypeDD").val("-1");  // Change the cmdType dropdown box value. Note: doesn't trigger the 'change' event
		$("#" + cmdPosPrefix + "cmdTypeDD").change();
	} else {
	
		$.get("ajaxGetSavedCmd.php", {
			cmdIdx: cmdIdx
		}, function (data) {
			try {
				var obj = jQuery.parseJSON(data);
			} catch (err) {
				var errInfo = "parseJSON error: " + err + ". Orig data: " + data;
				$("#result").html(errInfo);
			}
			
			var cmdName = obj.cmdName;
			var cmdBytesStr = obj.cmdBytesStr;
		
			if (cmdName != null && cmdBytesStr != null) {
				// Fill in Cmd Name textbox
				$("#" + cmdPosPrefix + "cmdName").val(cmdName);
			
				// change the cmdType dropdown (and trigger the change event)
				var cba = cmdBytesStr.split(",");
				var cmdType = parseInt(cba[0], 10);
				cbArr[cmdPos][cmdType] = cmdBytesStr;
				$("#" + cmdPosPrefix + "cmdTypeDD").val(cmdType);  // Change the cmdType dropdown box value. Note: doesn't trigger the 'change' event
				$("#" + cmdPosPrefix + "cmdTypeDD").change();  // trigger the 'change' event
			} else {
				$("#result").html("Variable(s) doesn't exist... Orig data: " + data);
			}
		});
	}
}

function savedLightShowsDDChanged() {
	var selLightShowIdx = parseInt($("#savedLightShowsDD").val(), 10);
	
	$.get("ajaxGetSavedLightShow.php", {
		lightShowIdx: selLightShowIdx
	}, function (data) {
		try {
			var obj = jQuery.parseJSON(data);
		} catch (err) {
			var errInfo = "parseJSON error: " + err + ". Orig data: " + data;
			$("#result").html(errInfo);
		}
		
		var lightShowName = obj.lightShowName;
		var singleCmdIdxStr = obj.singleCmdIdxStr;
		
		if (lightShowName != null && singleCmdIdxStr != null) {

			// Fill in Cmd Name textbox
			$("#lightShowName").val(lightShowName);
		
			var singleCmdIdxArr = singleCmdIdxStr.split(",");
		
			for (var cmdPos = 0; cmdPos < singleCmdIdxArr.length; cmdPos++) {
				var cmdPosPrefix = getCmdPosPrefix(cmdPos);
				var idx = singleCmdIdxArr[cmdPos];
				if (idx != 1) {
					// Normal cmd
					$("#" + cmdPosPrefix + "savedCmdsDD").val(idx);
				} else {
					// "Invalid" cmd, so choose "None" (-1)
					$("#" + cmdPosPrefix + "savedCmdsDD").val("-1");
				}
				$("#" + cmdPosPrefix + "savedCmdsDD").change();
			}
		} else {
			$("#result").html("Variable(s) doesn't exist... Orig data: " + data);
		}
	});
}

function cmdTypeDDChanged(cmdPos) {
	//alert ("cmdTypeDDChanged(), cmdPos: " + cmdPos);
	var cmdPosPrefix = getCmdPosPrefix(cmdPos);
	
	var cmdType = parseInt($("#" + cmdPosPrefix + "cmdTypeDD").val(), 10);
	if (cmdType == -1) {
		$("#" + cmdPosPrefix + "div").html("");
		return;
	}

	var cmdBytesStr = cbArr[cmdPos][cmdType];
	
	//alert ("cmdTypeDDChanged(), cmdPos: " + cmdPos + ", cmdType: " + cmdType + ", cmdBytesStr: " + cmdBytesStr);

	$.get("ajaxGetCmdTable.php", {
		cmdBytesStr: cmdBytesStr,
		cmdPos: cmdPos
	}, function (data) {
		$("#" + cmdPosPrefix + "div").html(data);
	});

}

function opModeDDChanged() {
	var newOpMode = (($("#opModeOutsideDD").val() & 0x0F) << 4) + ($("#opModeInsideDD").val() & 0x0F);
	//alert("newOpMode: " + newOpMode);
	
	// 0xAA => 170 	// Note: needs to be set as Integer (not as Hex)
	$.get("sendpkt2ard.php", {
		packet: "170," + newOpMode
	}, function (data) {
		$("#result").html(data);
	});
}

function updateCbArrAndSend(cmdPos) {
	var cmdPosPrefix = getCmdPosPrefix(cmdPos);
	
	updateCbArr(cmdPos);
	$("#" + cmdPosPrefix + "sendOneCmdBtn").click();
}

function updateCbArr(cmdPos) {
	
	var cmdBytesStr = getCmdBytes(cmdPos);
	
	if (cmdBytesStr != "") {
		var cmdPosPrefix = getCmdPosPrefix(cmdPos);
		var cmdType = parseInt($("#" + cmdPosPrefix + "cmdType").val(), 10);
		cbArr[cmdPos][cmdType] = cmdBytesStr;

		//alert("updateCbArr(" + cmdPos + "), cmdType: " + cmdType + ", cmdBytesStr: " + cmdBytesStr);
	}
}

function initRealTimeColorPicker(id, colorUsage) {
	var elem = $('#' + id);
	elem.spectrum({
		//color: "#" + elem.val(),
		color: "#" + elem.attr('value'),
	    showInput: true,
		preferredFormat: "hex",
		clickoutFiresChange: true,
		move: function(color) {
			jQuery.ajaxSetup({async:false});
			$.get("sendcolor2ard.php", {
				colorUsage: colorUsage,
				color: color.toHexString().substring(1,3) + color.toHexString().substring(3,5) + color.toHexString().substring(5,7)
			}, function (data) {
				//$("#result").html($("#result").html() + "<br />" + data);
				//$("#resulttable").hide().show(0);
			});
			jQuery.ajaxSetup({async:true});
		},
		change: function(color) {
			elem.attr('value', color.toHexString().substring(1,7));
		}
	});
}

/*
function initEcmColorPicker(id, isOutside) {
	// isOutside should be either 1 (Outside) or 0 (Inside)
	var elem = $('#' + id);
	elem.spectrum({
		//color: "#" + elem.val(),
		color: "#" + elem.attr('value'),
	    showInput: true,
		preferredFormat: "hex",
		clickoutFiresChange: true,
		move: function(color) {
			jQuery.ajaxSetup({async:false});
			$.get("sendcolor2ard.php", {
				isOutside: isOutside,
				color: color.toHexString().substring(1,3) + color.toHexString().substring(3,5) + color.toHexString().substring(5,7)
			}, function (data) {
				//$("#result").html($("#result").html() + "<br />" + data);
				//$("#resulttable").hide().show(0);
			});
			jQuery.ajaxSetup({async:true});
		}
	});
}
*/

function initFullColorPicker(elem, changeFn) {
	if (elem.length) {  // if exists
		elem.spectrum({
			//color: "#" + elem.val(),
			color: "#" + elem.attr('value'),
			showPalette: true,
			localStorageKey: "spectrum.savedselections",
		    showInput: true,
			preferredFormat: "hex",
			clickoutFiresChange: true,
			change: changeFn
		});
	}
}

function initStripCmdColorPicker(cmdPos, id) {
	var cmdPosPrefix = getCmdPosPrefix(cmdPos);
	var elem = $("#" + cmdPosPrefix + id);
	
	var changeFn = function(color) {
		elem.attr('value', color.toHexString().substring(1,7));
		updateCbArr(cmdPos);
		$("#" + cmdPosPrefix + "sendOneCmdBtn").click();  // send cmd to ColorRing
	}
	
	initFullColorPicker(elem, changeFn);
}

/*
function initColored5sColorPicker(id) {
	var elem = $("#" + id);
	
	var changeFn = function(color) {
		elem.attr('value', color.toHexString().substring(1,7));
		$("#" + id + "Btn").click();
	}
	
	initFullColorPicker(elem, changeFn);
}
*/



function getColorStrCSV(id) {
	var colorStr = "";
	/*
	colorStr += "0x" + $("#" + id).attr('value').substring(0,2) + ",";   // R
	colorStr += "0x" + $("#" + id).attr('value').substring(2,4) + ",";   // G
	colorStr += "0x" + $("#" + id).attr('value').substring(4,6);		 // B
	*/
	//var hexVal = $("#" + id).attr('value').substring(0,2);   // R
	colorStr += hexdec($("#" + id).attr('value').substring(0,2)) + ",";  // R
	colorStr += hexdec($("#" + id).attr('value').substring(2,4)) + ",";  // G
	colorStr += hexdec($("#" + id).attr('value').substring(4,6));		 // B
	
	return colorStr;
}

function hexdec(h) {
	// Hex to Decimal
	return parseInt(h,16);
}

// === document ready ===
$(function() {
	maxNumStripCmds = $("#maxNumStripCmds").val();
	//alert (maxNumStripCmds);
	
	// Init cbArr
	cbArr = [];
	for (var cmdPos = 0; cmdPos < maxNumStripCmds * 2; cmdPos++) {
		cbArr[cmdPos] = [];
		for(var cmdType = 0; cmdType < 4; cmdType++){
			cbArr[cmdPos][cmdType] = "" + cmdType + ",0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0";  // 32 bytes total
		}
	}
	
	// Then fill in cbArr with init values
	for (var cmdPos = 0; cmdPos < maxNumStripCmds * 2; cmdPos++) {
		updateCbArr(cmdPos);
	}
});
