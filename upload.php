<!DOCTYPE html>
	
<html>

<head>
	<title>NAPLS Upload</title>
	<link rel="stylesheet" type="text/css" href="ra-style.css">
	<link rel="stylesheet" type="text/css" href="jquery-ui.css">
	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
	
	<script src="validateForm.js" type="text/javascript"></script>
	<script src="generateID.js" type="text/javascript"></script>
	<script src="show_frame.js" type="text/javascript"></script>
	
	<script type="text/javascript" src="jquery-ui.js"></script>
	<script type="text/javascript" src="jquery.min.js"></script>
	<script type="text/javascript" src="jquery.form.min.js"></script>
	
	<script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
	
</head>

<body>
<div id="main">


	<h2> Upload NAPLS3 EEG Data </h2>
	<p>Please follow the guide below. The browsers supported currently are Chrome, Firefox, and Safari.</p>
	<p>Subject ID should be 5 characters, with an "S" prefix for subjects or "V" for volunteer pilots, followed
	by a 4 digit number with padded zeros if necessary. For example, for subject 1, their ID would be S0001.</p>
	<p>The form will populate the expected file names for you.</p>

	<div>	
		<form class="form" name="form" id="form" method="post" enctype="multipart/form-data" onsubmit="return validateForm()" action="uploader.php">
			
			<fieldset>
			<h3>Step 1. Enter Session Information</h3>
				
				<div class="form-group">
					<label for="site">Site</label>
					<select id="site" name="site">
						<option value = ""> </option>
						<option value = "01">UCLA</option>
						<option value = "02">Emory</option>
						<option value = "03">Harvard</option>
						<option value = "04">Hillside</option>
						<option value = "05">UNC</option>
						<option value = "06">UCSD</option>
						<option value = "07">Calgary</option>
						<option value = "08">Yale</option>
						<option value = "09">UCSF</option>
					</select>
				</div>
				
				<div class="form-group">
					<label for="group">Subject Group</label>
					<select id="group" name="group">
						<option value=""> </option>
						<option value="control">Healthy Control</option>
						<option value="prodrome">Prodrome</option>
						<option value="pilot">Pilot</option>
					</select>
				</div>
				
				<div class="form-group">
					<label for="subjectid">Subject ID</label>
					<input id="subjectid" type="text" name="subjectid">&nbsp;&nbsp;<div class="sub_valid"></div>
				</div>
				
				<div class="form-group">
					<label for="hfs">HFS</label>
					<select id="hfs" name="hfs">
						<option value=""> </option>
						<option value="V">Vertical</option>
						<option value="H">Horizontal</option>
					</select>
				</div>
				
				<div class="form-group">
					<label for="mmn_runs">Number of MMN runs</label>
					<select id="mmn_runs" name="mmn_runs">
						<option value=""> </option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
					</select>
				</div>
				
				
				<div class="form-group">
					<label for="visitdate">Date of Visit</label>
					<input type="date" id="visitdate" name="visitdate">
				</div>
				
				<script>
					(function() {
						var elem = document.createElement('input');
						elem.setAttribute('type', 'date');
						
						if (elem.type==='text') {
							$('#visitdate').datepicker();
						}
					})();
				</script>
				
				<div class="form-group">
					<label for="visit">Visit</label>
					<select id="visit" name="visit">
						<option value=""> </option>
						<option value="v0">v0</option>
						<option value="v2">v2</option>
						<option value="v4">v4</option>
						<option value="v6">v6</option>
						<option value="v8">v8</option>
						<option value="c">conversion</option>
						<option value="pilot">pilot</option>
					</select>
				</div>
				
				<div class="form-group">
					<label for="repeat">Repeat Visit?</label>
					<input id="repeat" type="checkbox" name="repeat">
				</div>
				
				<br>
					
		<h3>Step 2. Validate File Names</h3>
		<p>Please create a zip file named: <div class="site_output"></div><div class="subject_output"></div><div class="visit_output"></div><div class="repeat_output"></div>.zip</p>
		<p>The zipped file should contain the following BDF files appropriately named.
		Please include all Presentation logfiles, even from incomplete runs, in a folder named "beh."</p>
		<p>Check off all of the BDF files that you will be uploading in this zip file.</p>
		
				<div class="form-group">
					<input id="hv1" type="checkbox" name="hv1" value=1>
					<label for="hv1"><div class="site_output"></div><div class="subject_output"></div><div class="visit_output"></div><div class="repeat_output"></div>_hv1.bdf</label>
				</div>
				<div class="form-group">
					<input id="hv2" type="checkbox" name="hv2" value=1>
					<label for="hv2"><div class="site_output"></div><div class="subject_output"></div><div class="visit_output"></div><div class="repeat_output"></div>_hv2.bdf</label>
				</div>
				<div class="form-group">
					<input id="hv3" type="checkbox" name="hv3" value=1>
					<label for="hv3"><div class="site_output"></div><div class="subject_output"></div><div class="visit_output"></div><div class="repeat_output"></div>_hv3.bdf</label>
				</div>
				<div class="form-group">
					<input id="hv4" type="checkbox" name="hv4" value=1>
					<label for="hv4"><div class="site_output"></div><div class="subject_output"></div><div class="visit_output"></div><div class="repeat_output"></div>_hv4.bdf</label>
				</div>
				<div class="form-group">
					<input id="hfsbdf" type="checkbox" name="hfsbdf" value=1>
					<label for="hfsbdf"><div class="site_output"></div><div class="subject_output"></div><div class="visit_output"></div><div class="repeat_output"></div>_<div class="hfs_output"></div>HFS.bdf</label>	
				</div>
				<div class="form-group">
					<input id="mmn" type="checkbox" name="mmn" value=1>
					<label for="mmn"><div class="site_output"></div><div class="subject_output"></div><div class="visit_output"></div><div class="repeat_output"></div>_comMMN.bdf</label>
				</div>
				
		<p>If you are missing any of the BDF files listed above, please confirm by checking the box.</p>
		
				<div class="form-group">
					<input id="incomplete" type="checkbox" name="incomplete" value=1>
					<label for="incomplete">Incomplete Session?</label>
				</div>
		<br>
			
		<h3>Step 3. Upload</h3>
		
				<p>You will not be able to upload unless you fill out this form correctly. Please read the error messages and correct any discrepancies. Thank you.</p><br>
					
				<div class="form-group2">
					<input type="hidden" name="MAX_FILE_SIZE" value="800000000" />
					<input name="file" type="file" id="file">
				</div>
				<br>
					
				<div class="form-group2">
					<input class="button" type="submit" name="submit" value="Submit"><br>
				</div>				
				
				<p>Upload Status:</p>
				<div class="progress">
				<div class="bar"></div>
				<div class="percent">0%</div>
				</div>
				
				<p>Response:</p>
				
				<p><div class="success"></div><br><br>
				
				Filename: <div class="filename"></div><br>
				'beh' Folder: <div class="behfound"></div><br>
				Number of BDF files: <div class="numfiles"></div><br>
				Number of logfiles: <div class="numlogs"></div><br>
				Entered in database: <div class="db"></div><br><br>
				
				Error: <div class="error"></div>
				</p>
								
			</fieldset>
		
		</form>
		
	</div>

</div> <!-- /main div -->
</body>
</html>