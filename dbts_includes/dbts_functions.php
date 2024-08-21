<?php
if (!function_exists(RecursiveMkdir)) {
function RecursiveMkdir($path)
{
    if (!file_exists($path)) {
        RecursiveMkdir(dirname($path));
        mkdir($path, 0777);
    }
}
}
if (!function_exists(ValidateEmail)) {
function ValidateEmail($email)
{
    $pattern = '/^([0-9a-z]([-.\w]*[0-9a-z])*@(([0-9a-z])+([-\w]*[0-9a-z])*\.)+[a-z]{2,6})$/i';
    return preg_match($pattern, $email);
}
}

if (!function_exists(array_trim)) {
function array_trim($array)
{   if(is_array($array)){
	foreach ($array as $key => $val) {
	$array[$key] = trim($val);
	}
}
	else{
	$array = trim($array);
	}
	return $array;
}
}

if (!function_exists(report_error)) {
function report_error($error, $error_url){
         if($error_url == "" and $GLOBALS['error_url'] != ""){
         $error_url = $GLOBALS['error_url'];
         }
         if(file_exists($error_url)){
         $errorcode = file_get_contents($error_url);
         $replace = "##error##";
         $errorcode = str_replace($replace, $error, $errorcode);
         echo $errorcode;
         }
         else{
		 echo "Error: $error <br><br>";
		 }
         exit;
}
}

function captcha_check($captcha_error, $error_url){

   if (isset($_POST['captcha_code'],$_SESSION['random_txt']) && md5($_POST['captcha_code']) == $_SESSION['random_txt'])
   {
      unset($_POST['captcha_code'],$_SESSION['random_txt']);
   }
   else
   {
      $error .= $captcha_error."<br>";
      report_error($error, $error_url);
   }
}

if (!function_exists(rewrite_captcha)) {
function rewrite_captcha($script){
$file_path = basename($script);
$page_code = file_get_contents($file_path);
$needle = '<? $flag = 1; ?>';
//echo "Count = ".substr_count($page_code, $needle);
//exit;
$needle1 = '<a href="javascript:history.back()">Go Back</a>';

if (substr_count($page_code, $needle) <= 0 and substr_count($page_code, $needle1) ==1) {
$page_code = '<? $flag = 1; ?>'.trim(substr($page_code, 424, strlen($page_code) - 424));
unlink($file_path);
//$file_path = "indexRW1.php";
$dbts_values  = $page_code;
if (!$dbts_handle = fopen($file_path, 'a+')) {
$error = "Can't open $file_path for writing";
report_error($error, $GLOBALS['error_url']);
}
if (fwrite($dbts_handle, $dbts_values) === FALSE) {
$error = "Cannot write file $file_path!";
report_error($error, $GLOBALS['error_url']);
}
fclose($dbts_handle);
}
}
}
if (!function_exists(allowurls)) {
function allowurls($var){
   $var = preg_replace('/http:\/\/[\w]+(.[\w]+)([\w\-\.,@?^=%&:\/~\+#]*[\w\-\@?^=%%&\/~\+#])?/i', '<a href="$0" >$0</a>', $var);
   return trim($var);
}
}

if (!function_exists(WriteToFile)) {
function WriteToFile($csvFile, $separator, $logdata, $dbfieldname, $double_quote_fields)

   {

   // Let's check if file exists. If yes, retrieve first line with labels
   if (file_exists($csvFile)) {

   $file = fopen($csvFile, 'r');
          $data = '';
          $label_data = fgets($file); // Get the first line
          $labels = explode($separator, trim($label_data));
          for ($i = 0; $i<count($labels); $i++){
		  $labels[$i] = trim($labels[$i]);
		  }
          $data_old = fread($file, filesize($csvFile));// Reads the content
          fclose($file);
 }
 // Else create the labels line
 else{
   $labels = $dbfieldname;
 }
// Add non existing fields in the database
foreach($dbfieldname as $key => $value){
if(!in_array($value, $labels)){
   if($double_quote_fields == "Yes"){
   $labels[] = '" '.str_replace('"','""',$value).' "';
   }
   else{
   $labels[] = $value;
   }
  }
}
// Create new labels line if $labels1 doesn't exist
 $labels_line = implode($separator, $labels)."\n";
// At this point we can add the new record in the database file

foreach($labels as $key=> $value){
$logdata1[$value] = str_replace($separator, urldecode($separator),$logdata[$value]);
if($double_quote_fields == "Yes"){
$logdata1[$value] =  '" '.str_replace('"','""',$logdata[$value]).' "';
}
}
$data = implode($separator, $logdata1)."\n";
      $file = fopen($csvFile, 'w+'); // overwrites old file if existing
      fwrite($file, $labels_line);
      if (!empty($data_old)) {
      fwrite($file, $data_old);
	        }
	  fwrite($file, $data);
      fclose($file);
}
}

