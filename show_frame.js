$(document).ready( function() {  

    var percent = $('.percent');
    var bar = $('.bar');
    
    var success = $('.success');
    var filename = $('.filename');
    var behfound = $('.behfound');
    var numfiles = $('.numfiles');
    var numlogs = $('.numlogs');
    var db = $('.db');
    var error = $('.error');
    
    $('form').ajaxForm({
        
        // set data type json
        dataType: 'json',
        
        // reset before submitting
        beforeSend: function() {
            success.fadeOut();
            filename.fadeOut();
            behfound.fadeOut();
            numfiles.fadeOut();
            numlogs.fadeOut();
            db.fadeOut();
            error.fadeOut();
            bar.width('0%');
            percent.html('0%');
        },
        
        // progress bar call back
        uploadProgress: function(event, position, total, percentComplete) {
            var pVel = percentComplete + '%';
            bar.width(pVel);
            percent.html(pVel);
        },
        
        // complete call back
        complete: function(data) {
            success.html(data.responseJSON.success).fadeIn();
            filename.html(data.responseJSON.filename).fadeIn();
            behfound.html(data.responseJSON.behfound).fadeIn();
            numfiles.html(data.responseJSON.numfiles).fadeIn();
            numlogs.html(data.responseJSON.numlogs).fadeIn();
            db.html(data.responseJSON.db).fadeIn();
            error.html(data.responseJSON.error).fadeIn();
        }
        
        
    });
    
});