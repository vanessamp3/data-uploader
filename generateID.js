//var siteIDs = ["", "01_", "02_", "03_", "04_", "05_", "06_", "07_", "08_"];
//var visitIDs = ["", "_v0", "_v2", "_v4", "_v6", "_v8"];
//var hfsIDs = ["", "_V", "_H", ""]

$(document).ready( function() {
        
        $('#site').change(function() {                
                var mySite = $('#site option:selected').val();
                //var siteID = siteIDs[mySite];  
                $('.site_output').html(mySite.concat("_"));
        });
        
        $('#visit').change(function() {
                var myVisit = $('#visit option:selected').val();
                //var visitID = visitIDs[myVisit];
                $('.visit_output').html("_".concat(myVisit));
        });
        
        $('#repeat').change(function() {
                var myRepeat = $('#repeat').is(':checked');
                if (myRepeat) {
                        $('.repeat_output').html("r");
                }
                else {
                        $('.repeat_output').html("");
                }
                
        });
        
        $('#group').change(function() {
                var myGroup = $('#group option:selected').val();
        });
        
        $('#subjectid').keyup(function() {
                var subjectID = $('#subjectid').val();
                var myGroup = $('#group option:selected').val();
                var subInit = subjectID.substr(0,1);
                var subNum = parseInt(subjectID.substr(1));
                
                if (myGroup=="pilot" && subInit != 'V') {
                        $('.sub_valid').html("Pilots should have 'V' prepended.");
                }
                else if ( (myGroup=="control" || myGroup=="prodrome") && subInit != 'S') {
                        $('.sub_valid').html("Subjects should have 'S' prepended.");
                }                
                else if (subInit != 'V' && subInit != 'S') {
                        $('.sub_valid').html("ID must have 'S' or 'V' prepended.");
                }
                else {
                        if (subNum<10) {
                                addZero = "000";
                        }
                        else if (subNum<100) {
                                addZero = "00";
                        }
                        else if (subNum<1000) {
                                addZero = "0";
                        }
                        else {
                                addZero = "";
                        }
                        
                        myID = subInit.concat(addZero,subNum);
                        
                        if (!isNaN(subNum)) {
                                $('.subject_output').html(myID);
                        }
                        else {
                                $('.subject_output').html(subInit);
                        }
                }
                
                
        }) .blur(function() {
                var subjectID = $('#subjectid').val();
                if (subjectID.length != 5) {
                        $('.sub_valid').html("ID must have 5 characters.");
                }
                else {
                        $('.sub_valid').html("");
                }
        });
        
        $('#hfs').change(function() {
                var myHFS = $('#hfs option:selected').val();
                //var hfsID = hfsIDs[myHFS];                
                $('.hfs_output').html(myHFS);
        });
        
});