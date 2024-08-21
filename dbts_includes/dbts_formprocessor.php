<?php
eval($start_of_script_include);
if ($SMTPSecure == "No") {
    $SMTPSecure = "";
}
if (trim($SMTPPort) == "") {
    $SMTPPort = 25;
}

$allowed_extensions = explode(",", $file_ext);
for($i = 0; $i < count($allowed_extensions); $i++) {
    $allowed_extensions[$i] = trim($allowed_extensions[$i]);
}

$auto_attachments = explode(",", $auto_attach);
for($i = 0; $i < count($auto_attachments); $i++) {
    $auto_attachments[$i] = trim($auto_attachments[$i]);
}

$excludedfields = explode(",", $excluded_fields);
for($i = 0; $i < count($excludedfields); $i++) {
    $excludedfields[$i] = ucwords(str_replace("_", " ", $excludedfields[$i]));
}

$separator = ",";
if ($csvSeparator == "semicolon ;") {
    $separator = ";";
}
$formid = isset($_POST['formid']) ? $_POST['formid'] : "";
if (($form_id != "" and $form_id == $formid) or $formid == "") {
    eval($before_formdata_include);
    // ----Find Browser, IP address------------------
    $browser = $_SERVER['HTTP_USER_AGENT'];
    $ip = getRealIpAddr();
    // Check for banned IP addrsses
    for ($i = 0; $i < count($banned_ipaddress); $i++) {
        if (substr_count($ip, $banned_ipaddress[$i]) > 0) {
            $banned_error = $banned_errormessage;
        }
    }

        if (in_array(trim($_POST[$email_field]), $bannedemail_emails)) {
        $banned_error = $banned_errormessage;
        }
if($banned_error != ""){
        if ($banned_behavior == "Display Success Page") {
            header("Location: $success_url");
        }else {
            report_error($error, $error_url);
        }
}

    // Add timestamp and IP address in the fields list
    $dbfieldname[0] = $time_of_submission_label;
    $logdata[$time_of_submission_label] = $date;
    $dbfieldname[1] = "Timestamp";
    $logdata['Timestamp'] = time();
    $dbfieldname[2] = $ip_address_label;
    $logdata[$ip_address_label] = $ip;

    if ($copy_from_session == "Yes") {
        $POST = $SESSION;
    }while (list ($key, $val) = each ($_POST)) {
        $POST[$key] = $val;
    }
    eval($after_formdata_include);
    // -------- RECEIVING FIELDNAMES VALUES AND VALIDATION DETAILS------
    reset($POST);
    $i = 0;

    $internalfields = array ("submit", "send", "captcha_code", "count", "formid", "username", "recaptcha_challenge_field", "recaptcha_response_field");

    if ($use_reverse_captcha == "Yes") {
        if ($empty_field_name != "") {
            $internalfields[] = $empty_field_name;
            $empty_field_value_posted = $_POST[$empty_field_name];
            unset ($_POST[$empty_field_name]);
            unset ($POST[$empty_field_name]);
        }
        if ($non_empty_field_name != "") {
            $internalfields[] = $non_empty_field_name;
            $non_empty_field_value_posted = $_POST[$non_empty_field_name];
            unset ($_POST[$non_empty_field_name]);
            unset ($POST[$non_empty_field_name]);
        }
    }while (list ($key, $val) = each ($POST)) {
        if (!in_array(strtolower($key), $internalfields)) {
            $fieldname[$i] = ucwords(str_replace("_", " ", $key));
            $dbfieldname[$i + 3] = ucwords(strtolower(str_replace("_", "", $fieldname[$i])));
            $dbfieldname[$i + 3] = str_replace("-", "_", $dbfieldname[$i + 3]);

            if (!is_array($val)) {
                $fieldvalue[$i] = $val;
                $logvalue = stripslashes(str_replace(";", ",", $val));
                $logdata[$dbfieldname[$i + 3]] = $logvalue;
            }else {
                $fieldvalue[$i] = implode(",", $val);
                $logdata[$dbfieldname[$i + 3]] = stripslashes(str_replace(";", ",", implode("|", $val)));
            }
            if ($allow_urls == "Yes") {
                $fieldvalue[$i] = allowurls($fieldvalue[$i]);
            }

            if (strtolower($fieldname[$i]) == $email_field) {
                $fieldvalue[$i] = '<A href="mailto:' . $fieldvalue[$i] . '">' . $fieldvalue[$i] . "</a>";
            }
            $i++;
        }
    }
    // ------RECEIVING FILE VARIABLES--------------------
    $prefix = rand(100000, 1000000);
    reset ($_FILES);
    $k = 0;
    while (list ($key, $val) = each ($_FILES)) {
        if ($_FILES[$key]['name'] != "" and $_FILES[$key]['size'] > 0) {
            $upload_Name[$k] = str_replace(" ", "_", $_FILES[$key]['name']);
            $filename[$k] = $_FILES[$key]['name'];
            if ($add_prefix == "Yes") {
                $upload_Name[$k] = $prefix . "_" . $upload_Name[$k];
            }

            $upload_Size[$k] = ($_FILES[$key]['size']);
            $upload_Temp[$k] = ($_FILES[$key]['tmp_name']);
            $uploadlink[$k] = $uploadfolder . "/" . $upload_Name[$k] . "\n";
            $fieldname[$i] = ucwords(str_replace("_", " ", $key));
            $dbfieldname[$i + 3] = ucwords(strtolower(str_replace("_", "", $fieldname[$i])));
            $dbfieldname[$i + 3] = str_replace("-", "_", $dbfieldname[$i + 3]);
            $fieldvalue[$i] = allowurls("http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . "/" . $uploadlink[$k]);
            $logdata[$dbfieldname[$i + 3]] = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . "/" . $uploadlink[$k];
            $total_attach_size += $upload_Size[$k];
            $i++;
            $k++;
        }
    }

    if ($copy_in_session == "Yes") {
        // $_SESSION['POST'] = "";
        $_SESSION['POST'] = $POST;
    }
    // Captcha Verification
    if ($captcha_rewrite == "Yes") {
        $script = basename($_SERVER['PHP_SELF']);
        rewrite_captcha($script);
    }
    if (isset($_POST['captcha_code'])) {
        if (isset($_POST['captcha_code'], $_SESSION['random_txt']) && md5($_POST['captcha_code']) == $_SESSION['random_txt']) {
            unset($_POST['captcha_code'], $_SESSION['random_txt']);
        }else {
            $error .= $captcha_error . "<br>";
            report_error($error, $error_url);
        }
    }
    // Recaptcha Verification
    if ($force_recaptcha == "Yes") {
        require_once('dbts_includes/recaptchalib.php');
        $resp = recaptcha_check_answer ($privatekey,
            $_SERVER["REMOTE_ADDR"],
            $_POST["recaptcha_challenge_field"],
            $_POST["recaptcha_response_field"]);

        if (!$resp->is_valid) {
            $response_error = $resp->error;
            $error = $recaptcha_error_array[$response_error];
            if ($response_error != "recaptcha-not-reachable" or (($recaptcha_failure_behavior == "Disallow submission" and $response_error == "recaptcha-not-reachable"))) {
                report_error($error, $error_url);
                exit;
            }else {
                $error = "";
            }
        }
    }

    if ($use_reverse_captcha == "Yes") {
        if ($non_empty_field_value_posted != $non_empty_field_value or !empty($empty_field_value_posted)) {
            if ($reverse_captcha_behavior == "Display Error Page") {
                $error .= $reverse_captcha_error . "<br>";
                report_error($error, $error_url);
            }else if ($reverse_captcha_behavior == "Display Success Page") {
                header("Location: $success_url");
                exit;
            }
        }
    }

    eval($after_filedata_include);
    // Set Total size of attachments
    $total_attach_size = $total_attach_size / (1024 * 1000);
    // Finding out if there are more than one email addresses to receive the admin email
    $email = $POST[$email_field];
    $mailtos = array_trim(explode(",", $mailto));
    $mailfrom = !empty($from_email) ? $from_email : $mailtos[0];
    // Set $mailfrom and $mailtos if departments are enabled
    if ($enable_departments == "Yes"); {
        // Check if a departments has been submitted
        $department = $_POST[$department_field];
        $dep_mailto = $department_emails[$department];
        $dep_nameto = $department_names[$department];
        $dep_mailtos_array = explode(",", $dep_mailto);
        foreach($dep_mailtos_array as $key => $value) {
            if (trim($value) != "") {
                $dep_mailtos[] = trim($value);
            }
        }

        if (count($dep_mailtos) > 0) {
            $mailtos = $dep_mailtos;
            $nameto = $dep_nameto;
        }
    }
    // Set the CC email addresses to send the mail to
    $cc_mailtos = array_trim(explode(",", $cc_email));
    $bcc_mailtos = array_trim(explode(",", $bcc_email));
    // Email validation
    if (!empty($email)) {
        if (!ValidateEmail($email)) {
            $error .= "The specified email address is invalid!\n<br>";
        }
    }
    // ------------CHECKING FOR MAX UPLOADED FILE SIZE ----------------------
    for ($i = 0; $i < count($upload_Name); $i++) {
        if ($upload_Name[$i] != "") {
            $ext = explode(".", $filename[$i]);
            $file_ext = strtolower($ext[count($ext) - 1]);

            if (count($allowed_extensions) > 0) {
                if (!in_array($file_ext, $allowed_extensions)) {
                    $error .= "The file type of $filename[$i] is not allowed!\n";
                }
            }

            if ($upload_Size[$i] >= $filesize * 1024) {
                $error .= "The size of $upload_Name[$i] is bigger than the allowed $filesize Kb !\n";
            }
        }
    }

    eval($before_error_include);

    if (!empty($error)) {
        report_error($error, $error_url);
    }

    if ($csvStore == "Yes") {
        WriteToFile($csvFile, $separator, $logdata, $dbfieldname, $double_quote_fields);
    }
    if ($dbStore == "Yes") {
        $record_id = WriteToMySQL($db_name, $db_host, $db_user, $db_password, $db_table, $logdata, $dbfieldname, $create_table, $add_columns);
    }
    // Creating the Image code
    if ($logo_image_url != "") {
        $image_code = '<img src="' . $logo_image_url . '" id="Logo" alt="' . $logo_alt . '" border="0">';
    }
    if ($logo_link != "") {
        $image_code = '<a href="' . $logo_link . '">' . $image_code . '</a>';
    }

    eval($before_admin_mail_include);

    if ($admin_mail == "Yes") {
        $admin_mail_template = 'dbts_includes/admin_email.php';
        if (trim($custom_admin_mail_template) != "" and file_exists($custom_admin_mail_template)) {
            $admin_mail_template = $custom_admin_mail_template;
        }

        require_once $admin_mail_template;
        // Send email in  html format
        require_once 'dbts_includes/class.phpmailer.php';
        // include 'dbts_includes/class.smtp.php';
        // Set Generic Details
        $mail = new PHPMailer();

        if ($mail_engine == "Sendmail") {
            $mail->IsSendmail(); // set mailer to use Sendmail
        }

        if ($mail_engine == "Qmail") {
            $mail->IsQmail(); // set mailer to use Sendmail
        }

        if ($mail_engine == "SMTP") {
            $mail->IsSMTP(); // set mailer to use SMTP
            $mail->Host = $SMTPHost; // specify main and backup server
            $mail->Port = $SMTPPort; // specify smtp port
            if ($SMTPAuth == "true") {
                $mail->SMTPAuth = true; // turn on SMTP authentication
            }else {
                $mail->SMTPAuth = false;
            }
            $mail->Username = $SMTPUser; // SMTP username
            $mail->Password = $SMTPPass; // SMTP password
        }
        if ($SMTPSecure != "") {
            $mail->SMTPSecure = $SMTPSecure; // turn on Secure Connection
        }
        $mail->Subject = stripslashes($subject);

        $mail->From = $from_email;
        if (stripslashes($form_namefrom) != "") {
            $namefrom_array = array_trim(explode(",", $form_namefrom));
            foreach($namefrom_array as $key => $value) {
                $namefrom1 .= $_POST[$value] . " ";
            }
            if (trim($namefrom1) != "") {
                $namefrom = trim($namefrom1);
            }
        }
        $mail->FromName = stripslashes($namefrom);

        for ($i = 0; $i < count($mailtos); $i++) {
            if (trim(str_replace(",", "", $mailtos[$i])) != "") {
                $mail->AddAddress($mailtos[$i], $nameto);
            }
        }
        for ($i = 0; $i < count($cc_mailtos); $i++) {
            if (trim(str_replace(",", "", $cc_mailtos[$i])) != "") {
                $mail->AddCC($cc_mailtos[$i], "");
            }
        }
        for ($i = 0; $i < count($bcc_mailtos); $i++) {
            if (trim(str_replace(",", "", $bcc_mailtos[$i])) != "") {
                $mail->AddBCC($bcc_mailtos[$i], "");
            }
        }
        if ($email != "") {
            $mail->AddReplyTo($email);
        }

        $mail->CharSet = $charset;
        if ($admin_mode == "HTML") {
            $mail->IsHTML(true);
            $mail->AltBody = $ALTmessage;
        }else {
            $mail->IsHTML(false);
            $mail->WordWrap = 70; // set word wrap to 70 characters
        }

        $mail->MsgHTML($MAILbody);
        // Lets add the attachments if so set
        if ($attach_files == "Yes" and $total_attach_size <= $max_attach_size) {
            if (!empty($_FILES)) {
                $k = 0;
                foreach ($_FILES as $key => $value) {
                    if ($_FILES[$key]['error'] == 0 && $_FILES[$key]['size'] <= $filesize * 1024) {
                        $mail->AddAttachment($upload_Temp[$k], $filename[$k]);
                        $k++;
                    }
                }
            }
        }

        if (!$mail->Send()) {
            $report .= " EMAIL FAILED  Mailer Error: " . $mail->ErrorInfo . " \r\n";
            report_error($report, $error_url);
        }
    }
    // --------- Move the files to destination----------------
    // We cant do this earlier as we need to add the files to the email body
    if ($store_uploaded == "Yes") {
        $uploadfolder1 = basename($uploadfolder);
        for ($i = 0; $i < count($upload_Name); $i++) {
            if ($upload_Size[$i] > 0) {
                $uploadFile = "$uploadfolder1/" . $upload_Name[$i];
                if (!is_dir(dirname($uploadFile))) {
                    @RecursiveMkdir(dirname($uploadFile));
                }else {
                    @chmod(dirname($uploadFile), 0777);
                }
                @move_uploaded_file($upload_Temp[$i] , $uploadFile);
                chmod($uploadFile, 0644);
            }
        }
    }

    if ($autoresponder == "Yes" and $email != "") {
        require_once 'dbts_includes/class.phpmailer.php';
        $auto_mail_template = 'dbts_includes/auto_email.php';
        if (trim($custom_auto_mail_template) != "" and file_exists($custom_auto_mail_template)) {
            $auto_mail_template = $custom_auto_mail_template;
        }

        require_once $auto_mail_template;

        eval($before_auto_mail_include);
        // Set the From address if one doesn't exist
        if (trim($autoresponderfrom) == "") {
            $autoresponderfrom = $from_email;
        }
        // Send email in  html format
        // Set Generic Details
        $mail = new PHPMailer();

        $mail->Subject = stripslashes($autorespondersubject);
        $mail->From = $autoresponderfrom;
        $mail->FromName = stripslashes($autorespondernamefrom);
        $mail->AddAddress($email);
        $mail->AddReplyTo($autoresponderfrom);

        if ($mail_engine == "Sendmail") {
            $mail->IsSendmail(); // set mailer to use Sendmail
        }

        if ($mail_engine == "Qmail") {
            $mail->IsQmail(); // set mailer to use Sendmail
        }

        if ($mail_engine == "SMTP") {
            $mail->IsSMTP(); // set mailer to use SMTP
            $mail->Host = $SMTPHost; // specify main and backup server
            $mail->Port = $SMTPPort; // specify smtp port
            if ($SMTPAuth == "true") {
                $mail->SMTPAuth = true; // turn on SMTP authentication
            }else {
                $mail->SMTPAuth = false;
            }
            $mail->Username = $SMTPUser; // SMTP username
            $mail->Password = $SMTPPass; // SMTP password
            $mail->From = $SMTPUser;
        }
        if ($SMTPSecure != "") {
            $mail->SMTPSecure = $SMTPSecure; // turn on Secure Connection
        }
        $mail->CharSet = $charset;
        if ($auto_mode == "HTML") {
            $mail->IsHTML(true);
            $mail->AltBody = $ALTmessage;
        }else {
            $mail->IsHTML(false);
            $mail->WordWrap = 70; // set word wrap to 70 characters
        }
        // set word wrap to 70 characters
        $mail->MsgHTML($MAILbody);
        // Lets add the attachments if so set
        foreach ($auto_attachments as $attachment) {
            if (file_exists($attachment)) {
                $mail->AddAttachment($attachment);
            }
        }

        if (!$mail->Send()) {
            $report .= " EMAIL FAILED  Mailer Error: " . $mail->ErrorInfo . " \r\n";
            report_error($report, $error_url);
        }
    }
    // Posting the form data to a further script
    if ($post_further == "Yes") {
        post($post_further_to, $_POST, $others);
    }
    extract($POST, EXTR_OVERWRITE);

    eval($end_of_script_include);

    if ($afterprocessing == "Include" and file_exists($end_include)) {
        include $end_include;
        exit;
    }
    if ($clear_session == "Yes") {
        unset($_SESSION['POST']);
    }
    if ($afterprocessing == "Redirect") {
        header("Location: $success_url");
        exit;
    }
}

?>