if (!function_exists(WriteToMySQL)) {
function WriteToMySQL($db_name, $db_host, $db_user, $db_password, $db_table, $logdata, $dbfieldname, $create_table, $add_columns){
// Prepare dbfieldname array for use with the database
$db = db_connect();
for($i = 0; $i<count($dbfieldname); $i++){
$key = $dbfieldname[$i];
$dbfieldname[$i] = str_replace(" ","_", $dbfieldname[$i]);
$fieldtype[$i] = (strlen($logdata[$i] < 255))? "VARCHAR(255)" : "MEDIUMTEXT";
$logdata1[$dbfieldname[$i]] = mysql_real_escape_string($logdata[$key]);
}

// Find out if table exists, if not, create it
if($create_table == "Yes"){
$query = "CREATE TABLE IF NOT EXISTS `$db_table` (";
$query .='`ID` INT  NOT NULL AUTO_INCREMENT PRIMARY KEY';

 for ($i = 0; $i < (count($dbfieldname)); $i++)  {
$query .= ", `".$dbfieldname[$i]."`".$fieldtype[$i];
}
$query .=' )'
       . ' ENGINE = myisam ;';
$db = db_connect();
if (!mysql_query($query, $db)){
$error = "There has been an unknown error during Form Table creation. Invalid Query = $query . Error = ".mysql_error().". Please contact support at info@dbtechnosystems.com";
mysql_close($db);
report_error($error, $error_url);
}
}
// Get list of columns:
$labels = get_labels($db_table);


for($i = 0; $i < count($dbfieldname); $i++){

if($add_columns == "Yes"){
if (!in_array($dbfieldname[$i], $labels)) {
    $query = 'ALTER TABLE `'.$db_table.'` ADD `'.$dbfieldname[$i].'` '.$fieldtype[$i].' ;';
    $db = db_connect();
	if (!mysql_query($query, $db)){
    $error = "There has been an unknown error during Table Column Addition. Invalid Query = $query . Error = ".mysql_error().". Please contact support at info@dbtechnosystems.com";
    mysql_close($db);
    report_error($error, $error_url);
    }
    $labels[] = $dbfieldname[$i];
   }
  }
 }

$failure_message = "Failed creating new record in $db_table" ;
$id = create_entry($db_table, $dbfieldname, $logdata1, $failure_message) ;
return $id;
}
}

if (!function_exists(db_connect)) {
function db_connect()
{
$db = mysql_connect($GLOBALS['db_host'], $GLOBALS['db_user'], $GLOBALS['db_password']);
if ($db == FALSE){
$error = "Database error: ". mysql_error() . ". Please contact support at info@dbtechnosystems.com";
mysql_close($db);
report_error($error, $GLOBALS['errorpage']);
}
if (!mysql_select_db($GLOBALS['db_name'], $db)) {
$error = "Database error: ". mysql_error() . ". Please contact support at info@dbtechnosystems.com";
//mysql_close($db);
report_error($error, $GLOBALS['errorpage']);
}
return $db;
}
}
if (!function_exists(get_labels)) {
function get_labels($table)
{
$db = db_connect();
$result = mysql_list_fields($GLOBALS['db_name'], $table, $db);
$fieldnumber = mysql_num_fields($result);
for ($i = 0; $i < $fieldnumber; $i++) {
$labels[$i] = mysql_field_name($result, $i);
}
return $labels;
}
}

if (!function_exists(create_entry)) {
function create_entry($table, $keys, $values, $failure_message) {
@$query = "INSERT INTO `$table`(";
$k = count($keys);
for ($i = 0; $i < count($keys) - 1; $i++){
@$query .= "`".$keys[$i]."` ,";
}
@$query .= "`".$keys[$i]."`";
@$query .= ")";
@$query .= "VALUES (";
for ($i = 0; $i < count($keys) - 1; $i++){
@$query .= "'".$values[$keys[$i]]."',";
}
@$query .= "'".$values[$keys[$i]]."'";
@$query .= ")" ;
$db = db_connect();
$result = mysql_query($query, $db);
$recordID = mysql_insert_id();

if (!$result) {
$error = $failure_message.$GLOBALS['eol'].mysql_error();
report_error($error, $error_url);
}
mysql_close($db);
return $recordID;
}
}


if (!function_exists(update_record)) {
function update_record($table, $keys, $values, $where_column, $where_value, $failure_message) {

@$query = "UPDATE `$table` SET";

for ($i =0; $i < (count($keys) - 1); $i++){
@$query .= "`".$keys[$i]."` = \"".$values[$i]."\", ";
}
@$query .= "`".$keys[$i]."` = \"".$values[$i]."\" ";

if (is_array($where_column) and is_array($where_value)) {
for ($i = 0; $i < count($where_column) -1; $i ++){
$query1 .= "`".$where_column[$i]."` = '".$where_value[$i]."' ";
$query1 .= " AND ";
}
$query1 .= "`".$where_column[$i]."` = '".$where_value[$i]."' ";
}
else{
if($where_column != "" and $where_value != ""){
$query1 .= "`$where_column` = '$where_value'";
}
}
if ($query1 != "") {
$query .= " WHERE ".$query1;
}
//echo "Query = $query <br>";
$db = db_connect();
$result = mysql_query($query);
if (!$result) {
$error = $failure_message.$GLOBALS['eol']."Query = $query".$GLOBALS['eol'].mysql_error();
report_error($error, $GLOBALS['errorpage']);
}
return "Ok";
}
}

