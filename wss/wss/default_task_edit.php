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

$currentPage = $_SERVER["PHP_SELF"];

$colname_Recordset_task = "-1";
if (isset($_GET['editID'])) {
  $colname_Recordset_task = $_GET['editID'];
}

$pagetabs = "mtask";
if (isset($_GET['pagetab'])) {
  $pagetabs = $_GET['pagetab'];
}

$multilingual_breadcrumb_tasklisturl;
if($pagetabs=="mtask"){
$multilingual_breadcrumb_tasklisturl = "<a href='index.php?select=&select_project=&select_year=".date("Y")."&textfield=".date("m")."&select3=-1&select4=".$_SESSION['MM_uid']."&select_prt=&select_temp=&inputtitle=&select1=-1&select2=%&create_by=%&select_type=&inputid=&inputtag='>". $multilingual_user_mytask."</a>";
}else if ($pagetabs=="ftask") {
$multilingual_breadcrumb_tasklisturl = "<a href='index.php?select=&select_project=&select_year=".date("Y")."&textfield=".date("m")."&select3=-1&select4=%&select_prt=&select_temp=&inputtitle=&select1=-1&select2=".$_SESSION['MM_uid']."&create_by=%&select_type=&inputid=&inputtag=&pagetab=ftask'>". $multilingual_default_fromme."</a>";
}else if ($pagetabs=="ctask"){
$multilingual_breadcrumb_tasklisturl = "<a href='index.php?select=&select_project=&select_year=".date("Y")."&textfield=".date("m")."&select3=-1&select4=%&select_prt=&select_temp=&inputtitle=&select1=-1&select2=%&create_by=".$_SESSION['MM_uid']."&select_type=&inputid=&inputtag=&pagetab=ctask'>". $multilingual_default_createme."</a>";
} else if ($pagetabs=="etask"){
$multilingual_breadcrumb_tasklisturl = "<a href='index.php?select=&select_project=&select_year=--&textfield=--&select3=-1&select4=%&select_prt=&select_temp=&select_exam=".$multilingual_dd_status_exam."&inputtitle=&select1=-1&select2=".$_SESSION['MM_uid']."&select_type=&inputid=&inputtag=&pagetab=etask'>". $multilingual_exam_wait."</a>";
}  else if ($pagetabs=="alltask"){
$multilingual_breadcrumb_tasklisturl = "<a href='index.php?select=&select_project=&select_year=".date("Y")."&textfield=".date("m")."&select3=-1&select4=%&select_prt=&select_temp=&inputtitle=&select1=-1&select2=%&create_by=%&select_type=&inputid=&inputtag=&pagetab=alltask'>". $multilingual_default_alltask."</a>";
}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_task = sprintf("SELECT *, 
tk_user1.tk_display_name as tk_display_name1, 
tk_user2.tk_display_name as tk_display_name2, 
tk_user3.tk_display_name as tk_display_name3, 
tk_user4.tk_display_name as tk_display_name4,
tk_project.id as proid    
FROM tk_task 
inner join tk_task_tpye on tk_task.csa_type=tk_task_tpye.id 
inner join tk_status on tk_task.csa_remark2=tk_status.id 
inner join tk_user as tk_user1 on tk_task.csa_to_user=tk_user1.uid 
inner join tk_user as tk_user2 on tk_task.csa_from_user=tk_user2.uid 
inner join tk_user as tk_user3 on tk_task.csa_create_user=tk_user3.uid 
inner join tk_user as tk_user4 on tk_task.csa_last_user=tk_user4.uid 
inner join tk_project on tk_task.csa_project=tk_project.id 
WHERE TID = %s", GetSQLValueString($colname_Recordset_task, "int"));
$Recordset_task = mysql_query($query_Recordset_task, $tankdb) or die(mysql_error());
$row_Recordset_task = mysql_fetch_assoc($Recordset_task);
$totalRows_Recordset_task = mysql_num_rows($Recordset_task);


$taskid = $_GET['editID'];

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_sumlog = sprintf("SELECT sum(csa_tb_manhour) as sum_hour FROM tk_task_byday WHERE csa_tb_backup1= %s", GetSQLValueString($taskid, "int"));
$Recordset_sumlog = mysql_query($query_Recordset_sumlog, $tankdb) or die(mysql_error());
$row_Recordset_sumlog = mysql_fetch_assoc($Recordset_sumlog);

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_countlog = sprintf("SELECT COUNT(*) as count_log FROM tk_task_byday WHERE csa_tb_backup1= %s", GetSQLValueString($taskid, "int"));
$Recordset_countlog = mysql_query($query_Recordset_countlog, $tankdb) or die(mysql_error());
$row_Recordset_countlog = mysql_fetch_assoc($Recordset_countlog);

$maxRows_Recordset_comment = 10;
$pageNum_Recordset_comment = 0;
if (isset($_GET['pageNum_Recordset_comment'])) {
  $pageNum_Recordset_comment = $_GET['pageNum_Recordset_comment'];
}
$startRow_Recordset_comment = $pageNum_Recordset_comment * $maxRows_Recordset_comment;

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_comment = sprintf("SELECT * FROM tk_comment 
inner join tk_user on tk_comment.tk_comm_user =tk_user.uid 
								 WHERE tk_comm_pid = %s AND tk_comm_type = 1 
								
								ORDER BY tk_comm_lastupdate DESC", 
								GetSQLValueString($colname_Recordset_task, "text")
								);
$query_limit_Recordset_comment = sprintf("%s LIMIT %d, %d", $query_Recordset_comment, $startRow_Recordset_comment, $maxRows_Recordset_comment);
$Recordset_comment = mysql_query($query_limit_Recordset_comment, $tankdb) or die(mysql_error());
$row_Recordset_comment = mysql_fetch_assoc($Recordset_comment);

if (isset($_GET['totalRows_Recordset_comment'])) {
  $totalRows_Recordset_comment = $_GET['totalRows_Recordset_comment'];
} else {
  $all_Recordset_comment = mysql_query($query_Recordset_comment);
  $totalRows_Recordset_comment = mysql_num_rows($all_Recordset_comment);
}
$totalPages_Recordset_comment = ceil($totalRows_Recordset_comment/$maxRows_Recordset_comment)-1;

$queryString_Recordset_comment = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_comment") == false && 
        stristr($param, "totalRows_Recordset_comment") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_comment = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_comment = sprintf("&totalRows_Recordset_comment=%d%s", $totalRows_Recordset_comment, $queryString_Recordset_comment);

$maxRows_Recordset_actlog = 10;
$pageNum_Recordset_actlog = 0;
if (isset($_GET['pageNum_Recordset_actlog'])) {
  $pageNum_Recordset_actlog = $_GET['pageNum_Recordset_actlog'];
}
$startRow_Recordset_actlog = $pageNum_Recordset_actlog * $maxRows_Recordset_actlog;

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_actlog = sprintf("SELECT * FROM tk_log 
inner join tk_user on tk_log.tk_log_user =tk_user.uid 
								 WHERE tk_log_type = %s AND tk_log_class = 1 
								
								ORDER BY tk_log_time DESC", 
								GetSQLValueString($colname_Recordset_task, "text")
								);
$query_limit_Recordset_actlog = sprintf("%s LIMIT %d, %d", $query_Recordset_actlog, $startRow_Recordset_actlog, $maxRows_Recordset_actlog);
$Recordset_actlog = mysql_query($query_limit_Recordset_actlog, $tankdb) or die(mysql_error());
$row_Recordset_actlog = mysql_fetch_assoc($Recordset_actlog);

if (isset($_GET['totalRows_Recordset_actlog'])) {
  $totalRows_Recordset_actlog = $_GET['totalRows_Recordset_actlog'];
} else {
  $all_Recordset_actlog = mysql_query($query_Recordset_actlog);
  $totalRows_Recordset_actlog = mysql_num_rows($all_Recordset_actlog);
}
$totalPages_Recordset_actlog = ceil($totalRows_Recordset_actlog/$maxRows_Recordset_actlog)-1;

$queryString_Recordset_actlog = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_actlog") == false && 
        stristr($param, "totalRows_Recordset_actlog") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_actlog = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_actlog = sprintf("&totalRows_Recordset_actlog=%d%s", $totalRows_Recordset_actlog, $queryString_Recordset_actlog);

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_sumtotal = sprintf("SELECT 
							COUNT(*) as count_task   
							FROM tk_status  							
							WHERE task_status_backup2 = '1'"
								);
$Recordset_sumtotal = mysql_query($query_Recordset_sumtotal, $tankdb) or die(mysql_error());
$row_Recordset_sumtotal = mysql_fetch_assoc($Recordset_sumtotal);
$exam_totaltask=$row_Recordset_sumtotal['count_task'];

//for wbs!

$maxRows_Recordset_subtask = 15;
$pageNum_Recordset_subtask = 0;
if (isset($_GET['pageNum_Recordset_subtask'])) {
  $pageNum_Recordset_subtask = $_GET['pageNum_Recordset_subtask'];
}
$startRow_Recordset_subtask = $pageNum_Recordset_subtask * $maxRows_Recordset_subtask;

//$colname_Recordset_subtask = $row_DetailRS1['tk_user_login'];

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_subtask = sprintf("SELECT * 
							FROM tk_task 
							inner join tk_task_tpye on tk_task.csa_type=tk_task_tpye.id								
							inner join tk_user on tk_task.csa_to_user=tk_user.uid 
							inner join tk_status on tk_task.csa_remark2=tk_status.id 
							WHERE tk_task.csa_remark4 = %s ORDER BY csa_last_update DESC", 
								GetSQLValueString($colname_Recordset_task, "text")
								);
$query_limit_Recordset_subtask = sprintf("%s LIMIT %d, %d", $query_Recordset_subtask, $startRow_Recordset_subtask, $maxRows_Recordset_subtask);
$Recordset_subtask = mysql_query($query_limit_Recordset_subtask, $tankdb) or die(mysql_error());
$row_Recordset_subtask = mysql_fetch_assoc($Recordset_subtask);

if (isset($_GET['totalRows_Recordset_subtask'])) {
  $totalRows_Recordset_subtask = $_GET['totalRows_Recordset_subtask'];
} else {
  $all_Recordset_subtask = mysql_query($query_Recordset_subtask);
  $totalRows_Recordset_subtask = mysql_num_rows($all_Recordset_subtask);
}
$totalPages_Recordset_subtask = ceil($totalRows_Recordset_subtask/$maxRows_Recordset_subtask)-1;

$queryString_Recordset_subtask = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_subtask") == false && 
        stristr($param, "totalRows_Recordset_subtask") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_subtask = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_subtask = sprintf("&totalRows_Recordset_subtask=%d%s", $totalRows_Recordset_subtask, $queryString_Recordset_subtask);


if ($row_Recordset_task['csa_remark6'] == "-1" ){
$wbs_id = "1";
} else {
$wbs_id = $row_Recordset_task['csa_remark6'];
}


$wbsID = $wbs_id + 1;

if ($row_Recordset_task['csa_remark6'] == "-1"){
$wbssum = $row_Recordset_task['TID'].">".$wbsID;
}else {
$wbssum = $row_Recordset_task['csa_remark5'].">".$row_Recordset_task['TID'].">".$wbsID;
}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_sumplan = "SELECT round(sum(csa_plan_hour),1) as sum_plan_hour FROM tk_task 
inner join tk_task_tpye on tk_task.csa_type=tk_task_tpye.id 
WHERE task_tpye NOT LIKE '$multilingual_dd_status_ca' AND csa_remark5 LIKE '$wbssum%'";
$Recordset_sumplan = mysql_query($query_Recordset_sumplan, $tankdb) or die(mysql_error());
$row_Recordset_sumplan = mysql_fetch_assoc($Recordset_sumplan);

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_sumsublog = "SELECT round(sum(csa_tb_manhour),1) as sum_sublog FROM tk_task  
inner join tk_task_byday on tk_task.TID=tk_task_byday.csa_tb_backup1 
WHERE csa_remark5 LIKE '$wbssum%'";
$Recordset_sumsublog = mysql_query($query_Recordset_sumsublog, $tankdb) or die(mysql_error());
$row_Recordset_sumsublog = mysql_fetch_assoc($Recordset_sumsublog);

$pattaskid = $row_Recordset_task['csa_remark4'];

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_pattask = "SELECT * FROM tk_task inner join tk_task_tpye on tk_task.csa_type=tk_task_tpye.id WHERE TID = '$pattaskid'";
$Recordset_pattask = mysql_query($query_Recordset_pattask, $tankdb) or die(mysql_error());
$row_Recordset_pattask = mysql_fetch_assoc($Recordset_pattask);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WSS - <?php echo $multilingual_tasklog_title; ?></title>
<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/lhgdialog.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/jquery.js"></script>
<script type="text/javascript" src="srcipt/js.js"></script>
<script type="text/javascript" src="srcipt/lhgcore.js"></script>
<script type="text/javascript" src="srcipt/lhgdialog.js"></script>
<script type="text/javascript" language="javascript">    
<!--    
 
function TuneHeight()    
{    
var frm = document.getElementById("frame_content");    
var subWeb = document.frames ? document.frames["main_frame"].document : frm.contentDocument;    
if(frm != null && subWeb != null)    
{ frm.height = subWeb.body.scrollHeight;}    
}    
//-->    
</script>
<script type="text/javascript">
function eduser()
{
    J.dialog.get({ id: "test", title: '<?php echo $multilingual_tasklog_changeuser; ?>', width: 260, height: 140, page: "default_task_edituser.php?taskid=<?php echo $row_Recordset_task['TID']; ?>" });
}
function addcomm()
{
    J.dialog.get({ id: "test1", title: '<?php echo $multilingual_default_addcom; ?>', width: 600, height: 500, page: "comment_add.php?taskid=<?php echo $row_Recordset_task['TID']; ?>&type=1" });
}
function check()
{
    J.dialog.get({ id: "test2", title: '<?php echo $multilingual_exam_poptitle; ?>', width: 320, height: 260, page: "default_task_exam.php?taskid=<?php echo $row_Recordset_task['TID']; ?>" });
}
</script>
</head>

<body>
<?php require('head.php'); ?>


<br />
  <table align="center" class="fontsize-s input_task_table glink">
    <tr>
      <td width="40%" nowrap="nowrap">
	  <span class="float_left"><?php echo $multilingual_breadcrumb_tasklisturl; ?></span>
	  <span class="ui-icon month_next float_left"></span>
	  <span class="float_left" style="margin-right:100px"><?php echo $multilingual_tasklog_title; ?></span>
	  
	  
	  <span class="float_left" >
	  <?php echo $multilingual_default_taskproject; ?> <a href="project_view.php?recordID=<?php echo $row_Recordset_task['id']; ?>" ><?php echo $row_Recordset_task['project_name']; ?></a>	  </span>
	  <span class="ui-icon month_next float_left"></span>
	  
	   <?php if ($row_Recordset_task['csa_remark4'] <> -1) { ?>
	  
	  
	  <span class="float_left">
	   <?php echo $multilingual_default_task_parent; ?>: 
	  <a href="default_task_edit.php?editID=<?php echo $row_Recordset_pattask['TID']; ?>" ><b>[<?php echo $row_Recordset_pattask['task_tpye']; ?>]</b> <?php echo $row_Recordset_pattask['csa_text']; ?></a>
	  </span>
	  <?php } else {
	 echo $multilingual_subtask_root;
	  } ?>
	  <span class="clearboth"></span>

	
	  </td>
      <td  nowrap="nowrap" align="right" class="glink">
	  <span class="gray"><?php echo $multilingual_default_taskid; ?><?php echo $row_Recordset_task['TID']; ?></span> 
	  
	  <?php if ($exam_totaltask > "0") { ?>
	  <?php if (($row_Recordset_task['csa_from_user'] == $_SESSION['MM_uid'] && $_SESSION['MM_rank'] > "1") || $_SESSION['MM_rank'] > "4"  ) { ?>
	   <input name="button3" type="button" id="button3" onclick="check();" value="<?php echo $multilingual_exam_title; ?>"  class="button" />
	  <?php }  ?> 
	  <?php }  ?> 
	  <?php if($_SESSION['MM_rank'] > "2") { ?>
	  <input name="" type="button" class="button" onclick="javascript:self.location='default_task_add.php?taskID=<?php echo $colname_Recordset_task; ?>&projectID=<?php echo $row_Recordset_task['proid']; ?>&wbsID=<?php echo $wbsID; ?>';" value="<?php echo $multilingual_project_newtask; ?>(<?php echo $multilingual_global_break; ?>)" />
	  <?php }  ?> 
	  <?php if (($row_Recordset_task['csa_create_user'] == $_SESSION['MM_uid'] && $_SESSION['MM_rank'] > "1") || $_SESSION['MM_rank'] > "4"  ) { ?>
    
	  <input name="button1" type="button" id="button1" onClick="javascript:self.location='default_task_plan.php?editID=<?php echo $row_Recordset_task['TID']; ?>';" value="<?php echo $multilingual_global_action_edit; ?>"  class="button" />
      <?php }  ?> 

	   <?php if ($_SESSION['MM_rank'] > "4") {  ?>
      
	  <input name="button2" type="button" id="button2" onClick="javascript:if(confirm( '<?php 
	 if($row_Recordset_countlog['count_log'] == "0"){  
	  echo $multilingual_global_action_delconfirm;
	  } else { echo $multilingual_global_action_delconfirm2;} ?>'))self.location= 'task_del.php?delID=<?php echo $row_Recordset_task['TID']; ?>';" value="<?php echo $multilingual_global_action_del; ?>" class="button" 
	  <?php if( $_SESSION['MM_Username'] == $multilingual_dd_user_readonly){
	  echo "disabled='disabled'";
	  } ?>
	  />
      <?php }  ?> 
	  
	  <input name="button" type="button" id="button" onclick="javascript:history.go(-1)" value="<?php echo $multilingual_global_action_back; ?>"  class="button"  /></td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" nowrap="nowrap"><span class="input_task_title margin-y" style="margin-top:0px;"><?php echo $multilingual_default_task_section3; ?></span></td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" nowrap="nowrap"><span class="font_big18 fontbold breakwords">[<?php echo $row_Recordset_task['task_tpye']; ?>] <?php echo htmlentities($row_Recordset_task['csa_text'], ENT_COMPAT, 'utf-8'); ?></span></td>
    </tr>
    <tr valign="baseline">
      <td  nowrap="nowrap"><div class="float_left fontbold"><?php echo $multilingual_default_task_status; ?>:</div> <div class="float_left view_task_status"><?php echo $row_Recordset_task['task_status_display']; ?></div></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td  nowrap="nowrap">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td  nowrap="nowrap"><span class="fontbold"><?php echo $multilingual_default_task_to; ?>:</span> <a href="user_view.php?recordID=<?php echo $row_Recordset_task['csa_to_user']; ?>"><?php echo $row_Recordset_task['tk_display_name1']; ?></a> &nbsp;&nbsp;&nbsp;


<?php if($row_Recordset_countlog['count_log'] == "0" && $_SESSION['MM_uid'] == $row_Recordset_task['csa_to_user'] && $_SESSION['MM_rank'] > "1") { ?>
	  <a href="#" onclick="eduser();">[<?php echo $multilingual_tasklog_changeuser; ?>]</a>
<?php } else { ?>
 <b title="<?php echo $multilingual_tasktype_lock; ?>">[?]</b>	 </td>
<?php }  ?>


      <td width="25%"><?php if($row_Recordset_task['test02'] <> " " && $row_Recordset_task['test02'] <> "" ) { ?>
	 
	  <b><?php echo $multilingual_default_tasktag; ?>:</b> <?php echo $row_Recordset_task['test02']; ?>
	  <?php } ?></td>
    </tr>
    <tr valign="baseline">
      <td  nowrap="nowrap"><span class="fontbold"><?php echo $multilingual_default_task_from; ?>:</span> <a href="user_view.php?recordID=<?php echo $row_Recordset_task['csa_from_user']; ?>"><?php echo $row_Recordset_task['tk_display_name2']; ?></a></td>
      <td><span class="fontbold"><?php echo $multilingual_default_task_priority; ?>:</span> 
	  <?php
switch ($row_Recordset_task['csa_priority'])
{
case 5:
  echo $multilingual_dd_priority_p5;
  break;
case 4:
  echo $multilingual_dd_priority_p4;
  break;
case 3:
  echo $multilingual_dd_priority_p3;
  break;
case 2:
  echo $multilingual_dd_priority_p2;
  break;
case 1:
  echo $multilingual_dd_priority_p1;
  break;
}
?>
	  </td>
    </tr>
    <tr valign="baseline">
      <td  nowrap="nowrap"><span class="fontbold"><?php echo $multilingual_global_action_create; ?>:</span> <a href="user_view.php?recordID=<?php echo $row_Recordset_task['csa_create_user']; ?>"><?php echo $row_Recordset_task['tk_display_name3']; ?></a></td>
      <td><span class="fontbold"><?php echo $multilingual_default_tasklevel; ?>:</span> 
	  <?php
switch ($row_Recordset_task['csa_temp'])
{
case 5:
  echo $multilingual_dd_level_l5;
  break;
case 4:
  echo $multilingual_dd_level_l4;
  break;
case 3:
  echo $multilingual_dd_level_l3;
  break;
case 2:
  echo $multilingual_dd_level_l2;
  break;
case 1:
  echo $multilingual_dd_level_l1;
  break;
}
?>
	  </td>
    </tr>
    <tr valign="baseline">
      <td  nowrap="nowrap">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td  nowrap="nowrap"><span class="fontbold"><?php echo $multilingual_tasklog_cost; ?>:</span> 
	  <?php if($row_Recordset_sumlog["sum_hour"] == null){
	  $sum_hour = 0;
	  } else {
	  $sum_hour = $row_Recordset_sumlog["sum_hour"];
	  }
	  echo $sum_hour;?> <?php echo $multilingual_global_hour; ?></td>
      <td><span class="fontbold"><?php echo $multilingual_default_task_planstart; ?>:</span> <?php echo $row_Recordset_task['csa_plan_st']; ?>       </td>
    </tr>
    <tr valign="baseline">
      <td  nowrap="nowrap"><span class="fontbold"><?php echo $multilingual_default_task_planhour; ?>:</span> 
	  <?php if($row_Recordset_task['csa_plan_hour'] == null){
	  $plan_hour = 0;
	  } else {
	  $plan_hour = $row_Recordset_task['csa_plan_hour'];
	  }
	  echo $plan_hour;?>
 <?php echo $multilingual_global_hour; ?></td>
      <td><span class="fontbold"><?php echo $multilingual_default_task_planend; ?>:</span> <?php echo $row_Recordset_task['csa_plan_et']; ?>        </td>
    </tr>
    <tr valign="baseline">
      <td  nowrap="nowrap"><?php 
	  $over_hour=$plan_hour - $sum_hour;
	  if($row_Recordset_task['csa_plan_hour'] == null){
	  echo null;
	  } else if ($over_hour < 0) {
	  echo "<span class='red'>".$multilingual_tasklog_over.": ".-$over_hour." ".$multilingual_global_hour."</span>";
	  } else if ($over_hour >= 0) {
	  echo "<span class='fontbold'>".$multilingual_tasklog_live.":</span> ".$over_hour." ".$multilingual_global_hour;
	  }
	  ?></td>
      <td><?php 
	  $live_days = (strtotime($row_Recordset_task['csa_plan_et']) - strtotime(date("Y-m-d")))/86400;
	  if ($live_days < 0){
	  echo $multilingual_tasklog_overday;
	  } else {
	  echo "<span class='fontbold'>".$multilingual_tasklog_liveday.":</span> ".$live_days." ".$multilingual_tasklog_day;
	  }
	  ?>
	  </td>
    </tr>
	<?php if ($row_Recordset_task['csa_remark1'] <> "&nbsp;" && $row_Recordset_task['csa_remark1'] <> "") { ?>
    <tr valign="baseline">
      <td colspan="2" nowrap="nowrap"><span class="input_task_title  margin-y"><?php echo $multilingual_default_task_description; ?></span></td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" >
	<?php 
	echo $row_Recordset_task['csa_remark1']; 
	?>	  
	</td>
    </tr>
	<?php } ?>
	<tr valign="baseline">
      <td colspan="2" align="right">
	  <?php if($_SESSION['MM_rank'] > "1") { ?>
	  <a onclick="addcomm();" class="mouse_hover">[<?php echo $multilingual_default_addcom; ?>]</a>
	 <?php } ?> <a name="comment"></a>
	  </td>
    </tr>
	<?php if($totalRows_Recordset_comment > 0){ ?>
	<tr valign="baseline">
      <td colspan="2" nowrap="nowrap"><span class="input_task_title  margin-y" style="margin-top:0px;"><?php echo $multilingual_default_comment; ?></span></td>
    </tr>
		<?php do { ?>
		<tr valign="baseline">
      <td colspan="2" nowrap="nowrap">
	  <div class="float_left">
	  <b>
	  <a href="user_view.php?recordID=<?php echo $row_Recordset_comment['tk_comm_user']; ?>"><?php echo $row_Recordset_comment['tk_display_name']; ?></a> 
	  <?php echo $multilingual_default_by; ?>
	  <?php echo $row_Recordset_comment['tk_comm_lastupdate']; ?> 
	  <?php echo $multilingual_default_at; ?>
	  </b>
	  </div>
	  <div class="float_right">
	  <?php if ($_SESSION['MM_rank'] > "4" || ($row_Recordset_comment['tk_comm_user'] == $_SESSION['MM_uid'] && $_SESSION['MM_rank'] > "1")) {  ?>
	  <?php
	  $coid =$row_Recordset_comment['coid'];
	  $editcomment_row = "
<script type='text/javascript'>
	  function editcomm$coid()
{
    J.dialog.get({ id: 'test3', title: '$multilingual_default_editcom', width: 600, height: 500, page: 'comment_edit.php?editcoID=$coid' });
}
</script>";

echo $editcomment_row;
?>
	  <a onclick="editcomm<?php echo $coid; ?>();" class="mouse_hover">
	  <?php echo $multilingual_global_action_edit; ?></a>
	  <?php if ($_SESSION['MM_Username'] <> $multilingual_dd_user_readonly) {  ?>
	   <a  class="mouse_hover" 
	  onclick="javascript:if(confirm( '<?php 
	  echo $multilingual_global_action_delconfirm; ?>'))self.location='comment_del.php?delID=<?php echo $row_Recordset_comment['coid']; ?>&taskID=<?php echo $row_Recordset_task['TID']; ?>';"
	  ><?php echo $multilingual_global_action_del; ?></a>
	  <?php } else {  
	   echo $multilingual_global_action_del; 
	    }  ?>
	  <?php } ?>
	  </div>
	  </td>
    </tr>
    <tr valign="baseline" >
      <td colspan="2" class="comment_list">

	<?php 
	echo $row_Recordset_comment['tk_comm_title']; 
	?>
	
	
	</td>
    </tr>
	<?php
} while ($row_Recordset_comment = mysql_fetch_assoc($Recordset_comment));
  $rows = mysql_num_rows($Recordset_comment);
  if($rows > 0) {
      mysql_data_seek($Recordset_comment, 0);
	  $row_Recordset_comment = mysql_fetch_assoc($Recordset_comment);
  }
?>
	<tr valign="baseline">
      <td colspan="2" >
<table class="rowcon" border="0" align="center">
<tr>
<td>   <table border="0">
        <tr>
          <td><?php if ($pageNum_Recordset_comment > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset_comment=%d%s", $currentPage, 0, $queryString_Recordset_comment); ?>#comment"><?php echo $multilingual_global_first; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset_comment > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset_comment=%d%s", $currentPage, max(0, $pageNum_Recordset_comment - 1), $queryString_Recordset_comment); ?>#comment"><?php echo $multilingual_global_previous; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset_comment < $totalPages_Recordset_comment) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset_comment=%d%s", $currentPage, min($totalPages_Recordset_comment, $pageNum_Recordset_comment + 1), $queryString_Recordset_comment); ?>#comment"><?php echo $multilingual_global_next; ?></a>
              <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_Recordset_comment < $totalPages_Recordset_comment) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset_comment=%d%s", $currentPage, $totalPages_Recordset_comment, $queryString_Recordset_comment); ?>#comment"><?php echo $multilingual_global_last; ?></a>
              <?php } // Show if not last page ?></td>
        </tr>
      </table></td>
