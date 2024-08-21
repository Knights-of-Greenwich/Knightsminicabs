<?php
// This script was created by DBTECHNOSYSTEMS.com ----
// © DBTechnosystems.com http://www.dbtechnosystems.com
// You may use this script but please leave author details here
// DBTS Form Processor Extension version 2.7.5.6

ini_set('display_errors', 1);
$version = "2.7.5.6";
error_reporting(0);
if(session_id() == ""){
session_start();
}
if("Working Mode" == "Debug Mode"){
error_reporting(E_ALL);
}

$SESSION = $_SESSION['POST'];
if(is_array($SESSION)){
while (list ($key, $val) = each($SESSION)) {
// Stripslashes only if it is not an array, or it empties the array
if (!is_array($val)) {
$SESSION[$key] = stripslashes($val);
}
}
extract ($SESSION, EXTR_OVERWRITE);
}

if($_SERVER['REQUEST_METHOD'] == 'POST')
{

$error="";

require_once 'dbts_includes/dbts_functions.php';

// -------- RECEIVIMG CONFIG FILE VARIABLES-----------
$form_id = "form1";
$from_email = "info@knightsminicabs.com";
$nameto = "Webmaster";
$mailto = "info@knightsminicabs.com";
$cc_email = "";
$bcc_email = "";
$email_field = "email";
$charset = "UTF8";
$success_url = "success.html";
$error_url = "error.html";
$timezone = "Europe/Rome";
if(trim($timezone) != ""){
if (function_exists(date_default_timezone_set)) {
date_default_timezone_set($timezone);
}
else{
putenv ($timezone);
mktime(0,0,0,1,1,1970);
}
}
$time_format = "";
$date = date("l jS F Y, g:i A");
if(trim($time_format) != ""){
$date = date("");
}
$admin_mail = "Yes";
$autoresponder = "Yes";
$csvStore = "No";
$dbStore = "No";

$post_further = "No";
$post_further_to = "";

$copy_in_session = "No";
$copy_from_session = "No";
$clear_session = "Yes";
$afterprocessing = "Redirect";
$end_include = "";
$include_null_fields = "No";
$null_field_text = "No user input";
$excluded_fields = "";
$allow_urls = "Yes";

$captcha_rewrite = "Yes";
$captcha_error = "The entered code is wrong!";

$force_recaptcha = "No";
$privatekey = "";
$recaptcha_error_array['invalid-site-private-key'] = "We weren't able to verify the private key.";
$recaptcha_error_array['invalid-request-cookie'] = "The challenge parameter of the verify script was incorrect.";
$recaptcha_error_array['incorrect-captcha-sol'] = "The code you have entered in the captcha is wrong";
$recaptcha_error_array['recaptcha-not-reachable'] = "The captcha verification server is not accessible at the moment, so we can't verify your input. Please try again later.";
$recaptcha_failure_behavior = "Allow submission";

$use_reverse_captcha = "No";
$non_empty_field_name = "";
$non_empty_field_value = "";
$empty_field_name = "";
$reverse_captcha_error = "";
$reverse_captcha_behavior = "Display Success Page";

$enable_departments = "No";
$department_field = "department";

$departments = array();
$department_emails = array();


$banned_behavior = "Display Success Page";
$banned_error = "";

$banned_ipname = array();
$banned_ipaddress = array();


$bannedemail_names = array();
$bannedemail_emails = array();


$logo_image_url = "logo.jpg";
$logo_alt = "Visit our site!";
$logo_link = "http://www.knightsminicabs.com";
$logo_bgrd_clr = "#FFFFFF";
$logo_align = "center";

$table_width = "600";
$labelscolumn_width = "";
$cellspacing = "0";
$cellpadding = "1";
$border_width = "1";
$border_clr = "#000000";
$border_type = strtolower("Solid");
$bgrd_clr = "#FFFFFF";
$table_bgrd_clr = "#FFFFFF";
$header_bgrd_clr = "#FFFFFF";
$footer_bgrd_clr = "#FFFFFF";
$labels_text_clr = "#FF0000";
$labels_bkgd_clr = "#FFFFFF";
$values_text_clr = "#FF0000";
$values_bkgd_clr = "#FFFFFF";

$time_of_submission_label = "Time of Submission";
$ip_address_label = "IP Address";
$browser_label = "Browser";

$admin_mode = "HTML";
$subject = "Quote";
$namefrom = "";
$form_namefrom = "";
$htmlbodystart = 'There has been a new submission from our Website form. Here is the info submitted:';
$bodystart = "There has been a new submission from our Website form. Here is the info submitted:";
$htmlbodyend = 'Please review and act accordingly.';
$bodyend = "Please review and act accordingly.";
$custom_admin_mail_template = "";
$include_ip ="No";
$include_browser = "No";
$include_datetime = "No";

$auto_mode = "HTML";
$autorespondersubject = "Thank for your Quote";
$autoresponderfrom = "info@knightsminicabs.com";
$include_info = "Yes";
$autorespondernamefrom = "info@knightsminicabs.com";
$auto_form_nameto = "";
$htmlautoresponderstart= 'Hello, this email is to confirm that your info has been received.';
$autoresponderstart = "Hello, this email is to confirm that your info has been received.";
$htmlautoresponderend = 'Thank you for contacting us!';
$autoresponderend = "Thank you for contacting us!";
$custom_auto_mail_template = "";
$auto_include_datetime = "Yes";
$auto_attach = "";

$file_ext = strtolower("jpg, gif, png, bmp, mp3, pdf, html, htm");
$filesize = "1024";
$store_uploaded = "Yes";
$uploadfolder = "uploads";
$add_prefix = "Yes";
$attach_files = "No";
$max_attach_size = "10";

$double_quote_fields = "Yes";
$csvSeparator = "comma ,";
$csvFile = "fomdata.csv";

$db_host      = "localhost";
$db_name      = "database_name";
$db_user      = "username";
$db_password  = "password";
$db_table     = "table_name";
$create_table = "Yes";
$add_columns  = "Yes";

$mail_engine    = "mail() function";
$SMTPSecure = "tls";
$SMTPHost   = "send.one.com";
$SMTPPort   = "25";
$SMTPAuth   = "true";
$SMTPUser   = "info@knightsminicabs.com";
$SMTPPass   = "londonminicabs";

$start_of_script_include = '';
$before_formdata_include = '';
$after_formdata_include = '';
$after_filedata_include = '';
$before_error_include = '';
$before_admin_mail_include = '';
$before_auto_mail_include = '';
$end_of_script_include = '';
$end_include = '';

//  End of settings section
//  Process form
include 'dbts_includes/dbts_formprocessor.php';
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Knights Minicabs :  The Premier Taxi Company in London</title>
<meta name="description" content="Knights Minicabs provide airport transfers to or from Canary Wharf and Greenwich London. Free wifi and pay in car facilities in all of our 
Vehicles">
<meta name="keywords" content="Mini cab, Minicabs, Taxi, taxi london, Canary Wharf,mini cab London, minicab airport transfers, airport transfers, free wifi minicabs, pay in 
car minicabs. minicab quote, minicab eco, taxi service Canary Wharf, minicabs Canary Wharf, minicabs Greenwich, taxi service Greenwich">
<meta name="author" content="Taxi on the Web">
<meta name="generator" content="WYSIWYG Web Builder 9 - http://www.wysiwygwebbuilder.com">
<link href="km.ico" rel="shortcut icon">
<style type="text/css">
div#container
{
   width: 1200px;
   position: relative;
   margin: 0 auto 0 auto;
   text-align: left;
}
body
{
   background-color: #FFFFFF;
   background-image: url(images/stripe5.png);
   background-repeat: repeat-x;
   color: #000000;
   font-family: Arial;
   font-size: 13px;
   margin: 0;
   text-align: center;
}
</style>
<style type="text/css">
a
{
   color: #0000FF;
   text-decoration: underline;
}
a:visited
{
   color: #800080;
}
a:active
{
   color: #FF0000;
}
a:hover
{
   color: #0000FF;
   text-decoration: underline;
}
</style>
<style type="text/css">
#Image2
{
   border: 0px #000000 solid;
}
#wb_Text3 
{
   background-color: transparent;
   border: 0px #000000 solid;
   padding: 0;
   text-align: center;
}
#wb_Text3 div
{
   text-align: center;
}
#wb_Text2 
{
   background-color: transparent;
   border: 0px #000000 solid;
   padding: 0;
   text-align: right;
}
#wb_Text2 div
{
   text-align: right;
}
#Image8
{
   border: 0px #000000 solid;
}
#Image3
{
   border: 0px #000000 solid;
}
#Image1
{
   border-width: 0;
}
#TabMenu1
{
   text-align: left;
   float: left;
   margin: 0;
   width: 100%;
   font-family: Verdana;
   font-size: 13px;
   font-weight: normal;
   list-style-type: none;
   padding: 15px 0px 4px 10px;
   overflow: hidden;
}
#TabMenu1 li
{
   float: left;
}
#TabMenu1 li a.active, #TabMenu1 li a:hover.active
{
   background-color: #FFFFFF;
   color: #094775;
   position: relative;
   font-weight: normal;
   text-decoration: none;
   z-index: 2;
}
#TabMenu1 li a
{
   padding: 5px 14px 8px 14px;
   border: 1px solid #0B5E99;
   border-top-left-radius: 5px;
   border-top-right-radius: 5px;
   background-color: #AAD8F9;
   color: #0B5E99;
   margin-right: 3px;
   text-decoration: none;
   border-bottom: none;
   position: relative;
   top: 0;
   -webkit-transition: 200ms all ease;
   -moz-transition: 200ms all ease;
   -ms-transition: 200ms all ease;
   transition: 200ms all ease;
}
#TabMenu1 li a:hover
{
   background: #C0C0C0;
   color: #0B5E99;
   font-weight: normal;
   text-decoration: none;
   top: -4px;
}
</style>
<meta name="form_processor" content="DBTS WWB Form Processor v. 2.7.5.7">
<!-- Insert Google Analystics code here -->
</head>
<body>
<div id="container">
<div id="wb_Shape2" style="position:absolute;left:1px;top:165px;width:1200px;height:776px;z-index:0;">
<img src="images/img0006.png" id="Shape2" alt="" style="border-width:0;width:1200px;height:776px;"></div>
<div id="wb_Image2" style="position:absolute;left:178px;top:8px;width:457px;height:65px;z-index:1;">
<img src="images/knights3.png" id="Image2" alt="" style="width:457px;height:65px;"></div>
<div id="wb_Text3" style="position:absolute;left:231px;top:66px;width:341px;height:25px;text-align:center;z-index:2;">
<span style="color:#0B5E99;font-family:'Franklin Gothic Medium';font-size:20px;">The Best Value MiniCabs in London</span></div>
<div id="wb_Text2" style="position:absolute;left:902px;top:70px;width:251px;height:20px;text-align:right;z-index:3;">
<span style="color:#0B5E99;font-family:'Malgun Gothic';font-size:15px;"><strong>info@knightsminicabs.com.</strong></span></div>
<div id="wb_Image8" style="position:absolute;left:698px;top:90px;width:500px;height:76px;z-index:4;">
<img src="images/london_skyline_blue.png" id="Image8" alt="" style="width:500px;height:76px;"></div>
<div id="wb_Extension1" style="position:absolute;left:468px;top:443px;width:120px;height:72px;z-index:5;">
</div>
<div id="wb_Image3" style="position:absolute;left:836px;top:16px;width:338px;height:47px;z-index:6;">
<img src="images/knights3.png" id="Image3" alt="" style="width:338px;height:47px;"></div>
<div id="wb_Image1" style="position:absolute;left:14px;top:1px;width:149px;height:180px;z-index:7;">
<img src="images/img0007.png" id="Image1" alt="Knights Minicabs The Best Value MiniCabs in London" title="Knights Minicabs The Best Value MiniCabs in London" style="width:149px;height:180px;"></div>
<div id="wb_TabMenu1" style="position:absolute;left:144px;top:130px;width:543px;height:38px;z-index:8;overflow:hidden;">
<ul id="TabMenu1">
<li><a href="./index.html" title="Knights MiniCabs The Best Value MiniCabs in London">Home</a></li>
<li><a href="./quote.html" title="Knights MiniCabs The Best Value MiniCabs in London">Quote</a></li>
<li><a href="./contact.html" title="Knights MiniCabs The Best Value MiniCabs in London">Contact Us</a></li>
<li><a href="./services.html" title="Knights MiniCabs The Best Value MiniCabs in London">Services</a></li>
<li><a href="./payment.html">Payment</a></li>
<li><a href="./recruit.html" title="Knight Minicabs Recruitment">Work for Us</a></li>
</ul>
</div>
</div>
</body>
</html>