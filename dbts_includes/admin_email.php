<?php
// ------------Building the mail message----------------------
$HTMLmessage = '
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Form Submission</title>
<meta name="AUTHOR" content="DBTechnosystems http://www.dbtechnosystems.com ">
<meta name="GENERATOR" content="DBTechnosystems WB6 Form Processor Extension">
<style type="text/css">
div#container
{
   width: ' . $table_width . 'px;
   position: relative;
   margin-top: 30px;
   margin-left: auto;
   margin-right: auto;
   text-align: left;
}
body
{
   text-align: center;
   margin: 0;
}
</style>
</head>
<body bgcolor="' . $bgrd_clr . '" text="#000000">
<div id="container">
';

$HTMLmessage .= '<table width="' . $table_width . '"  cellpadding="' . $cellpadding . '" cellspacing="' . $cellspacing . '" bgcolor="' . $table_bgrd_clr . '" style="border: ' . $border_width . 'px ' . $border_clr . ' ' . $border_type . '">
';
if ($logo_image_url != "") {
    $HTMLmessage .= '<tr>
<td align="' . $logo_align . '" valign="' . $logo_valign . '" colspan="2" style="border: ' . $border_width . 'px ' . $border_clr . ' ' . $border_type . '" bgcolor="' . $logo_bgrd_clr . '"  height="30">' . $image_code . '</td>
</tr>
';
}
if ($htmlbodystart != "") {
    $HTMLmessage .= '<tr>
<td align="center" colspan="2" valign="middle" style="border: ' . $border_width . 'px ' . $border_clr . ' ' . $border_type . '" bgcolor="' . $header_bgrd_clr . '" height="30">';
    $HTMLmessage .= stripslashes($htmlbodystart);
    $HTMLmessage .= '</td>
</tr>
';
}else if (trim($bodystart) != "") {
    $HTMLmessage .= '<tr>
<td align="center" colspan="2" valign="middle" style="border: ' . $border_width . 'px ' . $border_clr . ' ' . $border_type . '" bgcolor="' . $header_bgrd_clr . '" height="30"><font style="font-size:13px" color="' . $header_text_clr . '" face="Arial"><B>';
    $HTMLmessage .= str_replace ("\r\n", "<br>", stripslashes($bodystart));
    $HTMLmessage .= '</font></td>
</tr>
';
}

for ($i = 0; $i < (count($fieldname) - count($upload_Name)); $i++) {
    if (!in_array($fieldname[$i], $excludedfields)) {
        $HTMLmessage1 = '<tr>
<td align="left" valign="middle" width="' . $labelscolumn_width . '" style="border: ' . $border_width . 'px ' . $border_clr . ' ' . $border_type . '" bgcolor="' . $labels_bkgd_clr . '" height="24"><font style="font-size:13px" color="' . $labels_text_clr . '" face="Arial">';
        $fieldvalue_i = trim($fieldvalue[$i]);
        if (trim($fieldvalue_i) == "") {
            $fieldvalue_i = $null_field_text;
        }
        $HTMLmessage1 .= $fieldname[$i];

        $HTMLmessage1 .= '</font></td>
<td align="left" valign="middle" style="border: ' . $border_width . 'px ' . $border_clr . ' ' . $border_type . '" bgcolor="' . $values_bkgd_clr . '"  height="24"><font style="font-size:13px" color="' . $values_text_clr . '" face="Arial">';
        $HTMLmessage1 .= str_replace ("\r\n", "<br>", stripslashes($fieldvalue_i));
        $HTMLmessage1 .= '</font></td>
</tr>
';
        if ($include_null_fields == "Yes" or trim($fieldvalue[$i]) != "") {
            $HTMLmessage .= $HTMLmessage1;
        }
    }
}
if (count($upload_Name) > 0) {
    for ($i = 0; $i < count($upload_Name); $i++) {
        $HTMLmessage .= '<tr>
<td align="left" valign="middle" width="' . $labelscolumn_width . '" style="border: ' . $border_width . 'px ' . $border_clr . ' ' . $border_type . '" bgcolor="' . $labels_bkgd_clr . '" width="150" height="24"><font style="font-size:13px" color="' . $labels_text_clr . '" face="Arial">';
        $l = count($fieldname) - count($upload_Name) + $i;
        $HTMLmessage .= $fieldname[$l];
        $HTMLmessage .= '</font></td>
<td align="left" valign="middle" style="border: ' . $border_width . 'px ' . $border_clr . ' ' . $border_type . '" bgcolor="' . $values_bkgd_clr . '" height="24"><font style="font-size:13px" color="' . $values_text_clr . '" face="Arial">';
        if ($store_uploaded == "Yes") {
            $HTMLmessage .= $fieldvalue[$l];
        }else {
            $HTMLmessage .= $filename[$i];
        }
        $HTMLmessage .= '</font></td>
</tr>
';
    }
}
if ($htmlbodyend != "") {
    $HTMLmessage .= '<tr>
<td align="center" colspan="2" valign="middle" style="border: ' . $border_width . 'px ' . $border_clr . ' ' . $border_type . '" bgcolor="' . $header_bgrd_clr . '" height="30">';
    $HTMLmessage .= stripslashes($htmlbodyend);
    $HTMLmessage .= '</td>
</tr>
';
}else if (trim($bodyend) != "") {
    $HTMLmessage .= '<tr>
<td align="center" valign="middle" colspan="2" style="border: ' . $border_width . 'px ' . $border_clr . ' ' . $border_type . '" bgcolor="' . $footer_bgrd_clr . '" height="18"><font style="font-size:13px" color="' . $footer_text_clr . '" face="Arial">';
    $HTMLmessage .= str_replace ("\r\n", "<br>", stripslashes($bodyend));
    $HTMLmessage .= '</font></td>
</tr>
';
}