<td align="right">   <?php echo ($startRow_Recordset_comment + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset_comment + $maxRows_Recordset_comment, $totalRows_Recordset_comment) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset_comment ?>)&nbsp;&nbsp;&nbsp;&nbsp;</td>
</tr>
</table>
	</td>
    </tr>
	<?php } ?>
	<?php if($totalRows_Recordset_actlog > 0){ ?>
	<tr valign="baseline">
      <td colspan="2" nowrap="nowrap"><span class="input_task_title "><?php echo $multilingual_log_title; ?></span><a name="log"></td>
    </tr>
	
	<tr>
	<td colspan="2">
	 <table border="0" cellspacing="0" cellpadding="0" width="100%" >


  <?php do { ?>
<tr>
      <td class="comment_list">
	  <?php echo $row_Recordset_actlog['tk_log_time']; ?> <a href="user_view.php?recordID=<?php echo $row_Recordset_actlog['tk_log_user']; ?>"><?php echo $row_Recordset_actlog['tk_display_name']; ?></a> <?php echo $row_Recordset_actlog['tk_log_action']; ?>
	  <td>
</tr>	  
<?php
} while ($row_Recordset_actlog = mysql_fetch_assoc($Recordset_actlog));
  $rows = mysql_num_rows($Recordset_actlog);
  if($rows > 0) {
      mysql_data_seek($Recordset_actlog, 0);
	  $row_Recordset_actlog = mysql_fetch_assoc($Recordset_actlog);
  }
?>	
	
</table>
<table class="rowcon" border="0" align="center">
<tr>
<td>   <table border="0">
        <tr>
          <td><?php if ($pageNum_Recordset_actlog > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset_actlog=%d%s", $currentPage, 0, $queryString_Recordset_actlog); ?>#log"><?php echo $multilingual_global_first; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset_actlog > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset_actlog=%d%s", $currentPage, max(0, $pageNum_Recordset_actlog - 1), $queryString_Recordset_actlog); ?>#log"><?php echo $multilingual_global_previous; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset_actlog < $totalPages_Recordset_actlog) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset_actlog=%d%s", $currentPage, min($totalPages_Recordset_actlog, $pageNum_Recordset_actlog + 1), $queryString_Recordset_actlog); ?>#log"><?php echo $multilingual_global_next; ?></a>
              <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_Recordset_actlog < $totalPages_Recordset_actlog) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset_actlog=%d%s", $currentPage, $totalPages_Recordset_actlog, $queryString_Recordset_actlog); ?>#log"><?php echo $multilingual_global_last; ?></a>
              <?php } // Show if not last page ?></td>
        </tr>
      </table></td>
