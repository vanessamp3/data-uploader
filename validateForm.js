function validateForm() {
    
    // Main form
    var site = $('#site').val();
    var group = $('#group').val();
    var subjectid = $('#subjectid').val();
    var hfs = $('#hfs').val();
    var mmn_runs = $('#mmn_runs').val();
    var visitdate = $('#visitdate').val();
    var visit = $('#visit').val();
    var repeat = $('#repeat').is(':checked');
    
    // Files
    var hv1 = $('#hv1').is(':checked');
    var hv2 = $('#hv2').is(':checked');
    var hv3 = $('#hv3').is(':checked');
    var hv4 = $('#hv4').is(':checked');
    var hfsbdf = $('#hfsbdf').is(':checked');
    var mmn = $('#mmn').is(':checked');
    var numFiles = hv1 + hv2 + hv3 + hv4 + hfsbdf + mmn;
    
    // Repeat visit?
    if (repeat) {
	var repeat_value = "r";
    } else {
	var repeat_value = "";
    }
    
    // Incomplete?
    var incomplete = $('#incomplete').is(':checked');
    
    // Upload file
    var filepath = $('#file').val();
    
    var uploadName = site.concat('_', subjectid, '_', visit, repeat_value);
    
    return validation(site,group,subjectid,visitdate,visit,hfs,hfsbdf,mmn_runs,mmn,numFiles,incomplete,filepath,uploadName)
}

function validation(site,group,subjectid,visitdate,visit,hfs,hfsbdf,mmn_runs,mmn,numFiles,incomplete,filepath,uploadName) {

    var subInit = subjectid.substr(0,1);
    var subNum = subjectid.substr(1);
    
    if (site == "") {
	alert("Site is required.");
	return false;
    }
    else if (group== "") {
	alert("Subject Group is required.");
	return false;
    }
    else if (subjectid == null || subjectid == "") {
	alert("Subject ID is required.");
	return false;
    }    
    else if ( (hfs == "") && hfsbdf) {
	alert("Must include HFS type if this was a complete session.");
	return false;
    }
    else if (visitdate == "") {
	alert("Date of Visit is required.");
	return false;
    }
    else if (visit == "") {
	alert("Visit is required.");
	return false;
    }
    else if (mmn_runs == "" && mmn) {
	alert("Number of MMN runs is required, otherwise uncheck the MMN.bdf box if you are not uploading it.")
	return false;
    }
    else if (mmn_runs != "" && !mmn) {
	alert("Do not include number of MMN runs if you are not uploading a MMN bdf.");
	return false;
    }
    else if ( ((group=="control" || group=="prodrome") && subInit!="S") || (group=="pilot" && subInit!="V") ) {
	alert("Subject ID and Subject Group mismatch.");
	return false;
    }
    else if ( group=="pilot" && visit!="pilot") {
	alert("For pilot subjects, must indicate this was a pilot in Visit.");
	return false;
    }
    else if ( (incomplete == false) && (numFiles<6) ) {
	alert("Must check Incomplete box if this session was incomplete.");
	return false;
    }
    else if ( (incomplete == true) && (numFiles==6) ) {
	alert("Are you sure this is an incomplete session? You checked off on all of the BDF files.");
	return false;
    }
    else if (filepath == null || filepath == "") {
	alert("File is required.");
	return false;
    }
    else if (filepath != null || filepath != "") {
	// Parse file
	var file = basename(filepath);
	var fileparts=file.split(".");
	if (fileparts.length==2) {
	    var fileID=fileparts[0];
	    var fileExt=fileparts[1];
	    if (fileExt != "zip") {
		alert("File is not a '.zip'");
		return false;
	    } else {
		if (fileID != uploadName) {
		    alert("Subject ID and name of '.zip' file must match.");
		    return false;
		}
		return true;
	    }
	} else {
	    alert("File does not have an extension.");
	    return false;
	}
    } else {
	return true;
    }
}

function basename(path) {
    return path.split(/[\\/]/).pop();
}