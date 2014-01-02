<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php
$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "5") {   
  header("Location: ". $restrictGoTo); 
  exit;
}
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="user_error.php";
  $loginUsername = $_POST['tk_user_login'];
  $LoginRS__query = sprintf("SELECT tk_user_login FROM tk_user WHERE tk_user_login=%s", GetSQLValueString($loginUsername, "text"));
  mysql_select_db($database_tankdb, $tankdb);
  $LoginRS=mysql_query($LoginRS__query, $tankdb) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$password = "-1";
if (isset($_POST['tk_user_pass'])) {
  $password = $_POST['tk_user_pass'];
}

$tk_password = md5(crypt($password,substr($password,0,2)));

if ( empty( $_POST['tk_user_contact'] ) ){
$tk_user_contact = "'',";
}else{
$tk_user_contact = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_user_contact']), "text"));
}

if ( empty( $_POST['tk_user_email'] ) ){
$tk_user_email = "'',";
}else{
$tk_user_email = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_user_email']), "text"));
}

if ( empty( $_POST['tk_user_remark'] ) ){
$tk_user_remark = "'',";
}else{
$tk_user_remark = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_user_remark']), "text"));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tk_user (tk_user_login, tk_user_pass, tk_display_name, tk_user_rank, tk_user_remark, tk_user_contact, tk_user_email, tk_user_backup1) VALUES (%s, %s, %s, %s, $tk_user_remark $tk_user_contact $tk_user_email '')",
                       GetSQLValueString($_POST['tk_user_login'], "text"),
                       GetSQLValueString($tk_password, "text"),
                       GetSQLValueString($_POST['tk_display_name'], "text"),
                       GetSQLValueString($_POST['tk_user_rank'], "text"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());

  $insertGoTo = "default_user.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WSS - <?php echo $multilingual_user_new; ?></title>
    <link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
    <link href="skin/themes/base/lhgcheck.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="srcipt/lhgcore.js"></script>
    <script type="text/javascript" src="srcipt/lhgcheck.js"></script>
<script type="text/javascript">
J.check.rules = [
    { name: 'tk_user_login', mid: 'user_login', type: 'limit|alpha', requir: true, min: 2, max: 12, warn: '<?php echo $multilingual_user_namequired; ?>|<?php echo $multilingual_user_alpha; ?>' },
	{ name: 'tk_user_pass', mid: 'user_pass', type: 'limit', requir: true, min: 2, max: 8, warn: '<?php echo $multilingual_user_namequired8; ?>' },
	{ name: 'tk_display_name', mid: 'display_name', type: 'limit', requir: true, min: 2, max: 12, warn: '<?php echo $multilingual_user_namequired; ?>' },
	{ name: 'tk_user_pass', mid: 'user_pass2', requir: true, type: 'match', to: 'tk_user_pass2', warn: '<?php echo $multilingual_user_tip_match; ?>' }
];

window.onload = function()
{
    J.check.regform('form1');
}
</script>
</head>

<body>
<?php require('head.php'); ?>
<p>&nbsp;</p>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center" width="80%">
    <tr valign="baseline">
      <td><span class="font_big18 fontbold float_left"><?php echo $multilingual_user_new; ?></span> </td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td><?php echo $multilingual_user_account; ?>:</td>
      <td><input type="text" name="tk_user_login" id="tk_user_login" value="" size="32" />
      <span id="user_login" class="gray"> <?php echo $multilingual_user_tip_account; ?></span><span class="red">*</span></td>
    </tr>
    <tr valign="baseline">
      <td><?php echo $multilingual_user_password; ?>:</td>
      <td><input type="password" name="tk_user_pass" id="tk_user_pass" value="" size="32" />
      <span id="user_pass" class="gray"> <?php echo $multilingual_user_tip_password; ?></span><span class="red">*</span></td>
    </tr>
    <tr valign="baseline">
      <td><?php echo $multilingual_user_password2; ?>:</td>
      <td><input type="password" name="tk_user_pass2" id="tk_user_pass2" value="" size="32" />
	  <span id="user_pass2" class="gray"> <?php echo $multilingual_user_tip_password2; ?></span><span class="red">*</span>	  </td>
    </tr>
    <tr valign="baseline">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td><?php echo $multilingual_user_name; ?>:</td>
      <td><input type="text" name="tk_display_name" id="tk_display_name" value="" size="32" />
      <span id="display_name" class="gray"> <?php echo $multilingual_user_tip_name; ?></span><span class="red">*</span></td>
    </tr>
	<tr valign="baseline" >
      <td><?php echo $multilingual_user_contact; ?>:</td>
      <td><input type="text" name="tk_user_contact" id="tk_user_contact" value="" size="32" /><span class="gray"> <?php echo $multilingual_user_tip_contact; ?></span></td>
    </tr>
    <tr valign="baseline" >
      <td><?php echo $multilingual_user_email; ?>:</td>
      <td><input type="text" name="tk_user_email" id="tk_user_email" value="" size="32" /><span class="gray"> <?php echo $multilingual_user_tip_mail; ?></span></td>
    </tr>
   
    <tr> </tr>
    <tr valign="baseline">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td><?php echo $multilingual_user_role; ?>:</td>
      <td><select name="tk_user_rank">
	    <option value="0" ><?php echo $multilingual_dd_role_disabled; ?></option>
		<option value="1" ><?php echo $multilingual_dd_role_readonly; ?></option>
		<option value="2" ><?php echo $multilingual_dd_role_guest; ?></option>
        <option value="3" selected="selected"><?php echo $multilingual_dd_role_general; ?></option>
		<option value="4" ><?php echo $multilingual_dd_role_pm; ?></option>
        <option value="5" ><?php echo $multilingual_dd_role_admin; ?></option>		
      </select>      </td>
    </tr>
    <tr valign="baseline">
      <td>&nbsp;</td>
      <td><table width="100%" border="1" cellspacing="0" cellpadding="5" class="rank_talbe">
        <tr>
          <td><?php echo $multilingual_user_role; ?></td>
          <td><?php echo $multilingual_rank1; ?></td>
          <td><?php echo $multilingual_rank2; ?></td>
          <td><?php echo $multilingual_rank3; ?></td>
          <td><?php echo $multilingual_rank4; ?></td>
          <td><?php echo $multilingual_rank5; ?></td>
          <td><?php echo $multilingual_rank6; ?></td>
          <td><?php echo $multilingual_rank7; ?></td>
          <td><?php echo $multilingual_rank8; ?></td>
          <td><?php echo $multilingual_rank9; ?></td>
        </tr>
        <tr>
          <td><?php echo $multilingual_dd_role_disabled; ?></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
        </tr>
        <tr>
          <td><?php echo $multilingual_dd_role_readonly; ?></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
        </tr>
        <tr>
          <td><?php echo $multilingual_dd_role_guest; ?></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
        </tr>
        <tr>
          <td><?php echo $multilingual_dd_role_general; ?></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
        </tr>
        <tr>
          <td><?php echo $multilingual_dd_role_pm; ?></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
        </tr>
        <tr>
          <td><?php echo $multilingual_dd_role_admin; ?></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
        </tr>
      </table></td>
    </tr>
    <tr valign="baseline">
      <td valign="top">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td valign="top"><?php echo $multilingual_user_remark; ?>:</td>
      <td><textarea name="tk_user_remark" cols="50" rows="5"></textarea>
        </br>
	  <span class="gray glink"> <?php echo $multilingual_user_tip_remark; ?></span>	  </td>
    </tr>
    <tr valign="baseline">
      <td>&nbsp;</td>
      <td><input type="submit" value="<?php echo $multilingual_global_action_save; ?>" 
	   <?php if( $_SESSION['MM_Username'] == $multilingual_dd_user_readonly){
	  echo "disabled='disabled'";
	  } ?> 
	  />
        <label>
        <input name="Submit" type="submit" onclick="javascript:history.go(-1)" value="<?php echo $multilingual_global_action_cancel; ?>" />
      </label></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
<?php require('foot.php'); ?>
</body>
</html>