<td align="right">   <?php echo ($startRow_Recordset_actlog + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset_actlog + $maxRows_Recordset_actlog, $totalRows_Recordset_actlog) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset_actlog ?>)&nbsp;&nbsp;&nbsp;&nbsp;</td>
</tr>
</table> 	
	</td>
	</tr>
	<?php } ?>
	
	<?php if($totalRows_Recordset_subtask > 0){ ?>	
	<tr valign="baseline">
      <td colspan="2" nowrap="nowrap"><span class="input_task_title  margin-y">
	  <?php echo $multilingual_default_task_subtask; ?>
	  </span></td>
    </tr>
	
	<tr valign="baseline">
      <td >
	  <span class="fontbold">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $multilingual_subtask_cost; ?>:</span> 
	  <?php if($row_Recordset_sumsublog['sum_sublog'] == null){
	  $sum_subhour = 0;
	  } else {
	  $sum_subhour = $row_Recordset_sumsublog['sum_sublog'];
	  }
	  echo $sum_subhour;?>
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  <span class="fontbold"><?php echo $multilingual_subtask_plan; ?>:</span> 
	  <?php if($row_Recordset_sumplan['sum_plan_hour'] == null){
	  $plan_subhour = 0;
	  } else {
	  $plan_subhour = $row_Recordset_sumplan['sum_plan_hour'];
	  }
	  echo $plan_subhour;?>
 <?php echo $multilingual_global_hour; ?>
	  </td>
	  <td align="right">
	  <?php if($_SESSION['MM_rank'] > "2") { ?>
	  <input name="" type="button" class="button" onclick="javascript:self.location='default_task_add.php?taskID=<?php echo $colname_Recordset_task; ?>&projectID=<?php echo $row_Recordset_task['proid']; ?>&wbsID=<?php echo $wbsID; ?>';" value="<?php echo $multilingual_project_newtask; ?>(<?php echo $multilingual_global_break; ?>)" />
	  <?php }  ?> 
	  </td>
	  </tr>
	

	<tr valign="baseline">
      <td colspan="2" >
	  <div class="taskdiv"> 
    <table border="0" cellspacing="0" cellpadding="0" align="center" class="maintable">
<thead class="toptable">
        
        <tr>
          <th><?php echo $multilingual_default_task_id; ?></th>
          <th><?php echo $multilingual_default_task_title; ?></th>
          <th><?php echo $multilingual_default_task_to; ?></th>
          <th><?php echo $multilingual_default_task_status; ?></th>
          <th><?php echo $multilingual_default_task_planstart; ?></th>
          <th><?php echo $multilingual_default_task_planend; ?></th>
          <th><?php echo $multilingual_default_task_priority; ?></th>
          <th><?php echo $multilingual_default_task_temp; ?></th>
        </tr>
        </thead>
        
        <?php do { ?>
          <tr>
            <td><?php echo $row_Recordset_subtask['TID']; ?></td>
            <td class="task_title">
			<div  class="text_overflow_150 task_title"  title="<?php echo $row_Recordset_subtask['csa_text']; ?>">
			<a href="default_task_edit.php?editID=<?php echo $row_Recordset_subtask['TID']; ?>" >
			<b>[<?php echo $row_Recordset_subtask['task_tpye']; ?>]</b> <?php echo $row_Recordset_subtask['csa_text']; ?>			</a>			</div></td>
            <td ><a href="user_view.php?recordID=<?php echo $row_Recordset_subtask['csa_to_user']; ?>"><?php echo $row_Recordset_subtask['tk_display_name']; ?></a></td>
            <td><?php echo $row_Recordset_subtask['task_status_display']; ?></td>
            <td><?php echo $row_Recordset_subtask['csa_plan_st']; ?></td>
            <td><?php echo $row_Recordset_subtask['csa_plan_et']; ?></td>
            <td><?php
switch ($row_Recordset_subtask['csa_priority'])
{
case 5:
  echo $multilingual_dd_priority_p5;
  break;
case 4:
  echo $multilingual_dd_priority_p4;
  break;
case 3:
  echo $multilingual_dd_priority_p3;
  break;
case 2:
  echo $multilingual_dd_priority_p2;
  break;
case 1:
  echo $multilingual_dd_priority_p1;
  break;
}
?>
			</td>
            <td>
			<?php
switch ($row_Recordset_subtask['csa_temp'])
{
case 5:
  echo $multilingual_dd_level_l5;
  break;
case 4:
  echo $multilingual_dd_level_l4;
  break;
case 3:
  echo $multilingual_dd_level_l3;
  break;
case 2:
  echo $multilingual_dd_level_l2;
  break;
case 1:
  echo $multilingual_dd_level_l1;
  break;
}
?>
			</td>
          </tr>
          <?php } while ($row_Recordset_subtask = mysql_fetch_assoc($Recordset_subtask)); ?>
      </table>
      </div>

     
      <table class="rowcon" border="0" align="center">
<tr>
<td>   <table border="0">
        <tr>
          <td><?php if ($pageNum_Recordset_subtask > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset_subtask=%d%s", $currentPage, 0, $queryString_Recordset_subtask); ?>#task"><?php echo $multilingual_global_first; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset_subtask > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset_subtask=%d%s", $currentPage, max(0, $pageNum_Recordset_subtask - 1), $queryString_Recordset_subtask); ?>#task"><?php echo $multilingual_global_previous; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset_subtask < $totalPages_Recordset_subtask) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset_subtask=%d%s", $currentPage, min($totalPages_Recordset_subtask, $pageNum_Recordset_subtask + 1), $queryString_Recordset_subtask); ?>#task"><?php echo $multilingual_global_next; ?></a>
              <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_Recordset_subtask < $totalPages_Recordset_subtask) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset_subtask=%d%s", $currentPage, $totalPages_Recordset_subtask, $queryString_Recordset_subtask); ?>#task"><?php echo $multilingual_global_last; ?></a>
              <?php } // Show if not last page ?></td>
        </tr>
      </table></td>
