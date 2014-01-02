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

$currentPage = $_SERVER["PHP_SELF"];

$pagetabs = "allprj";
if (isset($_GET['pagetab'])) {
  $pagetabs = $_GET['pagetab'];
}

$multilingual_breadcrumb_prjlisturl;
if($pagetabs=="mprj"){
$multilingual_breadcrumb_prjlisturl = "<a href='project.php'>". $multilingual_project_myprj."</a>";
}else if ($pagetabs=="jprj") {
$multilingual_breadcrumb_prjlisturl = "<a href='project.php?pagetab=jprj'>". $multilingual_project_jprj."</a>";
}else if ($pagetabs=="allprj"){
$multilingual_breadcrumb_prjlisturl = "<a href='project.php?pagetab=allprj'>". $multilingual_project_allprj."</a>";
} 

$maxRows_DetailRS1 = 25;
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
$query_DetailRS1 = sprintf("SELECT * FROM tk_project 
inner join tk_user on tk_project.project_to_user=tk_user.uid 
inner join tk_status_project on tk_project.project_status=tk_status_project.psid 
WHERE tk_project.id = %s", GetSQLValueString($colname_DetailRS1, "int"));
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

$maxRows_Recordset_task = 15;
$pageNum_Recordset_task = 0;
if (isset($_GET['pageNum_Recordset_task'])) {
  $pageNum_Recordset_task = $_GET['pageNum_Recordset_task'];
}
$startRow_Recordset_task = $pageNum_Recordset_task * $maxRows_Recordset_task;

$colname_Recordset_task = $row_DetailRS1['id'];

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_task = sprintf("SELECT *
							FROM tk_task 								
							inner join tk_task_tpye on tk_task.csa_type=tk_task_tpye.id								
							inner join tk_user on tk_task.csa_to_user=tk_user.uid 
							inner join tk_status on tk_task.csa_remark2=tk_status.id 
								WHERE csa_project = %s AND csa_remark4 = '-1' ORDER BY csa_last_update DESC", GetSQLValueString($colname_Recordset_task, "text"));
$query_limit_Recordset_task = sprintf("%s LIMIT %d, %d", $query_Recordset_task, $startRow_Recordset_task, $maxRows_Recordset_task);
$Recordset_task = mysql_query($query_limit_Recordset_task, $tankdb) or die(mysql_error());
$row_Recordset_task = mysql_fetch_assoc($Recordset_task);

if (isset($_GET['totalRows_Recordset_task'])) {
  $totalRows_Recordset_task = $_GET['totalRows_Recordset_task'];
} else {
  $all_Recordset_task = mysql_query($query_Recordset_task);
  $totalRows_Recordset_task = mysql_num_rows($all_Recordset_task);
}
$totalPages_Recordset_task = ceil($totalRows_Recordset_task/$maxRows_Recordset_task)-1;

$queryString_Recordset_task = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_task") == false && 
        stristr($param, "totalRows_Recordset_task") == false && 
        stristr($param, "tab") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_task = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_task = sprintf("&totalRows_Recordset_task=%d%s", $totalRows_Recordset_task, $queryString_Recordset_task);


mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_sumlog =  sprintf("SELECT sum(csa_tb_manhour) as sum_hour FROM tk_task_byday WHERE csa_tb_backup3= %s ", GetSQLValueString($colname_DetailRS1, "text"));
$Recordset_sumlog = mysql_query($query_Recordset_sumlog, $tankdb) or die(mysql_error());
$row_Recordset_sumlog = mysql_fetch_assoc($Recordset_sumlog);

$maxRows_Recordset_comment = 10;
$pageNum_Recordset_comment = 0;
if (isset($_GET['pageNum_Recordset_comment'])) {
  $pageNum_Recordset_comment = $_GET['pageNum_Recordset_comment'];
}
$startRow_Recordset_comment = $pageNum_Recordset_comment * $maxRows_Recordset_comment;

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_comment = sprintf("SELECT * FROM tk_comment 
inner join tk_user on tk_comment.tk_comm_user =tk_user.uid 
								 WHERE tk_comm_pid = %s AND tk_comm_type = 2 
								
								ORDER BY tk_comm_lastupdate DESC", 
								GetSQLValueString($colname_DetailRS1, "text")
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
        stristr($param, "totalRows_Recordset_comment") == false && 
        stristr($param, "tab") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_comment = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_comment = sprintf("&totalRows_Recordset_comment=%d%s", $totalRows_Recordset_comment, $queryString_Recordset_comment);

$maxRows_Recordset_file = 15;
$pageNum_Recordset_file = 0;
if (isset($_GET['pageNum_Recordset_file'])) {
  $pageNum_Recordset_file = $_GET['pageNum_Recordset_file'];
}
$startRow_Recordset_file = $pageNum_Recordset_file * $maxRows_Recordset_file;

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_file = sprintf("SELECT * FROM tk_document 
inner join tk_user on tk_document.tk_doc_edit =tk_user.uid 
WHERE tk_doc_class1 = %s AND  tk_doc_class2 = 0 
								
								ORDER BY tk_doc_backup1 DESC, tk_doc_edittime DESC", 
								GetSQLValueString($colname_DetailRS1, "text")
								);
$query_limit_Recordset_file = sprintf("%s LIMIT %d, %d", $query_Recordset_file, $startRow_Recordset_file, $maxRows_Recordset_file);
$Recordset_file = mysql_query($query_limit_Recordset_file, $tankdb) or die(mysql_error());
$row_Recordset_file = mysql_fetch_assoc($Recordset_file);

if (isset($_GET['totalRows_Recordset_file'])) {
  $totalRows_Recordset_file = $_GET['totalRows_Recordset_file'];
} else {
  $all_Recordset_file = mysql_query($query_Recordset_file);
  $totalRows_Recordset_file = mysql_num_rows($all_Recordset_file);
}
$totalPages_Recordset_file = ceil($totalRows_Recordset_file/$maxRows_Recordset_file)-1;

$queryString_Recordset_file = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_file") == false && 
        stristr($param, "totalRows_Recordset_file") == false && 
        stristr($param, "tab") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_file = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_file = sprintf("&totalRows_Recordset_file=%d%s", $totalRows_Recordset_file, $queryString_Recordset_file);


$maxRows_Recordset_log = 15;
$pageNum_Recordset_log = 0;
if (isset($_GET['pageNum_Recordset_log'])) {
  $pageNum_Recordset_log = $_GET['pageNum_Recordset_log'];
}
$startRow_Recordset_log = $pageNum_Recordset_log * $maxRows_Recordset_log;

$colname_Recordset_log = $colname_DetailRS1;

$colmonth_log = date("m");
if (isset($_GET['logmonth'])) {
  $colmonth_log = $_GET['logmonth'];
}

$colyear_log = date("Y");
if (isset($_GET['logyear'])) {
  $colyear_log = $_GET['logyear'];
}

$colday_log = "";
if (isset($_GET['logday'])) {
  $colday_log = $_GET['logday'];
}

$coldate = $colyear_log.$colmonth_log.$colday_log;

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_log = sprintf("SELECT * FROM tk_task_byday 
								inner join tk_project on tk_task_byday.csa_tb_backup3=tk_project.id 
								inner join tk_task_tpye on tk_task_byday.csa_tb_backup4=tk_task_tpye.id 
								inner join tk_status on tk_task_byday.csa_tb_status=tk_status.id 
								inner join tk_task on tk_task_byday.csa_tb_backup1=tk_task.TID 
								inner join tk_user on tk_task_byday.csa_tb_backup2=tk_user.uid 
WHERE csa_tb_backup3 = %s AND csa_tb_year LIKE %s ORDER BY csa_tb_year DESC", 
GetSQLValueString($colname_Recordset_log, "text"),
GetSQLValueString($coldate . "%", "text")
);
$query_limit_Recordset_log = sprintf("%s LIMIT %d, %d", $query_Recordset_log, $startRow_Recordset_log, $maxRows_Recordset_log);
$Recordset_log = mysql_query($query_limit_Recordset_log, $tankdb) or die(mysql_error());
$row_Recordset_log = mysql_fetch_assoc($Recordset_log);

if (isset($_GET['totalRows_Recordset_log'])) {
  $totalRows_Recordset_log = $_GET['totalRows_Recordset_log'];
} else {
  $all_Recordset_log = mysql_query($query_Recordset_log);
  $totalRows_Recordset_log = mysql_num_rows($all_Recordset_log);
}
$totalPages_Recordset_log = ceil($totalRows_Recordset_log/$maxRows_Recordset_log)-1;
$queryString_Recordset_log = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset_log") == false && 
        stristr($param, "totalRows_Recordset_log") == false && 
        stristr($param, "tab") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_log = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_log = sprintf("&totalRows_Recordset_log=%d%s", $totalRows_Recordset_log, $queryString_Recordset_log);


$host_url="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER["QUERY_STRING"];
$host_url=strtr($host_url,"&","!");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/lhgdialog.css" rel="stylesheet" type="text/css" />
<title>WSS - <?php echo $multilingual_project_view_title; ?></title>
<script type="text/javascript" src="srcipt/jquery.js"></script>
<script type="text/javascript" src="srcipt/js.js"></script>
<script type="text/javascript" src="srcipt/lhgcore.js"></script>
<script type="text/javascript" src="srcipt/lhgdialog.js"></script>
<script type="text/javascript" src="chart/js/swfobject.js"></script> 
<script type="text/javascript"> 
var flashvars = {"data-file":"chart_pie_project.php?recordID=<?php echo $row_DetailRS1['id']; ?>"};  
var params = {menu: "false",scale: "noScale",wmode:"opaque"};  
swfobject.embedSWF("chart/open-flash-chart.swf", "chart", "450px", "230px", 
 "9.0.0","expressInstall.swf", flashvars,params);  
</script>
<script type="text/javascript">
function addcomm()
{
    J.dialog.get({ id: "test1", title: '<?php echo $multilingual_default_addcom; ?>', width: 600, height: 500, page: "comment_add.php?taskid=<?php echo $row_DetailRS1['id']; ?>&projectid=1&type=2" });
}

function   searchtask() 
      {document.form1.action= "project_view.php?#task "; 
        document.form1.submit(); 
        return   true; 
      
      } 

function   exportexcel() 
      {document.form1.action= "excel_log.php "; 
        document.form1.submit(); 
        return   false; 
      
      } 
</script>

<?php 
$tab = "-1";
if (isset($_GET['tab'])) {
  $tab = $_GET['tab'];
}

$tabid = $tab + 1;

if($tab <> "-1"){
echo "
<script language='javascript'>
function tabs1()
{
var len = ".$tabid.";
for (var i = 1; i <= len; i++)
{
document.getElementById('tab_a' + i).style.display = (i == ".$tabid.") ? 'block' : 'none';
document.getElementById('tab_' + i).className = (i == ".$tabid.") ? 'onhover' : 'none';
}
}
</script>
";
}
?>

<script language="javascript">
function tabs(n)
{
var len = 4;
for (var i = 1; i <= len; i++)
{
document.getElementById('tab_a' + i).style.display = (i == n) ? 'block' : 'none';
document.getElementById('tab_' + i).className = (i == n) ? 'onhover' : 'none';
}
}
</script>


</head>

<body <?php if($tab <> "-1"){ echo "onload='tabs1();'";}?>>
<?php require('head.php'); ?>
<br />
<table align="center" class="fontsize-s input_task_table glink">
  <tr>
    <td width="40%">
	<span class="float_left"><?php echo $multilingual_breadcrumb_prjlisturl; ?></span>
	  <span class="ui-icon month_next float_left"></span>
	  <span class="float_left" style="margin-right:100px"><?php echo $multilingual_project_view_title; ?></span>
	
	</td>
    <td width="60%" align="right"><span class="gray"><?php echo $multilingual_project_id; ?>:<?php echo $row_DetailRS1['id']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $multilingual_global_lastupdate; ?>: <?php echo $row_DetailRS1['project_lastupdate']; ?> &nbsp;&nbsp;&nbsp;&nbsp; </span> 
	
	<?php if($_SESSION['MM_rank'] > "2") { ?>
	<input name="" type="button" class="button" onclick="javascript:self.location='default_task_add.php?projectID=<?php echo $row_DetailRS1['id']; ?>&formproject=1';" value="<?php echo $multilingual_project_newtask; ?>" /> 
	<?php }  ?>
	
	<?php if($_SESSION['MM_rank'] > "3" || $_SESSION['MM_uid'] == $row_DetailRS1['project_to_user']) { ?>
      <input name="" type="button" class="button" onclick="javascript:self.location='project_edit.php?editID=<?php echo $row_DetailRS1['id']; ?>';" value="<?php echo $multilingual_global_action_edit; ?>" /> 
      <?php }  ?> 
	  
      <?php if($_SESSION['MM_rank'] > "3")  {  ?>
      <input name="" type="button" class="button" onclick="javascript:if(confirm( '<?php 
	 if($totalRows_Recordset_task == "0"){  
	  echo $multilingual_global_action_delconfirm;
	  } else { echo $multilingual_global_action_delconfirm3;} ?>'))self.location='project_del.php?delID=<?php echo $row_DetailRS1['id']; ?>';" value="<?php echo $multilingual_global_action_del; ?>" 
	   <?php if( $_SESSION['MM_Username'] == $multilingual_dd_user_readonly){
	  echo "disabled='disabled'";
	  } ?> 
	  /> 
      <?php }  ?> 
	  
      <input name="button" type="button" id="button" onclick="javascript:history.go(-1)" value="<?php echo $multilingual_global_action_back; ?>"  class="button"  />
	</td>
  </tr>
  
  <tr>
    <td colspan="2"><span class="input_task_title margin-y" style="margin-top:0px;"><?php echo $multilingual_project_view_section1; ?></span></td>
  </tr>

  <tr>
    <td valign="top">
	<span class="font_big18 fontbold"><?php echo $row_DetailRS1['project_name']; ?></span><br /><br /><br />
	<div class="status_title float_left fontbold"><?php echo $multilingual_project_status; ?>:</div>    <div class="status_view float_left"><?php echo $row_DetailRS1['task_status_display']; ?></div>
	<div class="clearboth"></div>
	
	<span class="fontbold"><?php echo $multilingual_tasklog_cost; ?>:</span>    
	<?php if($row_Recordset_sumlog["sum_hour"] == null){
	  $sum_hour = 0;
	  } else {
	  $sum_hour = $row_Recordset_sumlog["sum_hour"];
	  }
	  echo $sum_hour;?> <?php echo $multilingual_project_hour; ?>
	<br/><br/>
	
	
  <?php if ($row_DetailRS1['project_code'] <> " " && $row_DetailRS1['project_code'] <> "") {  ?>
	<span class="fontbold"><?php echo $multilingual_project_code; ?>: </span><?php echo $row_DetailRS1['project_code']; ?><br /><br />
  <?php } ?>	
  <?php if ($row_DetailRS1['project_start'] <> "0000-00-00") {  ?>
	<span class="fontbold"><?php echo $multilingual_project_start; ?>: </span><?php echo $row_DetailRS1['project_start']; ?><br />
  <?php } ?>	
  <?php if ($row_DetailRS1['project_end'] <> "0000-00-00") {  ?>
	<span class="fontbold"><?php echo $multilingual_project_end; ?>: </span><?php echo $row_DetailRS1['project_end']; ?><br /><br />
  <?php } ?>	
	<span class="fontbold"><?php echo $multilingual_project_touser; ?>:  </span><a href="user_view.php?recordID=<?php echo $row_DetailRS1['project_to_user']; ?>"><?php echo $row_DetailRS1['tk_display_name']; ?></a><br /><br /><br /><br />
      
    
	</td>
    <td align="center">
	<?php if ($sum_hour > 0.5) {  ?>
	<?php echo $multilingual_project_taskoverlay; ?><br />
	<div id="chart"></div>
	<?php }  ?>	</td>
  </tr>
  
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  
  <!--remark start -->
  <tr valign="baseline">
      <td colspan="2">
	  <?php if($_SESSION['MM_rank'] > "1") { ?>
	  <div><a class="icon_folder" style="margin-top:0px;"></a><a href="file_add.php?projectid=<?php echo $row_DetailRS1['id']; ?>&pid=0&folder=1&pagetab=allfile" class="file_create_a">[<?php echo $multilingual_project_file_addfolder; ?>]</a> 
	  <a class="icon_file" style="margin-top:0px; "></a><a href="file_add.php?projectid=<?php echo $row_DetailRS1['id']; ?>&pid=0&pagetab=allfile" class="file_create_a">[<?php echo $multilingual_project_file_addfile; ?>]</a> 
	   <a onclick="addcomm();" class="mouse_hover file_create_a">[<?php echo $multilingual_default_addcom; ?>]</a>
	  <a name="comment"></a></div>
	  <?php } ?>
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
	  <?php if($_SESSION['MM_rank'] > "1") { ?>
	  <?php if ($_SESSION['MM_rank'] > "4" || $row_Recordset_comment['tk_comm_user'] == $_SESSION['MM_uid']) {  ?>
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
	  echo $multilingual_global_action_delconfirm; ?>'))self.location='comment_del.php?delID=<?php echo $row_Recordset_comment['coid']; ?>&projectID=<?php echo $row_DetailRS1['id']; ?>';"
	  ><?php echo $multilingual_global_action_del; ?></a>
	  <?php } else {  
	   echo $multilingual_global_action_del; 
	    }  ?>
	  <?php } ?>
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
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr> 
  
  <!--remark end -->
  
<tr>
<td colspan="2" >

<div class="tab">
<ul class="menu" id="menutitle">

<li id="tab_1"  class="onhover" 
<?php if ($totalRows_Recordset_task == 0) { echo "style='display:none'"; }?>>

<a href="javascript:void(0)" onclick="tabs('1');" >
<?php echo $multilingual_project_view_wbs; ?></a>
</li>

<li id="tab_2" 
<?php if ($totalRows_Recordset_task == 0 ) { echo "class='onhover'"; }?> 
<?php if ($totalRows_Recordset_file == 0) { echo "style='display:none'"; }?>>
<a href="javascript:void(0)" onclick="tabs('2');" >
<?php echo $multilingual_project_file; ?></a>
</li>

<li id="tab_3" 
<?php if ($totalRows_Recordset_task == 0 && $totalRows_Recordset_file == 0) { echo "class='onhover'"; }?> 
<?php if ($totalRows_Recordset_task == 0) { echo "style='display:none'"; }?>>
<a href="javascript:void(0)" onclick="tabs('3');" >
<?php echo $multilingual_project_view_log; ?></a>
</li>

<li id="tab_4" <?php if ($totalRows_Recordset_task == 0 && $totalRows_Recordset_file == 0 && $totalRows_Recordset_log == 0) { echo "class='onhover'"; }?> <?php if (($row_DetailRS1['project_text'] ==  "&nbsp;" ||$row_DetailRS1['project_text'] ==  "" )&& ($row_DetailRS1['project_from_contact'] == "" ||$row_DetailRS1['project_from_contact'] == " ")) { echo "style='display:none'"; } ?>><a href="javascript:void(0)" onclick="tabs('4');" ><?php echo $multilingual_project_view_title; ?></a></li>




<?php if ($totalRows_Recordset_file <> 0 || ($row_DetailRS1['project_text'] <>  "&nbsp;" && $row_DetailRS1['project_text'] <>  "") || ($row_DetailRS1['project_from_contact'] <> "" && $row_DetailRS1['project_from_contact'] <> " ") || $totalRows_Recordset_task <> 0) { ?>
<li >&nbsp;</li><li >&nbsp;</li>
<?php }?><a name="task"></a>
</ul>


<!-- task start -->
<div class="tab_b" id="tab_a1" 

<?php if ($totalRows_Recordset_task > 0) { 
echo "style='display:block'";
} else {
echo "style='display:none'";
} ?>>

<?php if ($totalRows_Recordset_task > 0) { // Show if recordset not empty ?>
  <table width="99%">
  <tr>
    <td colspan="2">
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
          <th><?php echo $multilingual_default_tasklevel; ?></th>
        </tr>
        </thead>
        
        <?php do { ?>
          <tr>
            <td><?php echo $row_Recordset_task['TID']; ?></td>
            <td class="task_title">
			<div  class="text_overflow_150 task_title"  title="<?php echo $row_Recordset_task['csa_text']; ?>">
			<a href="default_task_edit.php?editID=<?php echo $row_Recordset_task['TID']; ?>" >
			<b>[<?php echo $row_Recordset_task['task_tpye']; ?>]</b> <?php echo $row_Recordset_task['csa_text']; ?>			</a>			</div></td>
            <td ><a href="user_view.php?recordID=<?php echo $row_Recordset_task['csa_to_user']; ?>"><?php echo $row_Recordset_task['tk_display_name']; ?></a></td>
            <td><?php echo $row_Recordset_task['task_status_display']; ?></td>
            <td><?php echo $row_Recordset_task['csa_plan_st']; ?></td>
            <td><?php echo $row_Recordset_task['csa_plan_et']; ?></td>
            <td>
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
            <td>
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
          <?php } while ($row_Recordset_task = mysql_fetch_assoc($Recordset_task)); ?>
      </table>
      </div>

     
      <table class="rowcon" border="0" align="center">
<tr>
<td>   <table border="0">
        <tr>
          <td><?php if ($pageNum_Recordset_task > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset_task=%d%s", $currentPage, 0, $queryString_Recordset_task); ?>#task"><?php echo $multilingual_global_first; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset_task > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset_task=%d%s", $currentPage, max(0, $pageNum_Recordset_task - 1), $queryString_Recordset_task); ?>#task"><?php echo $multilingual_global_previous; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset_task < $totalPages_Recordset_task) { // Show if not last page ?>
              <a href="<?php 
			  printf("%s?pageNum_Recordset_task=%d%s", $currentPage, min($totalPages_Recordset_task, $pageNum_Recordset_task + 1), $queryString_Recordset_task); ?>#task" ><?php echo $multilingual_global_next; ?></a>
			  <?php } // Show if not last page ?>
			  </td>
          <td><?php if ($pageNum_Recordset_task < $totalPages_Recordset_task) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset_task=%d%s", $currentPage, $totalPages_Recordset_task, $queryString_Recordset_task); ?>#task"><?php echo $multilingual_global_last; ?></a>
              <?php } // Show if not last page ?></td>
        </tr>
      </table></td>
<td align="right">   <?php echo ($startRow_Recordset_task + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset_task + $maxRows_Recordset_task, $totalRows_Recordset_task) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset_task ?>)&nbsp;&nbsp;&nbsp;&nbsp;</td>
</tr>
</table>      </td>
</tr>
</table>
<?php } // Show if recordset not empty ?>
</div>
<!-- task end -->

<!--file start -->
<div class="tab_b" id="tab_a2" 
<?php if ($totalRows_Recordset_task > 0) { 
echo "style='display:none'";
} else {
echo "style='display:block'";
} ?>
>

<?php if ($totalRows_Recordset_file > 0) {  ?>
<table width="99%">

	<?php do { ?>
		<tr valign="baseline">
      <td colspan="2" nowrap="nowrap"  class="comment_list">
	  <div class="float_left">
	  
	  
	  <a href="file.php?recordID=<?php echo $row_Recordset_file['docid']; ?><?php 
	  if($row_Recordset_file['tk_doc_backup1']=="1"){
	  echo "&folder=".$row_Recordset_file['tk_doc_backup1'];
	  } ?>&projectID=<?php echo $colname_DetailRS1; ?>&pagetab=allfile
	  " class="
	  <?php 
	  if($row_Recordset_file['tk_doc_backup1']=="1"){
	  echo "icon_folder";
	  }else{
	  echo "icon_file";
	  } ?>
	  ">
	  <?php echo $row_Recordset_file['tk_doc_title']; ?></a>

	  
	 
	  <?php if ($row_Recordset_file['tk_doc_attachment'] <> null) {  ?>
	  <a href="<?php echo $row_Recordset_file['tk_doc_attachment']; ?>" class="icon_download"><?php echo $multilingual_project_file_download; ?></a>
	  <?php } ?>	  
	  </div>
	  <div class="float_right">
	  <span class="gray" >
	  <?php echo $row_Recordset_file['tk_display_name']; ?> <?php echo $multilingual_project_file_update; ?>
	  <?php echo $row_Recordset_file['tk_doc_edittime']; ?>
	  </span>
	  <?php if ($row_Recordset_file['tk_doc_backup1'] <> "1") {  ?>
	   <a href="word.php?fileid=<?php echo $row_Recordset_file['docid']; ?>" class="icon_word"><?php echo $multilingual_project_file_word; ?></a> 
	 <?php } ?>
	 &nbsp;
	 <?php if($_SESSION['MM_rank'] > "1") { ?>
	  <a href="file_edit.php?editID=<?php echo $row_Recordset_file['docid']; ?>&projectID=<?php echo $row_DetailRS1['id']; ?>&pid=0&folder=<?php echo $row_Recordset_file['tk_doc_backup1']; ?>">
	  <?php echo $multilingual_global_action_edit; ?></a> 
	  &nbsp;
	  <?php if ($_SESSION['MM_rank'] > "4" || $row_Recordset_file['tk_doc_create'] == $_SESSION['MM_uid']) {  ?>
	  
	  <?php if ($_SESSION['MM_Username'] <> $multilingual_dd_user_readonly) {  ?>
	   <a  class="mouse_hover" 
	  onclick="javascript:if(confirm( '<?php 
	  if ($row_Recordset_file['tk_doc_backup1'] == 0){
	  echo $multilingual_global_action_delconfirm;}
	  else {
	  echo $multilingual_global_action_delconfirm5;}
	   ?>'))self.location='file_del.php?delID=<?php echo $row_Recordset_file['docid']; ?>&projectID=<?php echo $row_DetailRS1['id']; ?>&pid=0&url=<?php echo $host_url; ?>';"
	  ><?php echo $multilingual_global_action_del; ?></a>
	  <?php } else {  
	   echo $multilingual_global_action_del; 
	    }  ?>
	  <?php }  ?><?php }  ?>
</div>
	  </td>
    </tr>
    
	<?php
} while ($row_Recordset_file = mysql_fetch_assoc($Recordset_file));
  $rows = mysql_num_rows($Recordset_file);
  if($rows > 0) {
      mysql_data_seek($Recordset_file, 0);
	  $row_Recordset_file = mysql_fetch_assoc($Recordset_file);
  }
?>
	<tr valign="baseline">
      <td colspan="2" >
<table class="rowcon" border="0" align="center">
<tr>
<td>   <table border="0">
        <tr>
          <td><?php if ($pageNum_Recordset_file > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset_file=%d%s", $currentPage, 0, $queryString_Recordset_file); ?>&tab=1#task"><?php echo $multilingual_global_first; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset_file > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset_file=%d%s", $currentPage, max(0, $pageNum_Recordset_file - 1), $queryString_Recordset_file); ?>&tab=1#task"><?php echo $multilingual_global_previous; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset_file < $totalPages_Recordset_file) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset_file=%d%s", $currentPage, min($totalPages_Recordset_file, $pageNum_Recordset_file + 1), $queryString_Recordset_file); ?>&tab=1#task"><?php echo $multilingual_global_next; ?></a>
              <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_Recordset_file < $totalPages_Recordset_file) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset_file=%d%s", $currentPage, $totalPages_Recordset_file, $queryString_Recordset_file); ?>&tab=1#task"><?php echo $multilingual_global_last; ?></a>
              <?php } // Show if not last page ?></td>
        </tr>
      </table></td>
