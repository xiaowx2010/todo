<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php 
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

$maxRows_DetailRS1 = 10;
$pageNum_DetailRS1 = 0;
if (isset($_GET['pageNum_DetailRS1'])) {
  $pageNum_DetailRS1 = $_GET['pageNum_DetailRS1'];
}
$startRow_DetailRS1 = $pageNum_DetailRS1 * $maxRows_DetailRS1;

$colname_DetailRS1 = "-1";
if (isset($_GET['recordID'])) {
  $colname_DetailRS1 = $_GET['recordID'];
}
mysql_select_db($database_tankdb, $tankdb);
$query_DetailRS1 = sprintf("SELECT * FROM tk_announcement inner join tk_user on tk_announcement.tk_anc_create=tk_user.uid  WHERE tk_announcement.AID = %s ORDER BY tk_anc_lastupdate DESC", GetSQLValueString($colname_DetailRS1, "int"));
$query_limit_DetailRS1 = sprintf("%s LIMIT %d, %d", $query_DetailRS1, $startRow_DetailRS1, $maxRows_DetailRS1);
$DetailRS1 = mysql_query($query_limit_DetailRS1, $tankdb) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);

if (isset($_GET['totalRows_DetailRS1'])) {
  $totalRows_DetailRS1 = $_GET['totalRows_DetailRS1'];
} else {
  $all_DetailRS1 = mysql_query($query_DetailRS1);
  $totalRows_DetailRS1 = mysql_num_rows($all_DetailRS1);
}
$totalPages_DetailRS1 = ceil($totalRows_DetailRS1/$maxRows_DetailRS1)-1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WSS - <?php echo $multilingual_announcement_view_title; ?></title>
<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php require('head.php'); ?>
<br />
<table align="center" class="fontsize-s input_task_table glink">
<tr>
<td>
	  <span class="float_left"><?php echo $multilingual_breadcrumb_anclist; ?></span>
	  <span class="ui-icon month_next float_left"></span>
	  <span class="float_left"><?php echo $multilingual_announcement_view_title; ?></span>
</td>
  <td align="right">
  <?php if ($_SESSION['MM_rank'] > "4") {  ?>
      <input name="" type="button" class="button" onclick="javascript:self.location='announcement_edit.php?editAID=<?php echo $row_DetailRS1['AID']; ?>';" value="<?php echo $multilingual_global_action_edit; ?>" /> 

      <input name="" type="button" class="button" onclick="javascript:if(confirm( '<?php 
	  echo $multilingual_global_action_delconfirm; ?>'))self.location='announcement_del.php?delAID=<?php echo $row_DetailRS1['AID']; ?>';" value="<?php echo $multilingual_global_action_del; ?>" 
	   <?php if( $_SESSION['MM_Username'] == $multilingual_dd_user_readonly){
	  echo "disabled='disabled'";
	  } ?> 
	  /> 
      <?php }  ?>  
	<input name="" type="button" onclick="javascript:history.go(-1)" value="<?php echo $multilingual_global_action_back; ?>"  class="button"  />
  </td>
</tr>
<tr>
    <td colspan="2"><span class="input_task_title margin-y" style="margin-top:0px;"><?php echo $multilingual_head_announcement; ?></span></td>
</tr>
  <tr>
    <td colspan="2"  class="font_big fontbold"><?php echo $row_DetailRS1['tk_anc_title']; ?></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <?php if($row_DetailRS1['tk_anc_text'] <> "&nbsp;"  && $row_DetailRS1['tk_anc_text'] <> "") { ?>
  <tr>
    <td colspan="2"  class="remark_bg breakwords"><?php 
	echo $row_DetailRS1['tk_anc_text']; 
	?></td>
  </tr>
  <?php } ?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $multilingual_announcement_publisher; ?>：<?php echo $row_DetailRS1['tk_display_name']; ?></td>
    <td><?php echo $multilingual_announcement_view_time; ?>:<?php echo $row_DetailRS1['tk_anc_lastupdate']; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">
	<span class="input_task_submit">
	
	<div class="float_right">
	 <?php if ($_SESSION['MM_rank'] > "4") {  ?>
      <input name="" type="button" class="button" onclick="javascript:self.location='announcement_edit.php?editAID=<?php echo $row_DetailRS1['AID']; ?>';" value="<?php echo $multilingual_global_action_edit; ?>" /> 

      <input name="" type="button" class="button" onclick="javascript:if(confirm( '<?php 
	  echo $multilingual_global_action_delconfirm; ?>'))self.location='announcement_del.php?delAID=<?php echo $row_DetailRS1['AID']; ?>';" value="<?php echo $multilingual_global_action_del; ?>" 
	   <?php if( $_SESSION['MM_Username'] == $multilingual_dd_user_readonly){
	  echo "disabled='disabled'";
	  } ?> 
	  /> 
      <?php }  ?>    
	<input name="" type="button" onclick="javascript:history.go(-1)" value="<?php echo $multilingual_global_action_back; ?>"  class="button"  />
	</div>
	</span>	</td>
  </tr>
</table>
<p>&nbsp;</p>
<?php require('foot.php'); ?>
</body>
</html><?php
mysql_free_result($DetailRS1);
?>