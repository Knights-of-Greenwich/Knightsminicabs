<?php

define('EMAIL_FOR_REPORTS', 'info@knightsminicabs.com');
define('RECAPTCHA_PRIVATE_KEY', '@privatekey@');
define('FINISH_URI', 'http://');
define('FINISH_ACTION', 'message');
define('FINISH_MESSAGE', 'Thank you for contacting Knights MiniCabs.
We shall respond shortly.');
define('UPLOAD_ALLOWED_FILE_TYPES', 'doc, docx, xls, csv, txt, rtf, html, zip, jpg, jpeg, png, gif');

require_once str_replace('\\', '/', __DIR__) . '/handler.php';

?>

<?php if (frmd_message()): ?>
<link rel="stylesheet" href="<?=dirname($form_path)?>/formoid-solid-blue.css" type="text/css" />
<span class="alert alert-success"><?=FINISH_MESSAGE;?></span>
<?php else: ?>
<!-- Start Formoid form-->
<link rel="stylesheet" href="<?=dirname($form_path)?>/formoid-solid-blue.css" type="text/css" />
<script type="text/javascript" src="<?=dirname($form_path)?>/jquery.min.js"></script>
<form class="formoid-solid-blue" style="background-color:#FFFFFF;font-size:14px;font-family:'Times New Roman',Times,serif;color:#111111;max-width:480px;min-width:150px" method="post"><div class="title"><h2>Contact Details</h2></div>
	<div class="element-input<?frmd_add_class("input")?>"><label class="title"><span class="required">*</span></label><div class="item-cont"><input class="large" type="text" name="input" required="required" placeholder="Input Text"/><span class="icon-place"></span></div></div>
	<div class="element-email<?frmd_add_class("email")?>"><label class="title"><span class="required">*</span></label><div class="item-cont"><input class="large" type="email" name="email" value="" required="required" placeholder="Email"/><span class="icon-place"></span></div></div>
	<div class="element-phone<?frmd_add_class("phone")?>"><label class="title"><span class="required">*</span></label><div class="item-cont"><input class="large" type="tel" pattern="[+]?[\.\s\-\(\)\*\#0-9]{3,}" maxlength="24" name="phone" required="required" placeholder="Phone" value=""/><span class="icon-place"></span></div></div>
	<div class="element-input<?frmd_add_class("input1")?>"><label class="title"><span class="required">*</span></label><div class="item-cont"><input class="large" type="text" name="input1" required="required" placeholder="Input Text"/><span class="icon-place"></span></div></div>
	<div class="element-input<?frmd_add_class("input2")?>"><label class="title"><span class="required">*</span></label><div class="item-cont"><input class="large" type="text" name="input2" required="required" placeholder="Input Text"/><span class="icon-place"></span></div></div>
	<div class="element-input<?frmd_add_class("input3")?>"><label class="title"><span class="required">*</span></label><div class="item-cont"><input class="large" type="text" name="input3" required="required" placeholder="Input Text"/><span class="icon-place"></span></div></div>
	<div class="element-date<?frmd_add_class("date")?>"><label class="title"><span class="required">*</span></label><div class="item-cont"><input class="large" data-format="yyyy-mm-dd" type="date" name="date" required="required" placeholder="Date"/><span class="icon-place"></span></div></div>
	<div class="element-multiple<?frmd_add_class("multiple1")?>"><label class="title"><span class="required">*</span></label><div class="item-cont"><div class="large"><select data-no-selected="Nothing selected" name="multiple1[]" multiple="multiple" required="required">

		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option></select><span class="icon-place"></span></div></div></div>
	<div class="element-multiple<?frmd_add_class("multiple")?>"><label class="title"><span class="required">*</span></label><div class="item-cont"><div class="large"><select data-no-selected="Nothing selected" name="multiple[]" multiple="multiple" required="required">

		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option></select><span class="icon-place"></span></div></div></div>
	<div class="element-input<?frmd_add_class("input4")?>"><label class="title"><span class="required">*</span></label><div class="item-cont"><input class="large" type="text" name="input4" required="required" placeholder="Destination"/><span class="icon-place"></span></div></div>
	<div class="element-textarea<?frmd_add_class("textarea")?>"><label class="title"></label><div class="item-cont"><textarea class="medium" name="textarea" cols="20" rows="5" placeholder="Additional Info."></textarea><span class="icon-place"></span></div></div>
	<div class="element-select<?frmd_add_class("select")?>"><label class="title"><span class="required">*</span></label><div class="item-cont"><div class="large"><span><select name="select" required="required">

		<option value="Yes">Yes</option>
		<option value="No">No</option></select><i></i><span class="icon-place"></span></span></div></div></div>
<div class="submit"><input type="submit" value="Submit Query"/></div></form><script type="text/javascript" src="<?=dirname($form_path)?>/formoid-solid-blue.js"></script>

<!-- Stop Formoid form-->
<?php endif; ?>

<?php frmd_end_form(); ?>