<td align="right">   <?php echo ($startRow_Recordset_file + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset_file + $maxRows_Recordset_file, $totalRows_Recordset_file) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset_file ?>)&nbsp;&nbsp;&nbsp;&nbsp;</td>
</tr>
</table>
	</td>
    </tr>
	
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr> 
  </table>  

<?php }  ?>
</div>
<!--file end -->


<!--log start-->
<div class="tab_b" id="tab_a3" 
<?php if ($totalRows_Recordset_task == 0 && $totalRows_Recordset_file == 0) { 
echo "style='display:block'";
} else {
echo "style='display:none'";
} ?>
>
<?php if ($totalRows_Recordset_task  > 0) {  ?>
<table width="100%" cellpadding="5">
  <tr>
  <td class="gray_bg">
<form id="form1" name="form1" method="get" >
  <?php echo $multilingual_user_view_date; ?>
  <input name="recordID" id="recordID" value="<?php echo $colname_DetailRS1; ?>" style="display:none" />
  <input name="tab" id="tab" value="2" style="display:none" />
      <select name="logyear" id="logyear">
        <option value=""><?php echo $multilingual_global_all; ?></option>
        <option value="2009" <?php if (!(strcmp(2009, date("Y")))) {echo "selected=\"selected\"";} ?>>2009</option>
        <option value="2010" <?php if (!(strcmp(2010, date("Y")))) {echo "selected=\"selected\"";} ?>>2010</option>
        <option value="2011" <?php if (!(strcmp(2011, date("Y")))) {echo "selected=\"selected\"";} ?>>2011</option>
        <option value="2012" <?php if (!(strcmp(2012, date("Y")))) {echo "selected=\"selected\"";} ?>>2012</option>
        <option value="2013" <?php if (!(strcmp(2013, date("Y")))) {echo "selected=\"selected\"";} ?>>2013</option>
        <option value="2014" <?php if (!(strcmp(2014, date("Y")))) {echo "selected=\"selected\"";} ?>>2014</option>
        <option value="2015" <?php if (!(strcmp(2015, date("Y")))) {echo "selected=\"selected\"";} ?>>2015</option>
        <option value="2016" <?php if (!(strcmp(2016, date("Y")))) {echo "selected=\"selected\"";} ?>>2016</option>
        <option value="2017" <?php if (!(strcmp(2017, date("Y")))) {echo "selected=\"selected\"";} ?>>2017</option>
        <option value="2018" <?php if (!(strcmp(2018, date("Y")))) {echo "selected=\"selected\"";} ?>>2018</option>
        <option value="2019" <?php if (!(strcmp(2019, date("Y")))) {echo "selected=\"selected\"";} ?>>2019</option>
        <option value="2020" <?php if (!(strcmp(2020, date("Y")))) {echo "selected=\"selected\"";} ?>>2020</option>
      </select> / <select name="logmonth" id="logmonth">
      <option value=""><?php echo $multilingual_global_all; ?></option>
      <option value="01" <?php if (!(strcmp(1, date("n")))) {echo "selected=\"selected\"";} ?>>01</option>
      <option value="02" <?php if (!(strcmp(2, date("n")))) {echo "selected=\"selected\"";} ?>>02</option>
      <option value="03" <?php if (!(strcmp(3, date("n")))) {echo "selected=\"selected\"";} ?>>03</option>
      <option value="04" <?php if (!(strcmp(4, date("n")))) {echo "selected=\"selected\"";} ?>>04</option>
      <option value="05" <?php if (!(strcmp(5, date("n")))) {echo "selected=\"selected\"";} ?>>05</option>
      <option value="06" <?php if (!(strcmp(6, date("n")))) {echo "selected=\"selected\"";} ?>>06</option>
      <option value="07" <?php if (!(strcmp(7, date("n")))) {echo "selected=\"selected\"";} ?>>07</option>
      <option value="08" <?php if (!(strcmp(8, date("n")))) {echo "selected=\"selected\"";} ?>>08</option>
      <option value="09" <?php if (!(strcmp(9, date("n")))) {echo "selected=\"selected\"";} ?>>09</option>
      <option value="10" <?php if (!(strcmp(10, date("n")))) {echo "selected=\"selected\"";} ?>>10</option>
      <option value="11" <?php if (!(strcmp(11, date("n")))) {echo "selected=\"selected\"";} ?>>11</option>
      <option value="12" <?php if (!(strcmp(12, date("n")))) {echo "selected=\"selected\"";} ?>>12</option>
    </select> / <select name="logday" id="logday">
      <option value="" selected="selected"><?php echo $multilingual_global_all; ?></option>
      <option value="01" >01</option>
      <option value="02" >02</option>
      <option value="03" >03</option>
      <option value="04" >04</option>
      <option value="05" >05</option>
      <option value="06" >06</option>
      <option value="07" >07</option>
      <option value="08" >08</option>
      <option value="09" >09</option>
      <option value="10" >10</option>
      <option value="11" >11</option>
      <option value="12" >12</option>
      <option value="13" >13</option>
      <option value="14" >14</option>
      <option value="15" >15</option>
      <option value="16" >16</option>
      <option value="17" >17</option>
      <option value="18" >18</option>
      <option value="19" >19</option>
      <option value="20" >20</option>
      <option value="21" >21</option>
      <option value="22" >22</option>
      <option value="23" >23</option>
      <option value="24" >24</option>
      <option value="25" >25</option>
      <option value="26" >26</option>
      <option value="27" >27</option>
      <option value="28" >28</option>
      <option value="29" >29</option>
      <option value="30" >30</option>
      <option value="31" >31</option>
    </select>
	<input type="button" value="<?php echo $multilingual_global_action_ok; ?>" class="button" onclick= "return   searchtask(); " />
	
	<input type="button" name="export" id="export" value="<?php echo $multilingual_global_excel; ?>"  class="button" onclick= "return   exportexcel(); " />
 </form>
  </td>
  </tr>
  <tr>
    <td>
	<?php if ($totalRows_Recordset_log > 0) { ?>
    <div >
    <table border="0" cellspacing="0" cellpadding="0" width="100%" >


  <?php do { ?>
<tr>
      <td class="comment_list">
<?php echo $row_Recordset_log['tk_display_name']; ?> <?php echo $multilingual_user_view_by; ?> 
	   
<?php 
$logdate = $row_Recordset_log['csa_tb_year'];
$logyear = str_split($logdate,4);
$logmonth = str_split($logyear[1],2);
echo $logyear[0]; ?>-<?php echo $logmonth[0]; ?>-<?php echo $logmonth[1]; ?>	



	  <?php echo $multilingual_user_view_do; ?>  
	  <?php echo $row_Recordset_log['task_tpye']; ?> - 
	  <a href="default_task_edit.php?editID=<?php echo $row_Recordset_log['TID']; ?>" >
	  <?php echo $row_Recordset_log['csa_text']; ?></a>

 (<?php echo $multilingual_user_view_project2; ?>: 
 <a href="project_view.php?recordID=<?php echo $row_Recordset_log['csa_project']; ?>">
  <?php echo $row_Recordset_log['project_name']; ?></a>)


 <?php echo $multilingual_user_view_cost; ?>: 
<?php echo $row_Recordset_log['csa_tb_manhour']; ?> <?php echo $multilingual_user_view_hour; ?>&nbsp;
 <?php echo $multilingual_user_view_status; ?>: 
<?php echo $row_Recordset_log['task_status']; ?>
 

  &nbsp;&nbsp;&nbsp;&nbsp;<span class="gray" ><?php echo $multilingual_project_file_update; ?><?php echo $row_Recordset_log['csa_tb_lastupdate']; ?></span>
</td>
</tr>
     <?php
} while ($row_Recordset_log = mysql_fetch_assoc($Recordset_log));
  $rows = mysql_num_rows($Recordset_log);
  if($rows > 0) {
      mysql_data_seek($Recordset_log, 0);
	  $row_Recordset_log = mysql_fetch_assoc($Recordset_log);
  }
?>
</table>
</div>
<table class="rowcon" border="0" align="center">
<tr>
<td>   <table border="0">
        <tr>
          <td><?php if ($pageNum_Recordset_log > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset_log=%d%s", $currentPage, 0, $queryString_Recordset_log); ?>&tab=2#task"><?php echo $multilingual_global_first; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset_log > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset_log=%d%s", $currentPage, max(0, $pageNum_Recordset_log - 1), $queryString_Recordset_log); ?>&tab=2#task"><?php echo $multilingual_global_previous; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset_log < $totalPages_Recordset_log) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset_log=%d%s", $currentPage, min($totalPages_Recordset_log, $pageNum_Recordset_log + 1), $queryString_Recordset_log); ?>&tab=2#task"><?php echo $multilingual_global_next; ?></a>
              <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_Recordset_log < $totalPages_Recordset_log) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset_log=%d%s", $currentPage, $totalPages_Recordset_log, $queryString_Recordset_log); ?>&tab=2#task"><?php echo $multilingual_global_last; ?></a>
              <?php } // Show if not last page ?></td>
        </tr>
      </table></td>