if ($include_datetime == "Yes") {
    $HTMLmessage .= '<tr>
<td align="left" valign="middle" width="' . $labelscolumn_width . '" style="border: ' . $border_width . 'px ' . $border_clr . ' ' . $border_type . '" bgcolor="' . $labels_bkgd_clr . '" width="150" height="24"><font style="font-size:13px" color="' . $labels_text_clr . '" face="Arial">';
    $HTMLmessage .= str_replace("_", " ", $time_of_submission_label);
    $HTMLmessage .= '</font></td>
<td align="left" valign="middle" style="border: ' . $border_width . 'px ' . $border_clr . ' ' . $border_type . '" bgcolor="' . $values_bkgd_clr . '" height="24"><font style="font-size:13px" color="' . $values_text_clr . '" face="Arial">';
    $HTMLmessage .= " $date";
    $HTMLmessage .= '</font></td>
</tr>
';
}

if ($include_browser == "Yes") {
    $HTMLmessage .= '<tr>
<td align="left" valign="middle" width="' . $labelscolumn_width . '" style="border: ' . $border_width . 'px ' . $border_clr . ' ' . $border_type . '" bgcolor="' . $labels_bkgd_clr . '" width="150" height="24"><font style="font-size:13px" color="' . $labels_text_clr . '" face="Arial">';
    $HTMLmessage .= str_replace("_", " ", $browser_label);
    $HTMLmessage .= '</font></td>
<td align="left" valign="middle" style="border: ' . $border_width . 'px ' . $border_clr . ' ' . $border_type . '" bgcolor="' . $values_bkgd_clr . '" height="24"><font style="font-size:13px" color="' . $values_text_clr . '" face="Arial">';
    $HTMLmessage .= " $browser";
    $HTMLmessage .= '</font></td>
</tr>
';
}

if ($include_ip == "Yes") {
    $HTMLmessage .= '<tr>
<td align="left" valign="middle" width="' . $labelscolumn_width . '" style="border: ' . $border_width . 'px ' . $border_clr . ' ' . $border_type . '" bgcolor="' . $labels_bkgd_clr . '" width="150" height="24"><font style="font-size:13px" color="' . $labels_text_clr . '" face="Arial">';
    $HTMLmessage .= str_replace("_", " ", $ip_address_label);
    $HTMLmessage .= '</font></td>
<td align="left" valign="middle" style="border: ' . $border_width . 'px ' . $border_clr . ' ' . $border_type . '" bgcolor="' . $values_bkgd_clr . '" height="24"><font style="font-size:13px" color="' . $values_text_clr . '" face="Arial">';
    $HTMLmessage .= " $ip";
    $HTMLmessage .= '</font></td>
</tr>
';
}
$HTMLmessage .= '</table>
';

$HTMLmessage .= '</div>
</body>
</html>
';
$HTMLmessage = stripslashes($HTMLmessage) . "\r\n";

$TEXTmessage = stripslashes($bodystart) . "\r\n";
for ($i = 0; $i < (count($fieldname) - count($upload_Name)); $i++) {
    if (!in_array($fieldname[$i], $excludedfields)) {
        $TEXTmessage .= $fieldname[$i] . ": " . $fieldvalue[$i] . "\r\n";
    }
}
if (count($upload_Name) > 0) {
    for ($i = 0; $i < count($upload_Name); $i++) {
        $l = count($fieldname) - count($upload_Name) + $i;
        $TEXTmessage .= $fieldname[$l] . ": " . $fieldvalue[$l] . "\r\n";
    }
}
$TEXTmessage .= "\r\n" . $bodyend . "\r\n";
if ($include_datetime == "Yes") {
    $TEXTmessage .= "Time of submission: $date \r\n";
}
if ($include_browser == "Yes") {
    $TEXTmessage .= "Visitor's Browser: $browser \r\n";
}
if ($include_ip == "Yes") {
    $TEXTmessage .= "Visitor's IP Address: $ip \r\n";
}
if ($admin_mode == "HTML") {
    $MAILbody = $HTMLmessage;
    $ALTmessage = "To properly view this email your should use an email software that supports HTML \r\n\n";
    $ALTmessage .= $TEXTmessage;
}else {
    $MAILbody = nl2br($TEXTmessage);
    $ALTmessage = "";
}
?>