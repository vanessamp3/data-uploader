# data-uploader

Vanessa Palzes
2/24/2015

A web-based uploader to transfer information and data via a form. Utilizes various validation techniques to check for discrepancies.

I built this tool for use at my work for transferring data across study sites. It was necessary to streamline the data uploads with consistent naming conventions. The form is used to populate expected filenames. If the data and form passes all of the checks, then the data is transferred to the proper folder on the web server.

The code can easily be tailored for other uses. I have altered/hidden some of the code to preserve security for my live project.

The main page is upload.php, which is where the majority of the HTML code is for the web form. It imports JavaScript scripts (generateID.js, show_frame.js, and validateForm.js) for use in generating the expected file names, showing the upload progress bar, and validating the information in the form, respectively. When the form is submitted, uploader.php will be run to process the contents of the uploaded file, check for discrepancies further, transfer the data, and send data to the MySQL database.

Written in HTML, PHP, JavaScript, jQuery, MySQL, and CSS.