<td align="right">   <?php echo ($startRow_Recordset_log + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset_log + $maxRows_Recordset_log, $totalRows_Recordset_log) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset_log ?>)&nbsp;&nbsp;&nbsp;&nbsp;</td>
</tr>
</table> 

<?php } else { ?>
<div class="update_bg">
    <?php echo $multilingual_user_view_nolog; ?>
</div>

<?php }  ?>
 </td>
</tr>
</table>  
<?php }  ?>
</div>
<!-- log end-->
 
<!-- more start -->
<div class="tab_b" id="tab_a4" 
<?php if ($totalRows_Recordset_file == 0 && $totalRows_Recordset_task == 0 && $totalRows_Recordset_log  == 0) { 
echo "style='display:block'";
} else {
echo "style='display:none'";
} ?>
>


<table width="99%">
<?php if ($row_DetailRS1['project_text'] <> "&nbsp;" && $row_DetailRS1['project_text'] <> "") {  ?>
  <tr>
    <td colspan="2"><span class="input_task_title margin-y" style="margin-top:0px;"><?php echo $multilingual_project_description; ?></span></td>
  </tr>
  <tr>
    <td colspan="2">
    <?php 
	
	
	echo $row_DetailRS1['project_text']; 
	
	?>    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php } // Show if recordset not empty ?>
  <?php if ($row_DetailRS1['project_from_contact'] <> "" && $row_DetailRS1['project_from_contact'] <> " ") { // Show if recordset not empty ?>
  <tr>
    <td colspan="2"><span class="input_task_title margin-y" style="margin-top:0px;"><?php echo $multilingual_project_view_section2; ?></span></td>
  </tr>
  <tr>
    <td colspan="2">
	<?php 
	
	$row_DetailRS1['project_from_contact']   =   htmlspecialchars($row_DetailRS1['project_from_contact']);  
	$row_DetailRS1['project_from_contact']   =   str_replace("\n",   "<br>",   $row_DetailRS1['project_from_contact']);  
	$row_DetailRS1['project_from_contact']   =   str_replace("     ",   "&nbsp;",   $row_DetailRS1['project_from_contact']);   
	echo $row_DetailRS1['project_from_contact']; 
	
	?>
	</td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php } // Show if recordset not empty ?>
