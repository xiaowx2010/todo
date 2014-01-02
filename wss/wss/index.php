<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php 
$url_this = $_SERVER["QUERY_STRING"] ;

$current_url = current(explode("&sort",$url_this));

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

$pagetabs = "mtask";
if (isset($_GET['pagetab'])) {
  $pagetabs = $_GET['pagetab'];
}

$currentPage = $_SERVER["PHP_SELF"];

$taskpage=2;

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_sumtotal = sprintf("SELECT 
							COUNT(*) as count_task   
							FROM tk_task 	
							inner join tk_status on tk_task.csa_remark2=tk_status.id 							
							WHERE csa_from_user = %s AND task_status LIKE %s", 
								GetSQLValueString($_SESSION['MM_uid'], "int"),
								GetSQLValueString("%" . $multilingual_dd_status_exam . "%", "text")
								);
$Recordset_sumtotal = mysql_query($query_Recordset_sumtotal, $tankdb) or die(mysql_error());
$row_Recordset_sumtotal = mysql_fetch_assoc($Recordset_sumtotal);
$exam_totaltask=$row_Recordset_sumtotal['count_task'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WSS - <?php echo $multilingual_default_title; ?></title>

<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/jquery.js"></script>
<script type="text/javascript" src="srcipt/js.js"></script>
<script type="text/javascript" src="srcipt/jqueryd.js"></script>
<script type="text/javascript"> 	  
function TuneHeight()    
{    
var frm = document.getElementById("frame_content");    
var subWeb = document.frames ? document.frames["main_frame"].document : frm.contentDocument;    
if(frm != null && subWeb != null)    
{ frm.height = subWeb.body.scrollHeight;}    
}    

</script>
</head>
<body>
<?php require('head.php'); ?>

<div class="subnav">
<div class="float_left">
  <ul class="subnav_item">
      <li class="
	  <?php if($pagetabs == "mtask") {
	  echo "subonhover";} ?>
	  "><a href="<?php echo $pagename; ?>?select=&select_project=&select_year=<?php echo date("Y");?>&textfield=<?php echo date("m");?>&select3=-1&select4=<?php echo "{$_SESSION['MM_uid']}"; ?>&select_prt=&select_temp=&inputtitle=&select1=-1&select2=%&create_by=%&select_type=&inputid=&inputtag="><?php echo $multilingual_user_mytask;?> </a></li>
	  
	  <li class="
	  <?php if($pagetabs == "ftask") {
	  echo "subonhover";} ?>
	  " ><a href="<?php echo $pagename; ?>?select=&select_project=&select_year=<?php echo date("Y");?>&textfield=<?php echo date("m");?>&select3=-1&select4=%&select_prt=&select_temp=&inputtitle=&select1=-1&select2=<?php echo "{$_SESSION['MM_uid']}"; ?>&select_type=&inputid=&inputtag=&pagetab=ftask"><?php echo $multilingual_default_fromme;?></a></li>
	  
	  <li class="
	  <?php if($pagetabs == "ctask") {
	  echo "subonhover";} ?>
	  " ><a href="<?php echo $pagename; ?>?select=&select_project=&select_year=<?php echo date("Y");?>&textfield=<?php echo date("m");?>&select3=-1&select4=%&select_prt=&select_temp=&inputtitle=&select1=-1&select2=%&create_by=<?php echo "{$_SESSION['MM_uid']}"; ?>&select_type=&inputid=&inputtag=&pagetab=ctask"><?php echo $multilingual_default_createme;?></a></li>
	  
	  <?php if($exam_totaltask > 0) { ?>
	  <li class="
	  <?php if($pagetabs == "etask") {
	  echo "subonhover";} ?>
	  " ><a href="<?php echo $pagename; ?>?select=&select_project=&select_year=--&textfield=--&select3=-1&select4=%&select_prt=&select_temp=&select_exam=<?php echo $multilingual_dd_status_exam; ?>&inputtitle=&select1=-1&select2=<?php echo "{$_SESSION['MM_uid']}"; ?>&select_type=&inputid=&inputtag=&pagetab=etask"><?php echo $multilingual_exam_wait."(".$exam_totaltask.")"; ?></a></li>
	  <?php } ?>
	  
	  <li class="
	  <?php if($pagetabs == "alltask") {
	  echo "subonhover";} ?>
	  " ><a href="<?php echo $pagename; ?>?select=&select_project=&select_year=<?php echo date("Y");?>&textfield=<?php echo date("m");?>&select3=-1&select4=%&select_prt=&select_temp=&inputtitle=&select1=-1&select2=%&create_by=%&select_type=&inputid=&inputtag=&pagetab=alltask"><?php echo $multilingual_default_alltask;?></a></li>
    </ul>
</div>

<?php if($_SESSION['MM_rank'] > "2" ) { ?>
<div class="float_right newtaskdiv">
        <input name="button2" type="submit" id="button2" onclick="addtask();" value="<?php echo $multilingual_default_newtask; ?>"  class="button"  >
</div>
<?php }  ?> 
<div class="clearboth"></div>
</div>

<div class="pagemargin">
<?php require('control_task.php'); ?>
</div>
<?php require('foot.php'); ?>

</body>
</html>