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

function outColored5sColorSubmit() {
	// 0xBD => 189
	var colorStr = "";
	colorStr += "0x" + $("#outColored5sColor").attr('value').substring(0,2) + ",";   // R
	colorStr += "0x" + $("#outColored5sColor").attr('value').substring(2,4) + ",";   // G
	colorStr += "0x" + $("#outColored5sColor").attr('value').substring(4,6);		  // B

	$.get("sendpkt2ard.php", {
		packet: "189," + colorStr
	}, function (data) {
		$("#result").html(data);
	});
}

function inColored5sColorSubmit() {
	// 0xBE => 190
	var colorStr = "";
	colorStr += "0x" + $("#inColored5sColor").attr('value').substring(0,2) + ",";   // R
	colorStr += "0x" + $("#inColored5sColor").attr('value').substring(2,4) + ",";   // G
	colorStr += "0x" + $("#inColored5sColor").attr('value').substring(4,6);		  	// B

	$.get("sendpkt2ard.php", {
		packet: "190," + colorStr
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

function sendOneCmdSubmit(cmdPos) {
	var cmdBytesStr = getCmdBytes(cmdPos);
	//alert ("cmdBytes: " + cmdBytes);
	
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

/*
jQuery.fn.redraw = function() {
    return this.hide(0, function() {
        $(this).show();
    });
};
*/

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
		
		var animDelay = $("#" + cmdPosPrefix + "animDelay").val();
		cmdBytes += "" + ((animDelay & 0xFF00) >> 8) + ",";  // MSB
		cmdBytes += "" + (animDelay & 0x00FF)		 + ",";  // LSB
		var pauseAfter = $("#" + cmdPosPrefix + "pauseAfter").val();
		cmdBytes += "" + ((pauseAfter & 0xFF00) >> 8) + ",";  // MSB
		cmdBytes += "" + (pauseAfter & 0x00FF)		  + ",";  // LSB
		
		cmdBytes += getBoolBits(cmdPos) + ",";
		
		cmdBytes += $("#" + cmdPosPrefix + "numColorsInSeries").val() + ",";
		for (var i = 0; i < 7; i++) {
			cmdBytes += "0x" + $("#" + cmdPosPrefix + "colorSeriesArr" + i).attr('value').substring(0,2) + ",";  // R
			cmdBytes += "0x" + $("#" + cmdPosPrefix + "colorSeriesArr" + i).attr('value').substring(2,4) + ",";  // G
			cmdBytes += "0x" + $("#" + cmdPosPrefix + "colorSeriesArr" + i).attr('value').substring(4,6) + ",";  // B
		}
		cmdBytes = cmdBytes.substring(0, cmdBytes.length-1);  // Take off the last ","
		
		cmdBytes = addZerosToEnd(cmdBytes);
		
		break;
	case "2":  // Shift
		cmdBytes += $("#" + cmdPosPrefix + "startPixelNum").val() + ",";
		cmdBytes += $("#" + cmdPosPrefix + "endPixelNum").val() + ",";
		cmdBytes += $("#" + cmdPosPrefix + "numPixelsToSkip").val() + ",";
		cmdBytes += $("#" + cmdPosPrefix + "numIter").val() + ",";

		var animDelay = $("#" + cmdPosPrefix + "animDelay").val();
		cmdBytes += "" + ((animDelay & 0xFF00) >> 8) + ",";  // MSB
		cmdBytes += "" + (animDelay & 0x00FF) 		 + ",";  // LSB
		var pauseAfter = $("#" + cmdPosPrefix + "pauseAfter").val();
		cmdBytes += "" + ((pauseAfter & 0xFF00) >> 8) + ",";  // MSB
		cmdBytes += "" + (pauseAfter & 0x00FF)		  + ",";  // LSB
		
		cmdBytes += getBoolBits(cmdPos) + ",";
		
		cmdBytes = addZerosToEnd(cmdBytes);
		
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
			cmdBytes += "0x" + $("#" + cmdPosPrefix + "colorSeriesArr" + i).attr('value').substring(0,2) + ",";  // R
			cmdBytes += "0x" + $("#" + cmdPosPrefix + "colorSeriesArr" + i).attr('value').substring(2,4) + ",";  // G
			cmdBytes += "0x" + $("#" + cmdPosPrefix + "colorSeriesArr" + i).attr('value').substring(4,6) + ",";  // B
		}
		cmdBytes = cmdBytes.substring(0, cmdBytes.length-1);  // Take off the last ","
		
		cmdBytes = addZerosToEnd(cmdBytes);
		break;
		
	default:
		return "";
	}
	
	//alert("cmdBytes: " + cmdBytes);
	
	return cmdBytes;
}

function addZerosToEnd(cmdBytes) {
	var numCommas = (cmdBytes.match(/,/g) || []).length;
	while (numCommas < 31) {
		cmdBytes += ",0";
		numCommas++;
	}

	return cmdBytes;
}

function pad(str, max) {
	str = str.toString();
	return str.length < max ? pad("0" + str, max) : str;
}

function cmdTypeDDChanged(dd, cmdPos) {
	//alert ("cmdTypeDDChanged(), cmdPos: " + cmdPos);
	
	var cmdPosPrefix = getCmdPosPrefix(cmdPos);
	var cmdTypeStr = dd.value;
	
	//alert ("cmdTypeDDChanged(), cmdTypeStr: " + cmdTypeStr);

	if (cmdTypeStr == "none") {
		$("#" + cmdPosPrefix + "div").html("");
		return;
	}

	var cmdType = 0;	
	if (cmdTypeStr == "ssp") { cmdType = 0; }
	if (cmdTypeStr == "shift") { cmdType = 2; }
	if (cmdTypeStr == "flow") { cmdType = 3; }
	
	
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

function updateCbArr(cmdPos) {
	
	var cmdBytesStr = getCmdBytes(cmdPos);
	
	if (cmdBytesStr != "") {
		var cmdPosPrefix = getCmdPosPrefix(cmdPos);
		var cmdType = $("#" + cmdPosPrefix + "cmdType").val();
		cbArr[cmdPos][cmdType] = cmdBytesStr;

		//alert("updateCbArr(" + cmdPos + "), cmdType: " + cmdType + ", cmdBytesStr: " + cmdBytesStr);
	}
}

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

function initColored5sColorPicker(id) {
	var elem = $("#" + id);
	
	var changeFn = function(color) {
		elem.attr('value', color.toHexString().substring(1,7));
		$("#" + id + "Btn").click();
	}
	
	initFullColorPicker(elem, changeFn);
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