</table>
</div>
<!-- more end -->



</td>
</tr>

  <tr>
    <td colspan="2">
      <span class="input_task_submit">
	 
	  <div class="float_right">
	 <?php if($_SESSION['MM_rank'] > "2") { ?>
	<input name="" type="button" class="button" onclick="javascript:self.location='default_task_add.php?projectID=<?php echo $row_DetailRS1['id']; ?>&formproject=1';" value="<?php echo $multilingual_project_newtask; ?>" /> 
	<?php }  ?>
	
	<?php if($_SESSION['MM_rank'] > "3" || $_SESSION['MM_uid'] == $row_DetailRS1['project_to_user']) { ?>
      <input name="" type="button" class="button" onclick="javascript:self.location='project_edit.php?editID=<?php echo $row_DetailRS1['id']; ?>';" value="<?php echo $multilingual_global_action_edit; ?>" /> 
      <?php }  ?> 
	  
      <?php if($_SESSION['MM_rank'] > "3")  {  ?>
      <input name="" type="button" class="button" onclick="javascript:if(confirm( '<?php 
	 if($totalRows_Recordset_task == "0"){  
	  echo $multilingual_global_action_delconfirm;
	  } else { echo $multilingual_global_action_delconfirm3;} ?>'))self.location='project_del.php?delID=<?php echo $row_DetailRS1['id']; ?>';" value="<?php echo $multilingual_global_action_del; ?>" 
	   <?php if( $_SESSION['MM_Username'] == $multilingual_dd_user_readonly){
	  echo "disabled='disabled'";
	  } ?> 
	  /> 
      <?php }  ?>
	  
      <input name="button" type="button" id="button" onclick="javascript:history.go(-1)" value="<?php echo $multilingual_global_action_back; ?>"  class="button"  />
	  </div>
	  </span></td>
  </tr>
</table>
<p>





</p>
<p>&nbsp;</p>
<?php require('foot.php'); ?>
</body>
</html><?php
mysql_free_result($DetailRS1);
mysql_free_result($Recordset_task);
?>