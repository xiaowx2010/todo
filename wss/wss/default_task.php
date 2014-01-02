<?php require_once('config/tank_config.php'); ?>
<?php require_once('session.php'); ?>
<?php
$url_this = $_SERVER["QUERY_STRING"] ;
$_SESSION['urlthis'] = $url_this;

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

$maxRows_Recordset1 = 15;
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;



$colname_Recordset1 = $_SESSION['MM_Username'];
if (isset($_GET['select4'])) {
  $colname_Recordset1 = $_GET['select4'];
}

$colfromuser_Recordset1 = "%";
if (isset($_GET['select2'])) {
  $colfromuser_Recordset1 = $_GET['select2'];
}

$colmonth_Recordset1 = date("m");
if (isset($_GET['textfield'])) {
  $colmonth_Recordset1 = $_GET['textfield'];
}

$colyear_Recordset1 = date("Y");
if (isset($_GET['select_year'])) {
  $colyear_Recordset1 = $_GET['select_year'];
}

if ($colyear_Recordset1 == "--"){
$startday = "1975-09-23";
$endday = "3000-13-31";
} else if ($colmonth_Recordset1 == "--"){
$startday = $colyear_Recordset1."-01-01";
$endday = $colyear_Recordset1."-12-31";
} else {
$startday = $colyear_Recordset1."-".$colmonth_Recordset1."-01";
$endday = $colyear_Recordset1."-".$colmonth_Recordset1."-31";
}

$colprt_Recordset1 = "";
if (isset($_GET['select_prt'])) {
  $colprt_Recordset1 = $_GET['select_prt'];
}

$coltemp_Recordset1 = "";
if (isset($_GET['select_temp'])) {
  $coltemp_Recordset1 = $_GET['select_temp'];
}

$colstatus_Recordset1 = "";
if (isset($_GET['select_st'])) {
  $colstatus_Recordset1 = $_GET['select_st'];
}

$colstatusf_Recordset1 = "+";
if (isset($_GET['select_stf'])) {
  $colstatusf_Recordset1 = $_GET['select_stf'];
}

$coltype_Recordset1 = "";
if (isset($_GET['select_type'])) {
  $coltype_Recordset1 = $_GET['select_type'];
}


$colproject_Recordset1 = "";
if (isset($_GET['select_project'])) {
  $colproject_Recordset1 = $_GET['select_project'];
}


$colinputid_Recordset1 = "";
if (isset($_GET['inputid'])) {
  $colinputid_Recordset1 = $_GET['inputid'];
}

$colinputtitle_Recordset1 = "";
if (isset($_GET['inputtitle'])) {
  $colinputtitle_Recordset1 = $_GET['inputtitle'];
}

$colcreate_Recordset1 = "%";
if (isset($_GET['create_by'])) {
  $colcreate_Recordset1 = $_GET['create_by'];
}

if($colyear_Recordset1 == "--"){
$YEAR = "0000";
} else {
$YEAR = $colyear_Recordset1;
}
if($colmonth_Recordset1 == "--"){
$MONTH = "00";
} else {
$MONTH = $colmonth_Recordset1;
}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset1 = sprintf("SELECT *, 
							
							tk_project.project_name as project_name_prt,
							tk_user1.tk_display_name as tk_display_name1, 
							tk_user2.tk_display_name as tk_display_name2, 
							tk_user3.tk_display_name as tk_display_name3 
							
							FROM tk_task  
							inner join tk_task_tpye on tk_task.csa_type=tk_task_tpye.id
							inner join tk_project on tk_task.csa_project=tk_project.id
							
							inner join tk_user as tk_user1 on tk_task.csa_to_user=tk_user1.tk_user_login 
							inner join tk_user as tk_user2 on tk_task.csa_from_user=tk_user2.tk_user_login 
							inner join tk_user as tk_user3 on tk_task.csa_create_user=tk_user3.tk_user_login 
							inner join tk_status on tk_task.csa_remark2=tk_status.id
							
							WHERE tk_task.csa_to_user LIKE %s 
							AND tk_task.csa_from_user LIKE %s 
							AND tk_task.csa_priority LIKE %s 
							AND tk_task.csa_temp LIKE %s 

							AND tk_status.task_status LIKE %s 
							AND tk_status.task_status NOT LIKE %s 

							AND tk_task.csa_type LIKE %s 
							AND tk_task.csa_project LIKE %s 
							AND tk_task.TID LIKE %s 
							AND tk_task.csa_text LIKE %s
							AND tk_task.csa_create_user LIKE %s

							AND (tk_task.csa_plan_st <=%s
 							AND tk_task.csa_plan_et >=%s
							OR tk_task.csa_plan_st <=%s
 							AND tk_task.csa_plan_et >=%s
							OR tk_task.csa_plan_st >=%s
 							AND tk_task.csa_plan_et <=%s)
														
							ORDER BY csa_last_update DESC", 
							
							GetSQLValueString($colname_Recordset1 , "text"), 
							GetSQLValueString($colfromuser_Recordset1 , "text"),  
							GetSQLValueString("%" . $colprt_Recordset1 . "%", "text"), 
							GetSQLValueString("%" . $coltemp_Recordset1 . "%", "text"), 
							GetSQLValueString("%" . $colstatus_Recordset1 . "%", "text"),  
							GetSQLValueString("%" . $colstatusf_Recordset1 . "%", "text"),  
							GetSQLValueString("%" . $coltype_Recordset1 . "%", "text"),
							GetSQLValueString("%" . $colproject_Recordset1 . "%", "text"),
							GetSQLValueString("%" . $colinputid_Recordset1 . "%", "text"),
							GetSQLValueString("%" . $colinputtitle_Recordset1 . "%", "text"),
							GetSQLValueString($colcreate_Recordset1 , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($endday , "text")
							);
$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
$Recordset1 = mysql_query($query_limit_Recordset1, $tankdb) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);

if (isset($_GET['totalRows_Recordset1'])) {
  $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
} else {
  $all_Recordset1 = mysql_query($query_Recordset1);
  $totalRows_Recordset1 = mysql_num_rows($all_Recordset1);
}
$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;

$queryString_Recordset1 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset1") == false && 
        stristr($param, "totalRows_Recordset1") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset1 = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset1 = sprintf("&totalRows_Recordset1=%d%s", $totalRows_Recordset1, $queryString_Recordset1);

