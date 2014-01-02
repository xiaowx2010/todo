<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php
$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "2") {   
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

$colname_Recordset1 = "-1";
if (isset($_GET['UID'])) {
  $colname_Recordset1 = $_GET['UID'];
}
mysql_select_db($database_tankdb, $tankdb);
$query_Recordset1 = sprintf("SELECT * FROM tk_user WHERE uid = %s", GetSQLValueString($colname_Recordset1, "text"));
$Recordset1 = mysql_query($query_Recordset1, $tankdb) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$restrictGoTo = "user_error3.php";
if ($row_Recordset1['uid'] <> $_SESSION['MM_uid'] && $_SESSION['MM_rank'] < "5") {   
  header("Location: ". $restrictGoTo); 
  exit;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ( empty( $_POST['tk_user_remark'] ) ){
$tk_user_remark = "tk_user_remark='',";
}else{
$tk_user_remark = sprintf("tk_user_remark=%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_user_remark']), "text"));
}

if ( empty( $_POST['tk_user_contact'] ) ){
$tk_user_contact = "tk_user_contact='',";
}else{
$tk_user_contact = sprintf("tk_user_contact=%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_user_contact']), "text"));
}

if ( empty( $_POST['tk_user_email'] ) ){
$tk_user_email = "tk_user_email=''";
}else{
$tk_user_email = sprintf("tk_user_email=%s", GetSQLValueString(str_replace("%","%%",$_POST['tk_user_email']), "text"));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE tk_user SET tk_display_name=%s, tk_user_rank=%s, $tk_user_remark $tk_user_contact $tk_user_email WHERE uid=%s",
                       
                       GetSQLValueString($_POST['tk_display_name'], "text"),
                       GetSQLValueString($_POST['tk_user_rank'], "text"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($updateSQL, $tankdb) or die(mysql_error());

  $updateGoTo = "user_view.php?recordID=$colname_Recordset1";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WSS - <?php echo $multilingual_user_edit_title; ?></title>
<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/custom.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/lhgcheck.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/lhgcore.js"></script>
<script type="text/javascript" src="srcipt/lhgcheck.js"></script>
<script type="text/javascript">
J.check.rules = [
	{ name: 'tk_display_name', mid: 'display_name', type: 'limit', requir: true, min: 2, max: 12, warn: '<?php echo $multilingual_user_namequired; ?>' }
	
];

window.onload = function()
{
    J.check.regform('form1');
}
function TuneHeight()    
{    
var frm = document.getElementById("frame_content");    
var subWeb = document.frames ? document.frames["main_frame"].document : frm.contentDocument;    
if(frm != null && subWeb != null)    
{ frm.height = subWeb.body.scrollHeight;}    
}   
</script>
<script language="javascript">
function tabs(n)
{
var len = 2;
for (var i = 1; i <= len; i++)
{
document.getElementById('tab_a' + i).style.display = (i == n) ? 'block' : 'none';
document.getElementById('tab_' + i).className = (i == n) ? 'onhover' : 'none';
}
}
</script>
</head>

<body>
<?php require('head.php'); ?>
<p>&nbsp;</p>
<div class="tab" style="width:80%; margin:0px auto 0px auto;">
<span class="font_big18 fontbold float_left"><?php echo $multilingual_user_edit_title; ?></span><br /><br />
  <ul class="menu" id="menutitle">
    <li id="tab_1" class="onhover" ><a href="javascript:void(0)" onclick="tabs('1');" ><?php echo $multilingual_user_edit_info; ?></a></li>
    <li id="tab_2" ><a href="javascript:void(0)" onclick="tabs('2');" ><?php echo $multilingual_user_edit_password; ?></a></li>
    <li >&nbsp;</li>
    <li >&nbsp;</li>
  </ul>
  <div class="tab_b" id="tab_a1" style="display: block">
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center" width="100%">
        <tr valign="baseline">
          <td><?php echo $multilingual_user_account; ?>:</td>
          <td><input name="tk_user_login" type="text" value="<?php echo htmlentities($row_Recordset1['tk_user_login'], ENT_COMPAT, 'utf-8'); ?>" size="32" readonly="readonly" class="gray" /></td>
        </tr>
        <tr valign="baseline">
          <td><?php echo $multilingual_user_name; ?>:</td>
          <td><input type="text" name="tk_display_name" id="tk_display_name" value="<?php echo htmlentities($row_Recordset1['tk_display_name'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
              <span id="display_name" class="gray"> <?php echo $multilingual_user_tip_name; ?></span></td>
        </tr>
        <tr valign="baseline" >
          <td><?php echo $multilingual_user_contact; ?>:</td>
          <td><input type="text" name="tk_user_contact" value="<?php echo htmlentities($row_Recordset1['tk_user_contact'], ENT_COMPAT, 'utf-8'); ?>"  size="32" />
              <span class="gray"> <?php echo $multilingual_user_tip_contact; ?></span></td>
        </tr>
        <tr valign="baseline" >
          <td><?php echo $multilingual_user_email; ?>:</td>
          <td><input type="text" name="tk_user_email" value="<?php echo htmlentities($row_Recordset1['tk_user_email'], ENT_COMPAT, 'utf-8'); ?>"  size="32" />
              <span class="gray"> <?php echo $multilingual_user_tip_mail; ?></span></td>
        </tr>
        
        <?php if ($_SESSION['MM_rank'] > "4") { ?>
        <tr valign="baseline">
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td><?php echo $multilingual_user_role; ?>:</td>
          <td class="glink"><select name="tk_user_rank">
            <option value="0" <?php if (!(strcmp("0", htmlentities($row_Recordset1['tk_user_rank'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $multilingual_dd_role_disabled; ?></option>
            <option value="1" <?php if (!(strcmp("1", htmlentities($row_Recordset1['tk_user_rank'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $multilingual_dd_role_readonly; ?></option>
            <option value="2" <?php if (!(strcmp("2", htmlentities($row_Recordset1['tk_user_rank'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $multilingual_dd_role_guest; ?></option>
            <option value="3" <?php if (!(strcmp("3", htmlentities($row_Recordset1['tk_user_rank'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $multilingual_dd_role_general; ?></option>
            <option value="4" <?php if (!(strcmp("4", htmlentities($row_Recordset1['tk_user_rank'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $multilingual_dd_role_pm; ?></option>
            <option value="5" <?php if (!(strcmp("5", htmlentities($row_Recordset1['tk_user_rank'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $multilingual_dd_role_admin; ?></option>
          </select>          </td>
        </tr>
        <tr valign="baseline" >
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
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <?php } else { ?>
        <tr valign="baseline" style="display:none;">
          <td>&nbsp;</td>
          <td><input name="tk_user_rank" type="text" value="<?php echo $row_Recordset1['tk_user_rank'];?>"  /></td>
        </tr>
        <?php } ?>
        <tr valign="baseline">
          <td valign="top"><?php echo $multilingual_user_remark; ?>:</td>
          <td><textarea name="tk_user_remark" cols="50" rows="5"><?php echo htmlentities($row_Recordset1['tk_user_remark'], ENT_COMPAT, 'utf-8'); ?></textarea>
              <br />
              <span class="gray glink"> <?php echo $multilingual_user_tip_remark; ?></span> </td>
        </tr>
        <tr valign="baseline">
          <td>&nbsp;</td>
          <td><input name="submit" type="submit" value="<?php echo $multilingual_global_action_save; ?>" 
		<?php if( $_SESSION['MM_Username'] == $multilingual_dd_user_readonly){
	  echo "disabled='disabled'";
	  } ?>
		/>
              <label>
              <input name="button" type="button" id="button" onclick="javascript:history.go(-1)" value="<?php echo $multilingual_global_action_cancel; ?>" />
            </label></td>
        </tr>
      </table>
      <input type="hidden" name="MM_update" value="form1" />
      <input type="hidden" name="ID" value="<?php echo $row_Recordset1['uid']; ?>" />
    </form>
  </div>
  <div class="tab_b" id="tab_a2" style="display:none">
    <iframe id="frame_content" name="main_frame" frameborder="0" height="150px" width="100%" src="user_edit_password.php?UID=<?php echo $colname_Recordset1; ?>" scrolling="no"></iframe>
  </div>
  <p>&nbsp;</p>
</div>
<?php require('foot.php'); ?>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>