<?php

header('Content-type: application/json');
    
    // Site Order
    $sites = array("", "UCLA", "Emory", "Harvard", "Hillside", "UNC", "UCSD", "Calgary", "Yale", "UCSF");
    
    // Read in variables from the form
    // SITE
    if (isset($_POST["site"])) {
        $site = $_POST["site"];
        $siteNum = intval($site);
        $siteName = $sites[$siteNum];
    } else {
        $site = "";
    }
    
    // GROUP
    if (isset($_POST["group"])) {
        $group = $_POST["group"];
    } else {
        $group = "";
    }
    
    // SUBJECTID
    if (isset($_POST["subjectid"])) {
        $subjectid = $_POST["subjectid"];
    } else {
        $subjectid = "";
    }
    
    // HFS
    if (isset($_POST["hfs"])) {
        $hfs = $_POST["hfs"];
    } else {
        $hfs = "";
    }
    
    // MMN RUNS
    if (isset($_POST["mmn_runs"])) {
        $mmn_runs = $_POST["mmn_runs"];
        $mmn_runsNum = intval($mmn_runs);
    } else {
        $mmn_runs = "";
    }
    
    // VISIT DATE
    if (isset($_POST["visitdate"])) {
        $visitdate = $_POST["visitdate"];
    } else {
        $visitdate = "";
    }
    
    // VISIT
    if (isset($_POST["visit"])) {
        $visit = $_POST["visit"];
    } else {
        $visit = "";
    }
    
    // REPEAT VISIT
    if (isset($_POST["repeat"])) {
        $repeatVal = $_POST["repeat"];
        if ($repeatVal=="on") {
            $repeat = 1;
        }
    } else {
        $repeat = 0;
    }
    
    // FILE CHECKBOXES
    // HV1
    if (isset($_POST["hv1"])) {
        $hv1 = $_POST["hv1"];
    } else {
        $hv1 = 0;
    }
    // HV2
    if (isset($_POST["hv2"])) {
        $hv2 = $_POST["hv2"];
    } else {
        $hv2 = 0;
    }
    // HV3
    if (isset($_POST["hv3"])) {
        $hv3 = $_POST["hv3"];
    } else {
        $hv3 = 0;
    }
    // HV4
    if (isset($_POST["hv4"])) {
        $hv4 = $_POST["hv4"];
    } else {
        $hv4 = 0;
    }
    // HFSBDF
    if (isset($_POST["hfsbdf"])) {
        $hfsbdf = $_POST["hfsbdf"];
    } else {
        $hfsbdf = 0;
    }
    // MMN
    if (isset($_POST["mmn"])) {
        $mmn = $_POST["mmn"];
    } else {
        $mmn = 0;
    }
    
    // INCOMPLETE
    if (isset($_POST["incomplete"])) {
        $incomplete = $_POST["incomplete"];
    } else {
        $incomplete = 0;
    }
    
    // FILE
    if ($_FILES["file"]["error"] > 0) {
        $fileError = $_FILES["file"]["error"];
        echo "File Error: " . $_FILES["file"]["error"] . "<br>";
    }    
    
    $filename = $_FILES["file"]["name"];
    $tempfile = $_FILES["file"]["tmp_name"];
    $filesize = filesize($tempfile);
    
    // Where the file is going to be placed 
    $targetPath = $siteName . "/";
    $targetPath = $targetPath . $filename;
    
    // Print out expectations
    function printFileNames($error, $list) {
        for ( $i = 0; $i < count($list); $i++) {
            $error = $error . $list[$i] . "<br>";
        }
        $error = $error . "<br>";
        return $error;
    }

    if ($repeat) {
        $base = $site . "_" . $subjectid . "_" . $visit . "r";
    } else {
        $base = $site . "_" . $subjectid . "_" . $visit;
    }    
    
    // Set expectations
    $fileNames = array($base . "_hv1.bdf", $base . "_hv2.bdf", $base . "_hv3.bdf",
                       $base . "_hv4.bdf", $base . "_" . $hfs . "HFS.bdf", $base . "_comMMN.bdf");
    $numFiles = count($fileNames);
    $acceptNames = array($base . "_hv1.bdf", $base . "/" . $base . "_hv1.bdf",
                         $base . "_hv2.bdf", $base . "/" . $base . "_hv2.bdf",
                         $base . "_hv3.bdf", $base . "/" . $base . "_hv3.bdf",
                         $base . "_hv4.bdf", $base . "/" . $base . "_hv4.bdf",
                         $base . "_" . $hfs . "HFS.bdf", $base . "/" . $base . "_" . $hfs . "HFS.bdf",
                         $base . "_comMMN.bdf", $base . "/" . $base . "_comMMN.bdf",);   
    $sizeLimit = 800000000;
    
    // Read contents of zip file
    $za = new ZipArchive();
    $za->open($tempfile);
    //echo $tempfile;
        
    // Get rid of any hidden files while creating array of file names included
    // Check for beh folder and logfiles along the way
    $uploadFiles = array();
    $logFiles = array();
    $behFound = false;
    for ( $i = 0; $i < $za->numFiles; $i++ ){
        $stat = $za->statIndex($i);
        $curFile = $stat["name"];
        
        if ($curFile == "beh/" || $curFile == $base . "/beh/") {
            $behFound = true;
        }
        else if ( substr($curFile,0,4) == "beh/" || substr($curFile,0,strlen($base)+5) == $base . "/beh/" ) {
            $curFileParts = explode("/", $curFile);
            $curFileParts = explode(".", end($curFileParts));
            $curFileExt = end($curFileParts);
            if ($curFile[0] != "_" && $curFileExt == "log") {
                $thisFile = $curFileParts[0] . "." . $curFileExt;
                array_push($logFiles,$thisFile);
            }
        }        
        else {        
            $curFileParts = explode(".", $curFile);
            $curFileExt = end($curFileParts);
            if ($curFile[0] != "_" && $curFileExt == "bdf") {
                if (strpos($curFile,'/')) {
                    $curFileParts = explode("/", $curFile);
                    $curFile = $curFileParts[1];
                }
                array_push($uploadFiles,$curFile);
            }
        }
    }
    
    $numActualFiles = count($uploadFiles);
    $numLogFiles = count($logFiles);
    
    $error = "";
    $behFolder = "";
    
    if ( $filesize > $sizeLimit  || $filesize == "") {
        $error = $error . "File size too big (>800MB). Server will not accept that!<br>";
    } 
    
    // Check number of files
    if ( $numActualFiles < $numFiles && !$incomplete) {
        $error = $error . "Not enough files. If this is an incomplete session, please indicate on the form.<br>";
    }
    else if ( $numActualFiles > $numFiles ) {
        $error = $error. "Too many files. There should only be " . $numFiles . ":<br>";
        $error = printFileNames($error,$fileNames);
    }
    
    // Check for logfiles based on which BDF files were selected in upload.php
    $checkLogs = array();
    $mmnLogs = array("comMMNa", "comMMNb", "comMMNc", "comMMNd", "comMMNe");
    if ( $hv1 ) {
        array_push($checkLogs, "r3hvAm");
    }
    if ( $hv2 ) {
        array_push($checkLogs, "r3hvBm");
    }
    if ( $hv3 ) {
        array_push($checkLogs, "r3hvCm");
    }
    if ( $hv4 ) {
        array_push($checkLogs, "r3hvDm");
    }
    if ( $hfsbdf ) {
        array_push($checkLogs, "m" . $hfs . "HFS");
    }
    if ( $mmn ) {
        for ( $m = 0; $m < $mmn_runs; $m++) {
            array_push($checkLogs, $mmnLogs[$m]);
        }
        // All sessions with MMN should also have an audiometer
        array_push($checkLogs, "audiometer");
    }
    $missingLog = array_fill(0,count($checkLogs),1);
    
    for ( $i = 0; $i < count($checkLogs); $i++ ) {
        $curCheckLog = $checkLogs[$i];
        
        for ( $j = 0; $j < $numLogFiles; $j++ ) {
            $curLogFile = $logFiles[$j];
            $pos = strpos($curLogFile,$curCheckLog);
            if ($pos !== false) {
                $missingLog[$i] = 0;
                break;
            }
        }        
    }
    
    // Print out missing logfiles
    for ( $i = 0; $i < count($missingLog); $i++) {
        if ( $missingLog[$i] ) {
            $error = $error . "Missing logfile for: " . $checkLogs[$i] . "<br>";
        }
    }
    
    // Check for erroneous naming
    for ( $i = 0; $i < $numActualFiles; $i++ ) {
        $curFile = $uploadFiles[$i];
        $curFileParts = explode("/",$curFile);
        $curFile = end($curFileParts);
        if ( !in_array($curFile, $fileNames) ) {
            $error = $error . $curFile . " is erroneously named. Expecting these names:<br>";
            $error = printFileNames($error,$fileNames);
        }
    }
    
    if ( !$incomplete) {
        for ( $i = 0; $i < $numFiles; $i++ ) {
            $curFile = $fileNames[$i];
            if ( !in_array($curFile, $uploadFiles) ) {
                if ( !in_array($subjectid . "/" . $curFile, $uploadFiles) ) {
                    $error = $error . $curFile . " not found.<br>";
            }
                }
        }
    }
    
    for ( $i = 0; $i < $numLogFiles; $i++ ) {
        $curFile = $logFiles[$i];
        $curFileParts = explode("-",$curFile);
        if (count($curFileParts)>1) {
            if ($curFileParts[0] != $base) {
                $error = $error . $curFile . " incorrectly named, expecting " . $base . "-" . $curFileParts[1] . "<br>";
            }
        }
    }    
    
    if (!$behFound) {
        $error = $error . "Missing 'beh' folder.<br>";
        $behFolder = "no";
    } else {
        $behFolder = "yes";
    }
    
    // Initialize success message
    $success = "Upload was not successful.";
    $databaseEntered = "no";
    
    // If no errors so far, then check the database for previous entries
    if (empty($error)) {
        
        // Start connection to mySQL
        require '/home2/bieeglne/www/NAPLSbdf/napls3db.inc';
	if (!($connection = @ mysql_connect($hostname,$username,$password))) {
		die("Could not connect to database.");
	}
        // select napls3 database on bieegl.net
        mysql_select_db("bieeglne_napls3",$connection);
        
        
        // Query to see if this subject has been uploaded before
        $query = "SELECT `site`, `subjid`, `visit`, `revisit` FROM uploads WHERE `site`={$siteNum} AND `subjid`='{$subjectid}' AND `visit`='{$visit}' ORDER BY visit";
        
        // Send query to database
        $result = @ mysql_query($query,$connection);
        
        // Check results of query
	if (!$result) {
            $error = $error . "Invalid MySQL query: " . mysql_error() . "<br>";
            $error .= "Whole query: " . $query . "<br>";
	} else {
            
            // Get the query results
            while ($row = mysql_fetch_assoc($result)) {
                // See if this subject has already been uploaded
                if ($row['revisit']==0 && $repeat==0) {
                    $error = $error . "This session has already been uploaded. Are you sure this is not a repeat visit?<br>";
                }
                // See if this is a second repeat visit (probably rare, but we will just add another entry in the database)
                if ($row['revisit']==1 && $repeat==1) {
                    $repeat=2;
                    $filenameParts = explode(".", $targetPath);
                    $targetPath = $filenameParts[0] . "2" . $filenameParts[1];
                    // ADD SOMETHING HERE TO ALERT ME IF THERE IS A DOUBLE REVISIT!
                }
                // See if we already have a repeat entry in here
                if ($row['revisit']==2 && $repeat==2) {
                    $error = $error. "There are already 2 repeat sessions added for this subject/visit.<br>";
                }
            }            
        }
    }
    
    // If no errors so far, then add the session to the database
    if (empty($error)) {
        
        // Get today's date
        $uploaddate = date('Y-m-d');
        
        // Reformat visitdate
        $visitdate = date("Y-m-d", strtotime($visitdate));
        
        // Get complete
        if ($incomplete==1) {
            $complete=0;
        } else {
            $complete=1;
        }
        
        // Add new session to the database
        $insert = "INSERT INTO uploads (`site`, `grp`, `subjid`, `visitdate`, `visit`, `revisit`, `hv1`, `hv2`, `hv3`, `hv4`, `hfs`, `mmn`, `complete`, `uploaddate`)";
        $insert = $insert . " VALUES ({$siteNum}, '{$group}', '{$subjectid}', '{$visitdate}', '{$visit}', {$repeat}, {$hv1}, {$hv2}, {$hv3}, {$hv4}, '{$hfs}', {$mmn_runsNum}, {$complete}, '{$uploaddate}');";
        
        // Insert row into database
        $addresult = @ mysql_query($insert,$connection);
        
        // Check result of add
        if (!$addresult) {
            $error = "Could not enter session into database: ". mysql_error() . "<br>";
            $databaseEntered = "no";
        } else {
            $databaseEntered = "yes";
        }
    }
    
    // Move the file if there are no database side errors
    if (empty($error)) {        
        $moveresult = move_uploaded_file($tempfile, $targetPath);
        
        // Check to see if the file was successfully moved from its temp location
        if ($moveresult) {
            $success = "Successful Upload!";
        } else {
            $error = "Error moving the file from its temp location on the server.<br>";
        }
    }
    
    echo json_encode(array('success' => $success, 'filename' => $filename,
                           'behfound' => $behFolder,
                           'numfiles' => $numActualFiles, 'numlogs' => $numLogFiles,
                           'db' => $databaseEntered, 'error' => $error,));
    

?>