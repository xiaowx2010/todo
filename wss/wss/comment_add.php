<?php require_once('config/tank_config.php'); ?>
<?php require_once('session.php'); ?>
<?php require_once('config/mail_config.php'); ?>
<?php
$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "2") {   
  header("Location: ". $restrictGoTo); 
  exit;
}

$taskid = $_GET['taskid'];
$nowuserid = $_SESSION['MM_uid'];
$nowuser = $_SESSION['MM_Displayname'];

$pid = "-1";
if (isset($_GET['projectid'])) {
  $pid = $_GET['projectid'];
}

$date = "-1";
if (isset($_GET['date'])) {
  $date = $_GET['date'];
}

$tid = "-1";
if (isset($_GET['tid'])) {
  $tid = $_GET['tid'];
}

$ctype = "-1";
if (isset($_GET['type'])) {
  $ctype = $_GET['type'];
}

if ($tid == "-1"){
$taskmid = $taskid;
} else {
$taskmid = $tid;
}

$self_url =  "http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF'];
$self =  substr($self_url , strrpos($self_url , '/') + 1);
$host_url=str_replace($self,'',$self_url);

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

mysql_select_db($database_tankdb, $tankdb);
$query_log = sprintf("SELECT *, 
tk_user1.tk_user_email as tk_user_email1 
FROM tk_task 
inner join tk_user as tk_user1 on tk_task.csa_to_user=tk_user1.uid 
WHERE TID= %s ",GetSQLValueString($taskmid, "text"));
$log = mysql_query($query_log, $tankdb) or die(mysql_error());
$row_log = mysql_fetch_assoc($log);
$totalRows_log = mysql_num_rows($log);

$mailto = $row_log['tk_user_email1']; 
$title = $row_log['csa_text'];  


// *** Redirect if username exists
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ( empty( $_POST['tk_comm_title'] ) ){
$tk_comm_title = "'',";
}else{
$tk_comm_title = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_comm_title']), "text"));
}

if ((isset($_POST["com_insert"])) && ($_POST["com_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tk_comment (tk_comm_title, tk_comm_user, tk_comm_pid, tk_comm_type, tk_comm_text) VALUES ($tk_comm_title %s, %s, %s, '')",
                       GetSQLValueString($nowuserid, "text"),
                       GetSQLValueString($taskid, "text"),
                       GetSQLValueString($ctype, "text"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());


if ($ctype == 3) {
  $updateSQL = sprintf("UPDATE tk_task_byday SET csa_tb_comment=csa_tb_comment+1 WHERE tbid=%s", GetSQLValueString($taskid, "int"));
  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($updateSQL, $tankdb) or die(mysql_error());
}

if ($tid <> "-1"){

  $lyear = $date;
  $lgyear = str_split($lyear,4);
  $lgmonth = str_split($lgyear[1],2);
  $ldate = $lgyear[0]."-".$lgmonth[0]."-".$lgmonth[1];

$marklogtext = $_POST['tk_comm_title'];

$action = $multilingual_log_marklog1.$ldate.$multilingual_log_marklog2.$marklogtext;

$insertSQL2 = sprintf("INSERT INTO tk_log (tk_log_user, tk_log_action, tk_log_type, tk_log_class, tk_log_description) VALUES (%s, %s, %s, 1, ''  )",
                       GetSQLValueString($nowuserid, "text"),
                       GetSQLValueString($action, "text"),
                       GetSQLValueString($taskmid, "text"));  
$Result3 = mysql_query($insertSQL2, $tankdb) or die(mysql_error());

}

if ($date == "-1"){
  $insertGoTo = "log_finish.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
} else {
 $insertGoTo = "log_view.php?date=".$date."&taskid=".$tid."#comment";
}

if ($tid == "-1"){
  $taskurl = "<a href='".$host_url."default_task_edit.php?editID=".$taskmid."'>".$title."</a>";
  $mailtilte = $nowuser." $multilingual_user_addcomment".$title;
  $mailtext = "<b>".$nowuser." $multilingual_user_addcomment ".$taskurl."</b><br /><br />".$_POST['tk_comm_title'];
  } else {
  $taskurl = "<a href='".$host_url."default_task_edit.php?editID=".$taskmid."'>".$title."</a>";
  
  $mailtilte = $nowuser." $multilingual_user_addcomment".$title." (".$ldate.$multilingual_log_marklog2.")";
  
  $logurl = "<a href='".$host_url."log_view.php?date=".$date."&taskid=".$tid."'>"." (".$ldate.$multilingual_log_marklog2.")"."</a>";
  $mailtext = "<b>".$nowuser." $multilingual_user_addcomment ".$taskurl."&nbsp;".$logurl."</b><br /><br />".$_POST['tk_comm_title'];
  }





if ( $mail_service_comment == "on" && $pid <> 1){
wss_post_office($mailto, $mailtilte, $mailtext); }

  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="skin/themes/base/lhgdialog.css" rel="stylesheet" type="text/css" />
	<title>log</title>
	<script type="text/javascript">
var P = window.parent, D = P.loadinndlg();   
function closreload(url)
{
    if(!url)
	    P.reload();    
}
function over()
{
    P.cancel();
}
	</script>
<script charset="utf-8" src="editor/kindeditor.js"></script>
<script charset="utf-8" src="editor/lang/zh_CN.js"></script>
<script>
function submitform()
{
    document.form1.cont.value='<?php echo $multilingual_global_wait; ?>';
	document.form1.cont.disabled=true;
	document.getElementById("btn5").click();
}
<!--
var editor;
        KindEditor.ready(function(K) {
                editor = K.create('#tk_comm_title', {
			width : '100%',
			height: '400px',
			items:[
        'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'cut', 'copy', 'paste',
        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
        'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'forecolor', 'hilitecolor', 'lineheight', 'bold',
        'italic', 'underline', 'strikethrough', 'removeformat', '|',   
        'formatblock', 'fontname', 'fontsize', '|','image',
        'flash', 'media', 'insertfile', 'table', 'hr', 'pagebreak', 'anchor', 
        'link', 'unlink', '|', 'about'
]
});
        });

function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
//-->
</script>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" >
  <table align="center" class="dialog_main">
    <tr>
      <td >
      <textarea name="tk_comm_title" id="tk_comm_title"></textarea>      </td>
    </tr>
    <tr>
      <td align="right" nowrap="nowrap">
	  <span class="dialog_submit">
	  
	  <input name="cont" type="button" value="<?php echo $multilingual_global_action_save; ?>" 
	   <?php if( $_SESSION['MM_Username'] == $multilingual_dd_user_readonly){
	  echo "disabled='disabled'";
	  } ?> 
	  onClick="submitform()" 
	  />
	  
	  <input type="submit"  id="btn5" value="<?php echo $multilingual_global_action_save; ?>"  style="display:none" />
	  
	  <?php if ($date == "-1"){ ?>
      <input id="btn1" type="button" value="<?php echo $multilingual_global_action_cancel; ?>" onclick="over()"/>
	  <?php } else { ?>
	  <input type="button" id="btn12" onclick="MM_goToURL('self','<?php echo "log_view.php?date=".$date."&taskid=".$tid; ?>');return document.MM_returnValue" value="<?php echo $multilingual_global_action_cancel; ?>"/>
	  <?php } ?>
	  </span>	  </td>
    </tr>
  </table>
  <input type="hidden" name="com_insert" value="form1" />
</form>
</body>
</html>