if($YEAR <> "0000" && $MONTH <> "00"){
mysql_select_db($database_tankdb, $tankdb);
$query_Reclog = sprintf("SELECT *							
							FROM tk_task  
							inner join tk_task_byday on tk_task.TID=tk_task_byday.csa_tb_backup1
							inner join tk_status on tk_task.csa_remark2=tk_status.id 
							inner join tk_status as tk_status1 on tk_task_byday.csa_tb_status=tk_status1.id 

							WHERE tk_task.csa_to_user LIKE %s 
							AND tk_task.csa_from_user LIKE %s 
							AND tk_task.csa_priority LIKE %s 
							AND tk_task.csa_temp LIKE %s 

							AND tk_status.task_status LIKE %s 
							

							AND tk_task.csa_type LIKE %s 
							AND tk_task.csa_project LIKE %s 			
							AND tk_task.TID LIKE %s 
							AND tk_task.csa_text LIKE %s
							AND tk_task.csa_create_user LIKE %s

							AND (tk_task.csa_plan_st <=%s
 							AND tk_task.csa_plan_et >=%s
							OR tk_task.csa_plan_st <=%s
 							AND tk_task.csa_plan_et >=%s
							OR tk_task.csa_plan_st >=%s
 							AND tk_task.csa_plan_et <=%s)
														
							ORDER BY csa_last_update DESC", 
							
							GetSQLValueString($colname_Recordset1 , "text"), 
							GetSQLValueString($colfromuser_Recordset1 , "text"),  
							GetSQLValueString("%" . $colprt_Recordset1 . "%", "text"), 
							GetSQLValueString("%" . $coltemp_Recordset1 . "%", "text"), 
							GetSQLValueString("%" . $colstatus_Recordset1 . "%", "text"),  
							
							GetSQLValueString("%" . $coltype_Recordset1 . "%", "text"),
							GetSQLValueString("%" . $colproject_Recordset1 . "%", "text"),
							GetSQLValueString("%" . $colinputid_Recordset1 . "%", "text"),
							GetSQLValueString("%" . $colinputtitle_Recordset1 . "%", "text"),
							GetSQLValueString($colcreate_Recordset1 , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($endday , "text")
							);
$Reclog = mysql_query($query_Reclog, $tankdb) or die(mysql_error());

$strslog=null;
while($row_Reclog=mysql_fetch_array($Reclog)){
$rowstatus = str_replace("'",   "\'",   $row_Reclog['task_status_display']);

$strtext =   htmlspecialchars($row_Reclog['csa_tb_text']);
$strtext =  stripslashes($strtext);
$strtext = str_replace("\n",   "<br>",   $strtext);  
$strtext = str_replace("\r",   "",   $strtext);  
$strtext = str_replace("  ",   "&nbsp;",   $strtext); 
$strtext = str_replace("'",   " ",   $strtext); 

$strtexttip =   htmlspecialchars($row_Reclog['csa_tb_text']);
$strtexttip =  stripslashes($strtexttip);
$strtexttip = str_replace("\n",   " ",   $strtexttip);  
$strtexttip = str_replace("\r",   " ",   $strtexttip);  
$strtexttip = str_replace("'",   " ",   $strtexttip); 


$logyear = str_split($row_Reclog['csa_tb_year'],4);
$logmonth = str_split($logyear[1],2);
$logdate = $logyear[0]."-".$logmonth[0]."-".$logmonth[1];

$strslog.="var "."d".$row_Reclog['TID'].$row_Reclog['csa_tb_year']."="."'<span title=\'$logdate $strtexttip\'>"."$rowstatus"."</span>"."</td><td width=\'30px\'  class=\'week_style_padtd\'>".$row_Reclog['csa_tb_manhour']."'; ";
}
} else {$strslog=null;}

mysql_select_db($database_tankdb, $tankdb);
$query_Sumlog = sprintf("SELECT *,
							sum(csa_tb_manhour) as sumhour							
							FROM tk_task  
							
							inner join tk_task_byday on tk_task.TID=tk_task_byday.csa_tb_backup1 
							inner join tk_status on tk_task.csa_remark2=tk_status.id 
							inner join tk_status as tk_status1 on tk_task_byday.csa_tb_status=tk_status1.id

							WHERE tk_task.csa_to_user LIKE %s 
							AND tk_task.csa_from_user LIKE %s 
							AND tk_task.csa_priority LIKE %s 
							AND tk_task.csa_temp LIKE %s 

							AND tk_status.task_status LIKE %s 
							
							AND tk_task.csa_type LIKE %s 
							AND tk_task.csa_project LIKE %s 			
							AND tk_task.TID LIKE %s 
							AND tk_task.csa_text LIKE %s
							AND tk_task.csa_create_user LIKE %s

							AND (tk_task.csa_plan_st <=%s
 							AND tk_task.csa_plan_et >=%s
							OR tk_task.csa_plan_st <=%s
 							AND tk_task.csa_plan_et >=%s
							OR tk_task.csa_plan_st >=%s
 							AND tk_task.csa_plan_et <=%s)
														
							GROUP BY TID", 
							
							GetSQLValueString($colname_Recordset1 , "text"), 
							GetSQLValueString($colfromuser_Recordset1 , "text"),  
							GetSQLValueString("%" . $colprt_Recordset1 . "%", "text"), 
							GetSQLValueString("%" . $coltemp_Recordset1 . "%", "text"), 
							GetSQLValueString("%" . $colstatus_Recordset1 . "%", "text"),  
							
							GetSQLValueString("%" . $coltype_Recordset1 . "%", "text"),
							GetSQLValueString("%" . $colproject_Recordset1 . "%", "text"),
							GetSQLValueString("%" . $colinputid_Recordset1 . "%", "text"),
							GetSQLValueString("%" . $colinputtitle_Recordset1 . "%", "text"),
							GetSQLValueString($colcreate_Recordset1 , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($endday , "text")
							);
$Sumlog = mysql_query($query_Sumlog, $tankdb) or die(mysql_error());

$strssumlog=null;
while($row_Sumlog=mysql_fetch_array($Sumlog)){
$strssumlog.="var "."sum".$row_Sumlog['TID']."='".$row_Sumlog['sumhour']."'; ";
}

mysql_select_db($database_tankdb, $tankdb);
$query_Sumtotal = sprintf("SELECT sum(csa_tb_manhour) as sumtotal							
							FROM tk_task  
							
							inner join tk_task_byday on tk_task.TID=tk_task_byday.csa_tb_backup1
							inner join tk_status on tk_task.csa_remark2=tk_status.id 
							inner join tk_status as tk_status1 on tk_task_byday.csa_tb_status=tk_status1.id

							WHERE tk_task.csa_to_user LIKE %s 
							AND tk_task.csa_from_user LIKE %s 
							AND tk_task.csa_priority LIKE %s 
							AND tk_task.csa_temp LIKE %s 

							AND tk_status.task_status LIKE %s 
							AND tk_status.task_status NOT LIKE %s 

							AND tk_task.csa_type LIKE %s 
							AND tk_task.csa_project LIKE %s 
							AND tk_task.TID LIKE %s 
							AND tk_task.csa_text LIKE %s
							AND tk_task.csa_create_user LIKE %s

							AND (tk_task.csa_plan_st <=%s
 							AND tk_task.csa_plan_et >=%s
							OR tk_task.csa_plan_st <=%s
 							AND tk_task.csa_plan_et >=%s
							OR tk_task.csa_plan_st >=%s
 							AND tk_task.csa_plan_et <=%s)													
							", 
							
							GetSQLValueString($colname_Recordset1 , "text"), 
							GetSQLValueString($colfromuser_Recordset1 , "text"),  
							GetSQLValueString("%" . $colprt_Recordset1 . "%", "text"), 
							GetSQLValueString("%" . $coltemp_Recordset1 . "%", "text"), 
							GetSQLValueString("%" . $colstatus_Recordset1 . "%", "text"),  
							GetSQLValueString("%" . $colstatusf_Recordset1 . "%", "text"),  
							GetSQLValueString("%" . $coltype_Recordset1 . "%", "text"),
							GetSQLValueString("%" . $colproject_Recordset1 . "%", "text"),
							GetSQLValueString("%" . $colinputid_Recordset1 . "%", "text"),
							GetSQLValueString("%" . $colinputtitle_Recordset1 . "%", "text"),
							GetSQLValueString($colcreate_Recordset1 , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($endday , "text")
							);
$Sumtotal = mysql_query($query_Sumtotal, $tankdb) or die(mysql_error());
$row_Sumtotal = mysql_fetch_assoc($Sumtotal);

if($YEAR <> "0000" && $MONTH <> "00"){
mysql_select_db($database_tankdb, $tankdb);
$query_Sumbyday = sprintf("SELECT *, sum(csa_tb_manhour) as Sumbyday							
							FROM tk_task  
							
							inner join tk_task_byday on tk_task.TID=tk_task_byday.csa_tb_backup1 
							inner join tk_status on tk_task.csa_remark2=tk_status.id 
							inner join tk_status as tk_status1 on tk_task_byday.csa_tb_status=tk_status1.id 

							WHERE tk_task.csa_to_user LIKE %s 
							AND tk_task.csa_from_user LIKE %s 
							AND tk_task.csa_priority LIKE %s 
							AND tk_task.csa_temp LIKE %s 

							AND tk_status.task_status LIKE %s 
							AND tk_status.task_status NOT LIKE %s 

							AND tk_task.csa_type LIKE %s 
							AND tk_task.csa_project LIKE %s 
							AND tk_task.TID LIKE %s 
							AND tk_task.csa_text LIKE %s
							AND tk_task.csa_create_user LIKE %s

							AND (tk_task.csa_plan_st <=%s
 							AND tk_task.csa_plan_et >=%s
							OR tk_task.csa_plan_st <=%s
 							AND tk_task.csa_plan_et >=%s
							OR tk_task.csa_plan_st >=%s
 							AND tk_task.csa_plan_et <=%s)													
							GROUP BY csa_tb_year", 
							
							GetSQLValueString($colname_Recordset1 , "text"), 
							GetSQLValueString($colfromuser_Recordset1 , "text"),  
							GetSQLValueString("%" . $colprt_Recordset1 . "%", "text"), 
							GetSQLValueString("%" . $coltemp_Recordset1 . "%", "text"), 
							GetSQLValueString("%" . $colstatus_Recordset1 . "%", "text"),  
							GetSQLValueString("%" . $colstatusf_Recordset1 . "%", "text"),  
							GetSQLValueString("%" . $coltype_Recordset1 . "%", "text"),
							GetSQLValueString("%" . $colproject_Recordset1 . "%", "text"),
							GetSQLValueString("%" . $colinputid_Recordset1 . "%", "text"),
							GetSQLValueString("%" . $colinputtitle_Recordset1 . "%", "text"),
							GetSQLValueString($colcreate_Recordset1 , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($endday , "text"),
							GetSQLValueString($startday , "text"),
							GetSQLValueString($endday , "text")
							);
$Sumbyday = mysql_query($query_Sumbyday, $tankdb) or die(mysql_error());
$strssumbyday=null;
while($row_Sumbyday=mysql_fetch_array($Sumbyday)){
$strssumbyday.="var "."sumbd".$row_Sumbyday['csa_tb_year']."='".$row_Sumbyday['Sumbyday']."'; ";
} 
} else {$strssumbyday=null;}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WSS</title>
<script type="text/JavaScript">
<!--
function GP_popupConfirmMsg(msg) { //v1.0
  document.MM_returnValue = confirm(msg);
}
//-->
</script>
<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/custom.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/lhgdialog.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/lhgcore.js"></script>
<script type="text/javascript" src="srcipt/lhgdialog.js"></script>
<script type="text/javascript" src="srcipt/jquery.js"></script>
<script type="text/javascript" src="srcipt/js.js"></script>
<script type="text/javascript" src="srcipt/jqueryd.js"></script>


<script type="text/javascript" src="skin/jquery-1.3.2.js"></script>
<script type="text/javascript" src="skin/ui/ui.core.js"></script>

<script>

function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
</script>
<script type="text/javascript">
<?php 
echo $strssumlog;
echo $strslog; 
echo $strssumbyday;?>
</script>


</head>

<body>

<?php if ($totalRows_Recordset1 > 0) { // Show if recordset not empty ?>

<table class="rowcon" border="0" align="center">

<td>  <table border="0">
    <tr>
      <td>
        <input name="button2" type="submit" id="button2" onClick="MM_goToURL('parent','default_task_add.php');return document.MM_returnValue" value="<?php echo $multilingual_default_newtask; ?>"  class="button"  >
 </td>
      <td>
        &nbsp;&nbsp;&nbsp;<?php echo $multilingual_default_shortcut; ?> 
        <a href="default_task.php?select=&select_project=&select_year=--&textfield=--&select3=-1&select4=<?php echo "{$_SESSION['MM_Username']}"; ?>&select_project_sub1=-1&select_project_sub2=&select_prt=&select_temp=&select_fd=&select_st=&inputtitle=&select1=-1&select2=%&create_by=%&select_type=" ><?php echo $multilingual_default_tome; ?></a>
		
		<a href="default_task.php?select=&select_project=&select_year=--&textfield=--&select3=-1&select4=%&select_project_sub1=-1&select_project_sub2=&select_prt=&select_temp=&select_fd=&select_st=&inputtitle=&select1=-1&select2=<?php echo "{$_SESSION['MM_Username']}"; ?>&select_type=&inputid="  ><?php echo $multilingual_default_fromme; ?></a>
		
		<a href="default_task.php?select=&select_project=&select_year=--&textfield=--&select3=-1&select4=%&select_project_sub1=-1&select_project_sub2=&select_prt=&select_temp=&select_fd=&select_st=&inputtitle=&select1=-1&select2=%&create_by=<?php echo "{$_SESSION['MM_Username']}"; ?>&select_type=&inputid=" ><?php echo $multilingual_default_createme; ?></a></td>
    </tr>
  </table></td>
<td align="right"><?php echo ($startRow_Recordset1 + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset1 + $maxRows_Recordset1, $totalRows_Recordset1) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset1 ?>)</td>
</tr>
</table>

<div class="tasktab">
<div class="clearboth"></div>
<div class="condition">
<span>
 <?php echo $multilingual_tasktype_condition; ?>
		<?php if ($colproject_Recordset1 <> null) { // Show if recordset not empty ?>
		&nbsp;&nbsp;<b><?php echo $multilingual_default_taskproject; ?></b> <?php echo $row_Recordset1['project_name_prt']; ?>
		<?php } // Show if recordset empty ?>

		<?php if ($colname_Recordset1 <> "%") { // Show if recordset not empty ?>
		&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $multilingual_default_taskto; ?></b> <?php echo $row_Recordset1['tk_display_name1']; ?>
		<?php } // Show if recordset empty ?>

		<?php if ($colfromuser_Recordset1 <> "%") { // Show if recordset not empty ?>
		&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $multilingual_default_taskfrom; ?></b> <?php echo $row_Recordset1['tk_display_name2']; ?>
		<?php } // Show if recordset empty ?> 

		<?php if ($colcreate_Recordset1 <> "%") { // Show if recordset not empty ?>
		&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $multilingual_default_taskcreate; ?></b> <?php echo $row_Recordset1['tk_display_name3']; ?>
		<?php } // Show if recordset empty ?> 
        
        <?php if ($colyear_Recordset1 <> null) { // Show if recordset not empty ?>
		&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $multilingual_default_taskyear; ?></b> <?php echo $colyear_Recordset1; ?>
		<?php } // Show if recordset empty ?> 
        
        <?php if ($colmonth_Recordset1 <> null) { // Show if recordset not empty ?>
		&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $multilingual_default_taskmonth; ?></b> <?php echo $colmonth_Recordset1; ?>
		<?php } // Show if recordset empty ?> 
        
        <?php if ($coltype_Recordset1 <> null) { // Show if recordset not empty ?>
		&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $multilingual_default_tasktype; ?></b> <?php echo $row_Recordset1['task_tpye']; ?>
		<?php } // Show if recordset empty ?> 
        
        <?php if ($colstatus_Recordset1 <> null) { // Show if recordset not empty ?>
		&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $multilingual_default_taskstatus; ?></b> <?php echo $colstatus_Recordset1; ?>
		<?php } // Show if recordset empty ?> 
        
        <?php if ($colprt_Recordset1 <> null) { // Show if recordset not empty ?>
		&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $multilingual_default_taskpriority; ?></b> <?php echo $colprt_Recordset1; ?>
		<?php } // Show if recordset empty ?> 
        
        <?php if ($coltemp_Recordset1 <> null) { // Show if recordset not empty ?>
		&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $multilingual_default_tasktemp; ?></b> <?php echo $coltemp_Recordset1; ?>
		<?php } // Show if recordset empty ?> 
        
        <?php if ($colinputtitle_Recordset1 <> null) { // Show if recordset not empty ?>
		&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $multilingual_default_tasktitle; ?></b> <?php echo $colinputtitle_Recordset1; ?>
		<?php } // Show if recordset empty ?> 
        
        <?php if ($colinputid_Recordset1 <> null) { // Show if recordset not empty ?>
		&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $multilingual_default_taskid; ?></b> <?php echo $colinputid_Recordset1; ?>
		<?php } // Show if recordset empty ?> 

		<?php if ($colstatusf_Recordset1 <> "+") { // Show if recordset not empty ?>
		&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $multilingual_default_taskstatusf; ?>:</b> <?php echo $multilingual_dd_whether_yes; ?>
		<?php } // Show if recordset empty ?> 
</span>
</div>
<div class="tbody_bl" id="tbody_bl">

<table  border="0" cellspacing="0" cellpadding="0" align="center"  class="maintable tasktab_bl" >
<tbody>
 <thead  class="toptable tasktab_tl" >
    <tr>
      <th width="15%;"  class="tasktab_height" ><?php echo $multilingual_default_task_id; ?></th>      
      <th width="65%;" ><div  class="text_overflow_150"><?php echo $multilingual_default_task_title; ?></div></th>
      <th width="20%;"><?php echo $multilingual_default_task_to; ?></th>
      </tr>
   </thead>
        <tr >
          <td colspan="3" class="fontbold weekend_style"><?php echo $multilingual_default_task_totalhour; ?></td>
        </tr>
 <?php do { ?>
        <tr  title="<?php echo $row_Recordset1['csa_text']; ?>" >
      <td class="week_style_padtd"   ><?php echo $row_Recordset1['TID']; ?></td>
      <td class="week_style_padtd" ><div  class="text_overflow_150  task_title"><a href="default_task_edit.php?editID=<?php echo $row_Recordset1['TID']; ?>"  target="_parent"><b>[<?php echo $row_Recordset1['task_tpye']; ?>]</b> <?php echo $row_Recordset1['csa_text']; ?></a></div></td>
      <td class="week_style_padtd" >
	  <a href="user_view.php?recordID=<?php echo $row_Recordset1['csa_to_user']; ?>" target="_parent">
	  <?php echo $row_Recordset1['tk_display_name1']; ?></a></td>
      </tr>
    <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>  
    </tbody>

  </table>
  
</div>
<!-- -->
<div class="tbody_br"  id="tbody_br"  >
  <table  border="0" cellspacing="0" cellpadding="0" align="center"  class="maintable tasktab_br" >
   <tbody>
     <thead  class="toptable " >
    <tr>
      
      <th rowspan="2" width="53px" ><?php echo $multilingual_default_task_manhour; ?></th>
      <th rowspan="2" width="100px"><?php echo $multilingual_default_task_status; ?></th>
	  <th rowspan="2" width="110px"><?php echo $multilingual_default_task_planstart; ?></th>
      <th rowspan="2" width="110px"><?php echo $multilingual_default_task_planend; ?></th>
      <th rowspan="2" width="200px"><?php echo $multilingual_default_task_project; ?></th>

      <th rowspan="2" width="80px"><?php echo $multilingual_default_task_from; ?></th>
      <th rowspan="2" width="60px"><?php echo $multilingual_default_task_priority; ?></th>
      <th rowspan="2" width="40px"><?php echo $multilingual_default_task_temp; ?></th>
      
      
      <th colspan="14" class="weekbig_style">
<?php
$DAY = date("d");
$time = $YEAR."-".$MONTH."-".$DAY;
if (isset($_GET['getday'])) {
  $time = $_GET['getday'];
}
$time = strtotime( $time);
?>
<div class="float_left task_weeklog_div">
<div class="task_weeklog_left">
<?php if($YEAR <> "0000" && $MONTH <> "00"){ ?><a href="default_task.php?<?php echo $url_this; ?>&getday=<?php echo date("y-m-d", strtotime("-1 weeks", $time)); ?>#logsite" class="fleft">
<span class="ui-icon month_pre fleft icon_margen"></span><span class="fleft"><?php echo $multilingual_default_task_preweek; ?></span>
</a><span class="fleft">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php
$Sun = date("Y-m-d", strtotime("last Sunday", $time));
$Sat = date("Y-m-d", strtotime("last Sunday +6 days", $time));
echo $Sun." ".$multilingual_global_to." ".$Sat
?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
<a href="default_task.php?<?php echo $url_this; ?>&getday=<?php echo date("y-m-d", strtotime("+1 weeks", $time)); ?>#logsite"  class="fleft">
<span class="fleft"><?php echo $multilingual_default_task_nextweek; ?></span><span class="ui-icon month_next fleft icon_margen"></span>
</a>
<?php } else { echo $multilingual_calendar_donoedit;} ?>
</div>
</div>
<div class="float_right task_weeklog_right"><a name="logsite">&nbsp;</a></div>
	  </th>
      </tr>
    <tr>
      <th width="111px" colspan="2"><?php echo $multilingual_global_sun; 
if($YEAR <> "0000" && $MONTH <> "00"){
echo date(" m/d", strtotime("last Sunday", $time));}?></th>
      <th width="111px" colspan="2"><?php echo $multilingual_global_mon; 
if($YEAR <> "0000" && $MONTH <> "00"){
echo date(" m/d", strtotime("last Sunday +1 days", $time));}?></th>
      <th width="111px" colspan="2"><?php echo $multilingual_global_tues; 
if($YEAR <> "0000" && $MONTH <> "00"){
echo date(" m/d", strtotime("last Sunday +2 days", $time));}?></th>
      <th width="111px" colspan="2"><?php echo $multilingual_global_wed; 
if($YEAR <> "0000" && $MONTH <> "00"){
echo date(" m/d", strtotime("last Sunday +3 days", $time));}?></th>
      <th width="111px" colspan="2"><?php echo $multilingual_global_thur; 
if($YEAR <> "0000" && $MONTH <> "00"){
echo date(" m/d", strtotime("last Sunday +4 days", $time));}?></th>
      <th width="111px" colspan="2"><?php echo $multilingual_global_fir; 
if($YEAR <> "0000" && $MONTH <> "00"){
echo date(" m/d", strtotime("last Sunday +5 days", $time));}?></th>
      <th width="111px" colspan="2"><?php echo $multilingual_global_sat; 
if($YEAR <> "0000" && $MONTH <> "00"){
echo date(" m/d", strtotime("last Sunday +6 days", $time));}?></th>
    </tr>
   </thead>     

     <tr class="fontbold  weekend_style"> 
       <td class="week_style_padtd">
      <?php echo $row_Sumtotal['sumtotal']; ?>&nbsp;
       </td>
       <td class="week_style_padtd">&nbsp;</td>
       <td class="week_style">&nbsp;</td>
       <td class="week_style_padtd  task_title" >&nbsp;</td>
       <td class="week_style_padtd">&nbsp;</td>
	   <td class="week_style_padtd">&nbsp;</td>
       <td class="week_style_padtd">&nbsp;</td>
       <td class="week_style_padtd">&nbsp;</td>
       
        <td class="week_style_padtd">&nbsp;</td>
       <td  class="week_style week_style_padtd">
<?php
$sumbd = date("Ymd", strtotime("last Sunday", $time));
$out_sumbd = "
<script type='text/javascript'>
if (typeof(sumbd$sumbd)!='undefined'){
document.write(sumbd$sumbd)
}
</script>
";
echo $out_sumbd;
?>
&nbsp;</td>
       <td  class="week_style">&nbsp;</td>
       <td class="week_style week_style_padtd">
<?php
$sumbd = date("Ymd", strtotime("last Sunday +1 days", $time));
$out_sumbd = "
<script type='text/javascript'>
if (typeof(sumbd$sumbd)!='undefined'){
document.write(sumbd$sumbd)
}
</script>
";
echo $out_sumbd;
?>
&nbsp;</td>
       <td >&nbsp;</td>
       <td class="week_style_padtd">
<?php
$sumbd = date("Ymd", strtotime("last Sunday +2 days", $time));
$out_sumbd = "
<script type='text/javascript'>
if (typeof(sumbd$sumbd)!='undefined'){
document.write(sumbd$sumbd)
}
</script>
";
echo $out_sumbd;
?>
&nbsp;</td>
       <td >&nbsp;</td>
       <td class="week_style_padtd">
<?php
$sumbd = date("Ymd", strtotime("last Sunday +3 days", $time));
$out_sumbd = "
<script type='text/javascript'>
if (typeof(sumbd$sumbd)!='undefined'){
document.write(sumbd$sumbd)
}
</script>
";
echo $out_sumbd;
?>
&nbsp;</td>
       <td >&nbsp;</td>
       <td class="week_style_padtd">
<?php
$sumbd = date("Ymd", strtotime("last Sunday +4 days", $time));
$out_sumbd = "
<script type='text/javascript'>
if (typeof(sumbd$sumbd)!='undefined'){
document.write(sumbd$sumbd)
}
</script>
";
echo $out_sumbd;
?>
&nbsp;</td>
       <td class="weekend_style" >&nbsp;</td>
       <td class="week_style_padtd weekend_style">
<?php
$sumbd = date("Ymd", strtotime("last Sunday +5 days", $time));
$out_sumbd = "
<script type='text/javascript'>
if (typeof(sumbd$sumbd)!='undefined'){
document.write(sumbd$sumbd)
}
</script>
";
echo $out_sumbd;
?>
&nbsp;</td>
       <td class="weekend_style"  >&nbsp;</td>
       <td class="week_style_padtd weekend_style">
<?php
$sumbd = date("Ymd", strtotime("last Sunday +6 days", $time));
$out_sumbd = "
<script type='text/javascript'>
if (typeof(sumbd$sumbd)!='undefined'){
document.write(sumbd$sumbd)
}
</script>
";
echo $out_sumbd;
?>
&nbsp;</td>
     </tr>
     <?php do { ?>
     <tr>
       
       <td class="week_style_padtd" width="53px">
<?php
$sumlog_tid = $row_Recordset1['TID'];
$out_sumlog = "
<script type='text/javascript'>
if (typeof(sum$sumlog_tid)!='undefined'){
document.write(sum$sumlog_tid)
} else {
document.write('0')
}
</script>
";
echo $out_sumlog;
?>&nbsp;
</td>
<td class="week_style_padtd"  width="100px"><?php echo $row_Recordset1['task_status_display']; ?></td>
<td class="week_style_padtd" width="70px">
       
       <?php echo $row_Recordset1['csa_plan_st']; ?>&nbsp;
       </td>
<td class="week_style_padtd" width="70px">
       
       <?php echo $row_Recordset1['csa_plan_et']; ?>&nbsp;
       </td>
       <td class="week_style_padtd  task_title"  width="200px" >
         <a href="project_view.php?recordID=<?php echo $row_Recordset1['csa_project']; ?>" target="_parent"><?php echo $row_Recordset1['project_name_prt']; ?></a>
       </td>

       <td class="week_style_padtd"  width="100px">
	   <a href="user_view.php?recordID=<?php echo $row_Recordset1['csa_from_user']; ?>" target="_parent">
	   <?php echo $row_Recordset1['tk_display_name2']; ?></a>
	   </td>
       <td class="week_style_padtd" width="50px"><?php echo $row_Recordset1['csa_priority']; ?></td>
       <td class="week_style_padtd" width="40px"><?php echo $row_Recordset1['csa_temp']; ?></td>
      
       <td width="80px" class="weekend_style week_style" >

<?php
$row_tid = $row_Recordset1['TID'];
$row_userid = $row_Recordset1['csa_to_user'];
$row_pid = $row_Recordset1['csa_project'];
$row_type = $row_Recordset1['csa_type'];
$nowuser = $_SESSION['MM_Username'];

$m1day1 = date("Ymd", strtotime("last Sunday", $time));
$m1day1d = date("Y-m-d", strtotime("last Sunday", $time));

$out_row = "
<script type='text/javascript'>
function op$row_tid$m1day1()
{
    J.dialog.get({ id: 'test', title: '$multilingual_default_task_section5', page: 'log_add.php?date=$m1day1&taskid=$row_tid&userid=$row_userid&projectid=$row_pid&tasktype=$row_type' });
}

function ed$row_tid$m1day1()
{
    J.dialog.get({ id: 'test', title: '$multilingual_default_task_section5', page: 'log_edit.php?date=$m1day1&taskid=$row_tid' });
}

function vi$row_tid$m1day1()
{
    J.dialog.get({ id: 'test', title: '$multilingual_default_task_section5', page: 'log_view.php?date=$m1day1&taskid=$row_tid' });
}
</script>

<script type='text/javascript'>
if ($YEAR == '0000' || $MONTH == '00')
{
document.write('<div title=\'$multilingual_calendar_donoedit\'>&nbsp;</div></td><td width=\'30px\' class=\'week_style_padtd weekend_style\'>&nbsp;')
}
else if (typeof(d$row_tid$m1day1)=='undefined' && '$nowuser' == '$row_userid' )
{
document.write('<div onclick=\'op$row_tid$m1day1();\' title=\'$m1day1d $multilingual_calendar_addlog\' class=\'day_mouse_nul\'>&nbsp;</div></td><td width=\'30px\' class=\'week_style_padtd weekend_style\'>&nbsp;')
}
else if ('$nowuser' == '$row_userid')
{
document.write('<div onclick=\'ed$row_tid$m1day1();\' title=\'$m1day1d $multilingual_calendar_editlog\' class=\'day_mouse\'>')
document.write(d$row_tid$m1day1)
document.write('</div>')
}
else if (typeof(d$row_tid$m1day1)=='undefined')
{
document.write('<div title=\'$m1day1d $multilingual_calendar_others\'>&nbsp;</div></td><td width=\'30px\' class=\'week_style_padtd weekend_style\'>&nbsp;')
}
else
{
document.write('<div onclick=\'vi$row_tid$m1day1();\' title=\'$m1day1d $multilingual_calendar_view\' class=\'day_mouse\'>')
document.write(d$row_tid$m1day1)
document.write('</div>')
}
</script>
";
echo $out_row;
?>
	   </td>
       <td width="80px" class="week_style">
	   <?php
$m1day1 = date("Ymd", strtotime("last Sunday +1 days", $time));
$m1day1d = date("Y-m-d", strtotime("last Sunday +1 days", $time));

$out_row = "
<script type='text/javascript'>
function op$row_tid$m1day1()
{
    J.dialog.get({ id: 'test', title: '$multilingual_default_task_section5', page: 'log_add.php?date=$m1day1&taskid=$row_tid&userid=$row_userid&projectid=$row_pid&tasktype=$row_type' });
}

function ed$row_tid$m1day1()
{
    J.dialog.get({ id: 'test', title: '$multilingual_default_task_section5', page: 'log_edit.php?date=$m1day1&taskid=$row_tid' });
}

function vi$row_tid$m1day1()
{
    J.dialog.get({ id: 'test', title: '$multilingual_default_task_section5', page: 'log_view.php?date=$m1day1&taskid=$row_tid' });
}
</script>

<script type='text/javascript'>
if ($YEAR == '0000' || $MONTH == '00')
{
document.write('<div title=\'$multilingual_calendar_donoedit\'>&nbsp;</div></td><td width=\'30px\' class=\'week_style_padtd\'>&nbsp;')
}
else if (typeof(d$row_tid$m1day1)=='undefined' && '$nowuser' == '$row_userid' )
{
document.write('<div onclick=\'op$row_tid$m1day1();\' title=\'$m1day1d $multilingual_calendar_addlog\' class=\'day_mouse_nul\'>&nbsp;</div></td><td width=\'30px\' class=\'week_style_padtd\'>&nbsp;')
}
else if ('$nowuser' == '$row_userid')
{
document.write('<div onclick=\'ed$row_tid$m1day1();\' title=\'$m1day1d $multilingual_calendar_editlog\' class=\'day_mouse\'>')
document.write(d$row_tid$m1day1)
document.write('</div>')
}
else if (typeof(d$row_tid$m1day1)=='undefined')
{
document.write('<div title=\'$m1day1d $multilingual_calendar_others\'>&nbsp;</div></td><td width=\'30px\' class=\'week_style_padtd\'>&nbsp;')
}
else
{
document.write('<div onclick=\'vi$row_tid$m1day1();\' title=\'$m1day1d $multilingual_calendar_view\' class=\'day_mouse\'>')
document.write(d$row_tid$m1day1)
document.write('</div>')
}
</script>
";
echo $out_row;
?>
	   </td>
       <td width="80px">
	   <?php
$m1day1 = date("Ymd", strtotime("last Sunday +2 days", $time));
$m1day1d = date("Y-m-d", strtotime("last Sunday +2 days", $time));

$out_row = "
<script type='text/javascript'>
function op$row_tid$m1day1()
{
    J.dialog.get({ id: 'test', title: '$multilingual_default_task_section5', page: 'log_add.php?date=$m1day1&taskid=$row_tid&userid=$row_userid&projectid=$row_pid&tasktype=$row_type' });
}

function ed$row_tid$m1day1()
{
    J.dialog.get({ id: 'test', title: '$multilingual_default_task_section5', page: 'log_edit.php?date=$m1day1&taskid=$row_tid' });
}

function vi$row_tid$m1day1()
{
    J.dialog.get({ id: 'test', title: '$multilingual_default_task_section5', page: 'log_view.php?date=$m1day1&taskid=$row_tid' });
}
</script>

<script type='text/javascript'>
if ($YEAR == '0000' || $MONTH == '00')
{
document.write('<div title=\'$multilingual_calendar_donoedit\'>&nbsp;</div></td><td width=\'30px\' class=\'week_style_padtd\'>&nbsp;')
}
else if (typeof(d$row_tid$m1day1)=='undefined' && '$nowuser' == '$row_userid' )
{
document.write('<div onclick=\'op$row_tid$m1day1();\' title=\'$m1day1d $multilingual_calendar_addlog\' class=\'day_mouse_nul\'>&nbsp;</div></td><td width=\'30px\' class=\'week_style_padtd\'>&nbsp;')
}
else if ('$nowuser' == '$row_userid')
{
document.write('<div onclick=\'ed$row_tid$m1day1();\' title=\'$m1day1d $multilingual_calendar_editlog\' class=\'day_mouse\'>')
document.write(d$row_tid$m1day1)
document.write('</div>')
}
else if (typeof(d$row_tid$m1day1)=='undefined')
{
document.write('<div title=\'$m1day1d $multilingual_calendar_others\'>&nbsp;</div></td><td width=\'30px\' class=\'week_style_padtd\'>&nbsp;')
}
else
{
document.write('<div onclick=\'vi$row_tid$m1day1();\' title=\'$m1day1d $multilingual_calendar_view\' class=\'day_mouse\'>')
document.write(d$row_tid$m1day1)
document.write('</div>')
}
</script>
";
echo $out_row;
?>
       </td>
       <td width="80px">
	   <?php
$m1day1 = date("Ymd", strtotime("last Sunday +3 days", $time));
$m1day1d = date("Y-m-d", strtotime("last Sunday +3 days", $time));

$out_row = "
<script type='text/javascript'>
function op$row_tid$m1day1()
{
    J.dialog.get({ id: 'test', title: '$multilingual_default_task_section5', page: 'log_add.php?date=$m1day1&taskid=$row_tid&userid=$row_userid&projectid=$row_pid&tasktype=$row_type' });
}

function ed$row_tid$m1day1()
{
    J.dialog.get({ id: 'test', title: '$multilingual_default_task_section5', page: 'log_edit.php?date=$m1day1&taskid=$row_tid' });
}

function vi$row_tid$m1day1()
{
    J.dialog.get({ id: 'test', title: '$multilingual_default_task_section5', page: 'log_view.php?date=$m1day1&taskid=$row_tid' });
}
</script>

<script type='text/javascript'>
if ($YEAR == '0000' || $MONTH == '00')
{
document.write('<div title=\'$multilingual_calendar_donoedit\'>&nbsp;</div></td><td width=\'30px\' class=\'week_style_padtd\'>&nbsp;')
}
else if (typeof(d$row_tid$m1day1)=='undefined' && '$nowuser' == '$row_userid' )
{
document.write('<div onclick=\'op$row_tid$m1day1();\' title=\'$m1day1d $multilingual_calendar_addlog\' class=\'day_mouse_nul\'>&nbsp;</div></td><td width=\'30px\' class=\'week_style_padtd\'>&nbsp;')
}
else if ('$nowuser' == '$row_userid')
{
document.write('<div onclick=\'ed$row_tid$m1day1();\' title=\'$m1day1d $multilingual_calendar_editlog\' class=\'day_mouse\'>')
document.write(d$row_tid$m1day1)
document.write('</div>')
}
else if (typeof(d$row_tid$m1day1)=='undefined')
{
document.write('<div title=\'$m1day1d $multilingual_calendar_others\'>&nbsp;</div></td><td width=\'30px\' class=\'week_style_padtd\'>&nbsp;')
}
else
{
document.write('<div onclick=\'vi$row_tid$m1day1();\' title=\'$m1day1d $multilingual_calendar_view\' class=\'day_mouse\'>')
document.write(d$row_tid$m1day1)
document.write('</div>')
}
</script>
";
echo $out_row;
?>
       </td>
       <td width="80px" >
	   <?php
$m1day1 = date("Ymd", strtotime("last Sunday +4 days", $time));
$m1day1d = date("Y-m-d", strtotime("last Sunday +4 days", $time));

$out_row = "
<script type='text/javascript'>
function op$row_tid$m1day1()
{
    J.dialog.get({ id: 'test', title: '$multilingual_default_task_section5', page: 'log_add.php?date=$m1day1&taskid=$row_tid&userid=$row_userid&projectid=$row_pid&tasktype=$row_type' });
}

function ed$row_tid$m1day1()
{
    J.dialog.get({ id: 'test', title: '$multilingual_default_task_section5', page: 'log_edit.php?date=$m1day1&taskid=$row_tid' });
}

function vi$row_tid$m1day1()
{
    J.dialog.get({ id: 'test', title: '$multilingual_default_task_section5', page: 'log_view.php?date=$m1day1&taskid=$row_tid' });
}
</script>

<script type='text/javascript'>
if ($YEAR == '0000' || $MONTH == '00')
{
document.write('<div title=\'$multilingual_calendar_donoedit\'>&nbsp;</div></td><td width=\'30px\' class=\'week_style_padtd\'>&nbsp;')
}
else if (typeof(d$row_tid$m1day1)=='undefined' && '$nowuser' == '$row_userid' )
{
document.write('<div onclick=\'op$row_tid$m1day1();\' title=\'$m1day1d $multilingual_calendar_addlog\' class=\'day_mouse_nul\'>&nbsp;</div></td><td width=\'30px\' class=\'week_style_padtd\'>&nbsp;')
}
else if ('$nowuser' == '$row_userid')
{
document.write('<div onclick=\'ed$row_tid$m1day1();\' title=\'$m1day1d $multilingual_calendar_editlog\' class=\'day_mouse\'>')
document.write(d$row_tid$m1day1)
document.write('</div>')
}
else if (typeof(d$row_tid$m1day1)=='undefined')
{
document.write('<div title=\'$m1day1d $multilingual_calendar_others\'>&nbsp;</div></td><td width=\'30px\' class=\'week_style_padtd\'>&nbsp;')
}
else
{
document.write('<div onclick=\'vi$row_tid$m1day1();\' title=\'$m1day1d $multilingual_calendar_view\' class=\'day_mouse\'>')
document.write(d$row_tid$m1day1)
document.write('</div>')
}
</script>
";
echo $out_row;
?>
       </td>
       <td width="80px">
	   <?php
$m1day1 = date("Ymd", strtotime("last Sunday +5 days", $time));
$m1day1d = date("Y-m-d", strtotime("last Sunday +5 days", $time));

$out_row = "
<script type='text/javascript'>
function op$row_tid$m1day1()
{
    J.dialog.get({ id: 'test', title: '$multilingual_default_task_section5', page: 'log_add.php?date=$m1day1&taskid=$row_tid&userid=$row_userid&projectid=$row_pid&tasktype=$row_type' });
}

function ed$row_tid$m1day1()
{
    J.dialog.get({ id: 'test', title: '$multilingual_default_task_section5', page: 'log_edit.php?date=$m1day1&taskid=$row_tid' });
}

function vi$row_tid$m1day1()
{
    J.dialog.get({ id: 'test', title: '$multilingual_default_task_section5', page: 'log_view.php?date=$m1day1&taskid=$row_tid' });
}
</script>

<script type='text/javascript'>
if ($YEAR == '0000' || $MONTH == '00')
{
document.write('<div title=\'$multilingual_calendar_donoedit\'>&nbsp;</div></td><td width=\'30px\' class=\'week_style_padtd\'>&nbsp;')
}
else if (typeof(d$row_tid$m1day1)=='undefined' && '$nowuser' == '$row_userid' )
{
document.write('<div onclick=\'op$row_tid$m1day1();\' title=\'$m1day1d $multilingual_calendar_addlog\' class=\'day_mouse_nul\'>&nbsp;</div></td><td width=\'30px\' class=\'week_style_padtd\'>&nbsp;')
}
else if ('$nowuser' == '$row_userid')
{
document.write('<div onclick=\'ed$row_tid$m1day1();\' title=\'$m1day1d $multilingual_calendar_editlog\' class=\'day_mouse\'>')
document.write(d$row_tid$m1day1)
document.write('</div>')
}
else if (typeof(d$row_tid$m1day1)=='undefined')
{
document.write('<div title=\'$m1day1d $multilingual_calendar_others\'>&nbsp;</div></td><td width=\'30px\' class=\'week_style_padtd\'>&nbsp;')
}
else
{
document.write('<div onclick=\'vi$row_tid$m1day1();\' title=\'$m1day1d $multilingual_calendar_view\' class=\'day_mouse\'>')
document.write(d$row_tid$m1day1)
document.write('</div>')
}
</script>
";
echo $out_row;
?>
       </td>
       <td width="80px" class="weekend_style week_style">
	   <?php
$m1day1 = date("Ymd", strtotime("last Sunday +6 days", $time));
$m1day1d = date("Y-m-d", strtotime("last Sunday +6 days", $time));

$out_row = "
<script type='text/javascript'>
function op$row_tid$m1day1()
{
    J.dialog.get({ id: 'test', title: '$multilingual_default_task_section5', page: 'log_add.php?date=$m1day1&taskid=$row_tid&userid=$row_userid&projectid=$row_pid&tasktype=$row_type' });
}

function ed$row_tid$m1day1()
{
    J.dialog.get({ id: 'test', title: '$multilingual_default_task_section5', page: 'log_edit.php?date=$m1day1&taskid=$row_tid' });
}

function vi$row_tid$m1day1()
{
    J.dialog.get({ id: 'test', title: '$multilingual_default_task_section5', page: 'log_view.php?date=$m1day1&taskid=$row_tid' });
}
</script>

<script type='text/javascript'>
if ($YEAR == '0000' || $MONTH == '00')
{
document.write('<div title=\'$multilingual_calendar_donoedit\'>&nbsp;</div></td><td width=\'30px\' class=\'week_style_padtd weekend_style\'>&nbsp;')
}
else if (typeof(d$row_tid$m1day1)=='undefined' && '$nowuser' == '$row_userid' )
{
document.write('<div onclick=\'op$row_tid$m1day1();\' title=\'$m1day1d $multilingual_calendar_addlog\' class=\'day_mouse_nul\'>&nbsp;</div></td><td width=\'30px\' class=\'week_style_padtd weekend_style\'>&nbsp;')
}
else if ('$nowuser' == '$row_userid')
{
document.write('<div onclick=\'ed$row_tid$m1day1();\' title=\'$m1day1d $multilingual_calendar_editlog\' class=\'day_mouse\'>')
document.write(d$row_tid$m1day1)
document.write('</div>')
}
else if (typeof(d$row_tid$m1day1)=='undefined')
{
document.write('<div title=\'$m1day1d $multilingual_calendar_others\'>&nbsp;</div></td><td width=\'30px\' class=\'week_style_padtd weekend_style\'>&nbsp;')
}
else
{
document.write('<div onclick=\'vi$row_tid$m1day1();\' title=\'$m1day1d $multilingual_calendar_view\' class=\'day_mouse\'>')
document.write(d$row_tid$m1day1)
document.write('</div>')
}
</script>
";
echo $out_row;
?>
       </td> 
       </tr>
     <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>  
   </tbody>
  </table>
  

</div>

<div class="clearboth"></div>
</div>

<table class="rowcon" border="0" align="center">
<tr>
<td>  <table border="0">
  <tr>
    <td><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, 0, $queryString_Recordset1); ?>"><?php echo $multilingual_global_first; ?></a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, max(0, $pageNum_Recordset1 - 1), $queryString_Recordset1); ?>"><?php echo $multilingual_global_previous; ?></a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, min($totalPages_Recordset1, $pageNum_Recordset1 + 1), $queryString_Recordset1); ?>"><?php echo $multilingual_global_next; ?></a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, $totalPages_Recordset1, $queryString_Recordset1); ?>"><?php echo $multilingual_global_last; ?></a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
      </td>
<td align="right"><?php echo ($startRow_Recordset1 + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset1 + $maxRows_Recordset1, $totalRows_Recordset1) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset1 ?>)</td>
</tr>
</table>









<?php } // Show if recordset not empty ?>
<?php if ($totalRows_Recordset1 == 0) { // Show if recordset empty ?>
<div class="ui-widget"  style="margin-left:5px;">
<div class="ui-state-highlight fontsize-s" style=" padding: 5px; width:99%;"> 
				<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
	<?php echo $multilingual_default_sorrytipup; ?>&nbsp;<input name="buttonn" type="submit" id="buttonn" onClick="MM_goToURL('parent','default_task_add.php');return document.MM_returnValue" value="<?php echo $multilingual_default_newtask; ?>"  class="button" > <br /><br />
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $multilingual_default_sorrytipdown; ?> 
      <a href="default_task.php?select=&select_project=&select_year=--&textfield=--&select3=-1&select4=<?php echo "{$_SESSION['MM_Username']}"; ?>&select_project_sub1=-1&select_project_sub2=&select_prt=&select_temp=&select_fd=&select_st=&inputtitle=&select1=-1&select2=%&create_by=%&select_type=&inputid="  ><?php echo $multilingual_default_tome; ?></a>

      
      <a href="default_task.php?select=&select_project=&select_year=--&textfield=--&select3=-1&select4=%&select_project_sub1=-1&select_project_sub2=&select_prt=&select_temp=&select_fd=&select_st=&inputtitle=&select1=-1&select2=<?php echo "{$_SESSION['MM_Username']}"; ?>&select_type=&inputid=" ><?php echo $multilingual_default_fromme; ?></a>
      
      <a href="default_task.php?select=&select_project=&select_year=--&textfield=--&select3=-1&select4=%&select_project_sub1=-1&select_project_sub2=&select_prt=&select_temp=&select_fd=&select_st=&inputtitle=&select1=-1&select2=%&create_by=<?php echo "{$_SESSION['MM_Username']}"; ?>&select_type=&inputid=" ><?php echo $multilingual_default_createme; ?></a> 
                  
     
    </div>
    </div>
</div>
<?php } // Show if recordset empty ?>

</body>
</html>