if (!function_exists(get_record_from_db)) {
function get_record_from_db($column, $table, $where_clause) {
$query = "SELECT `$column` FROM `$table` WHERE `$where_column` = '$where_value'";
$db = db_connect();
$result = mysql_query($query, $db);
if ($result) {
$row = mysql_fetch_array($result, MYSQL_ASSOC);
$content = trim(stripslashes($row[$column]));
}
else {
$content = "";
}
return $content;
}
}

if (!function_exists(check_unique_in_db)) {
function check_unique_in_db($string, $table, $column, $exist_error) {
if (trim($string) != "") {
$db = db_connect();
$query = "SELECT $column FROM $table WHERE $column= '$string'";
$result = mysql_query($query, $db);
$row = mysql_fetch_array($result, MYSQL_ASSOC);
if ($row !="") {
                $error = $exist_error;
                if ($error != "") {
                $error .= $GLOBALS['eol'];
                }
}
}
return $error;
}
}

if (!function_exists(check_pass_strength)) {
function check_pass_strength($string){
$pattern = '/^.*(?=.{8,})(?=.*\d)(?=.*[a-z]{2})(?=.*[A-Z]{2})(?=.*[@#$%^&+=]).*$/';
if (!preg_match($pattern, $string)) {
$error = $GLOBALS['eol']. "Your Password is too weak, please try something different!"
. " Length must be between ".$GLOBALS['pass_min']." and ".$GLOBALS['pass_max']." chrs and must contain at least 2 lowercase and 2 uppercase letters, two digits, and at least one of the folowing symbols: @,#,$,%,^,&,+,= ";
if ($error != "") {
$error .= $GLOBALS['eol'];
}
}
return $error;
}
}

if (!function_exists(getRealIpAddr)) {
function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
}

if (!function_exists(post)) {
function post($host,$data,$others){
    $path=explode('/',$host);
    $host=$path[0];
    unset($path[0]);
    $path='/'.(implode('/',$path));
while (list ($key, $val) = each ($data))
{
$query .= urlencode($key)."=".urlencode($val)."&";
}
$query = rtrim($query, '&');
    $post="POST $path HTTP/1.1\r\nHost: $host\r\nContent-type: application/x-www-form-urlencoded\r\n ${others} User-Agent: Mozilla 4.0\r\nContent-length: ".strlen($query)."\r\nConnection: close\r\n\r\n$query";
    $h=fsockopen($host,80,$errno, $errstr, 30);
//if (!$h) {
//    echo "Error: $errstr ($errno)<br />\n";
//}
    fwrite($h,$post);
    for($a=0,$r='';!$a;){
        $b=fread($h,8192);
        $r.=$b;
        $a=(($b=='')?1:0);
    }
    fclose($h);
    return $r;
}
}


if (!function_exists(check_credit_card)) {
function check_credit_card($cc_number, $cc_type) {
   /* Validate; return value is card type if valid. */
   $false = false;
   $card_type = "";
   $card_regexes = array(
      "/^4\d{12}(\d\d\d){0,1}$/" => "Visa",
      "/^5[12345]\d{14}$/"       => "Mastercard",
      "/^3[47]\d{13}$/"          => "American Express",
      "/^6011\d{12}$/"           => "Discover",
      "/^30[012345]\d{11}$/"     => "Diners Club",
      "/^3[68]\d{12}$/"          => "Diners Club",
   );

   foreach ($card_regexes as $regex => $type) {
       if (preg_match($regex, $cc_number)) {
           $card_type = $type;
           break;
       }
   }

   if (!$card_type) {
$error = "Your Credit Card number doesn't match any of the known Credit Card formats".$GLOBALS['eol'];
   }
else{
   if ($card_type != $cc_type) {
$error .= "Your Credit Card number doesn't match the format for $type";
 if ($error != "") {
 $error .= $GLOBALS['eol'];
 }
   }
}
   /*  mod 10 checksum algorithm  */
   $revcode = strrev($cc_number);   $checksum = 0;

   for ($i = 0; $i < strlen($revcode); $i++) {
       $current_num = intval($revcode[$i]);
       if($i & 1) {  /* Odd  position */
          $current_num *= 2;
       }
       /* Split digits and add. */
           $checksum += $current_num % 10; if
       ($current_num >  9) {
           $checksum += 1;
       }
   }

   if ($checksum % 10 != 0) {
$error .= "Your Credit Card number seems incorrect";
   }
    if ($error != "") {
    $error .= $GLOBALS['eol'];
    }
return $error;
}
}

?>