<td align="right">   <?php echo ($startRow_Recordset_subtask + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset_subtask + $maxRows_Recordset_subtask, $totalRows_Recordset_subtask) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset_subtask ?>)&nbsp;&nbsp;&nbsp;&nbsp;</td>
</tr>
</table>		</td>
    </tr>
	<?php } ?>
	
    <tr valign="baseline">
      <td colspan="2" nowrap="nowrap"><span class="input_task_title "><?php echo $multilingual_default_task_section5; ?></span></td>
    </tr>    
    <tr valign="baseline">
      <td colspan="2" nowrap="nowrap">

      
<iframe id="frame_content" name="main_frame" frameborder="0" height="" width="100%" src="default_task_calendar.php?taskid=<?php echo $row_Recordset_task['TID']; ?>&userid=<?php echo $row_Recordset_task['csa_to_user']; ?>&projectid=<?php echo $row_Recordset_task['csa_project']; ?>&tasktype=<?php echo $row_Recordset_task['csa_type']; ?>" onLoad="TuneHeight()" scrolling="no"></iframe>      </td>
    </tr>
	<tr valign="baseline">
      <td colspan="2" nowrap="nowrap">
      <span class="input_task_submit">
	  
      <div class="float_right">
	  <?php if ($exam_totaltask > "0") { ?>
	   <?php if (($row_Recordset_task['csa_from_user'] == $_SESSION['MM_uid'] && $_SESSION['MM_rank'] > "1") || $_SESSION['MM_rank'] > "4"  ) { ?>
	   <input name="button3" type="button" id="button3" onclick="check();" value="<?php echo $multilingual_exam_title; ?>"  class="button" />
	  <?php }  ?> 
	  <?php }  ?> 
	  <?php if($_SESSION['MM_rank'] > "2") { ?>
	  <input name="" type="button" class="button" onclick="javascript:self.location='default_task_add.php?taskID=<?php echo $colname_Recordset_task; ?>&projectID=<?php echo $row_Recordset_task['proid']; ?>&wbsID=<?php echo $wbsID; ?>';" value="<?php echo $multilingual_project_newtask; ?>(<?php echo $multilingual_global_break; ?>)" />
	  <?php }  ?> 
      <?php if (($row_Recordset_task['csa_create_user'] == $_SESSION['MM_uid'] && $_SESSION['MM_rank'] > "1") || $_SESSION['MM_rank'] > "4"  ) { ?>
     
	  <input name="button1" type="button" id="button1" onClick="javascript:self.location='default_task_plan.php?editID=<?php echo $row_Recordset_task['TID']; ?>';" value="<?php echo $multilingual_global_action_edit; ?>"  class="button" />
      <?php }  ?> 

	   <?php if ($_SESSION['MM_rank'] > "4") {  ?>
      
	  <input name="button2" type="button" id="button2" onClick="javascript:if(confirm( '<?php 
	 if($row_Recordset_countlog['count_log'] == "0"){  
	  echo $multilingual_global_action_delconfirm;
	  } else { echo $multilingual_global_action_delconfirm2;} ?>'))self.location= 'task_del.php?delID=<?php echo $row_Recordset_task['TID']; ?>';" value="<?php echo $multilingual_global_action_del; ?>" class="button" 
	  <?php if( $_SESSION['MM_Username'] == $multilingual_dd_user_readonly){
	  echo "disabled='disabled'";
	  } ?>
	  />
      <?php }  ?> 
	  
	  <input name="button" type="button" id="button" onclick="javascript:history.go(-1)" value="<?php echo $multilingual_global_action_back; ?>"  class="button"  />
</div>
      </span><br /><br /><br /><br />      </td>
    </tr>
  </table>

<p>&nbsp;</p>
<?php require('foot.php'); ?>
</body>
</html>
<?php
mysql_free_result($Recordset_task);
?>