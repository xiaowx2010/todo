<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php require_once('config/mail_config.php'); ?>
<?php
$self_url =  "http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF'];
$self =  substr($self_url , strrpos($self_url , '/') + 1);
$host_url=str_replace($self,'',$self_url);

$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "3") {   
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


$to_user = "-1";
if (isset($_POST['csa_to_user'])) {
  $to_user= $_POST['csa_to_user'];
}

mysql_select_db($database_tankdb, $tankdb);
$query_touser =  sprintf("SELECT * FROM tk_user WHERE uid = %s",
                       GetSQLValueString($to_user, "int"));  
$touser = mysql_query($query_touser, $tankdb) or die(mysql_error());
$row_touser = mysql_fetch_assoc($touser);
$totalRows_touser = mysql_num_rows($touser);

$mailto = $row_touser['tk_user_email']; 

$title = "-1";
if (isset($_POST['csa_text'])) {
  $title= $_POST['csa_text'];
}

$project_id = "-1";
if (isset($_GET['projectID'])) {
  $project_id = $_GET['projectID'];
}

$project_url = "-1";
if (isset($_GET['formproject'])) {
  $project_url= $_GET['formproject'];
}

$user_id = "-1";
if (isset($_GET['UID'])) {
  $user_id= $_GET['UID'];
}

$user_url = "-1";
if (isset($_GET['touser'])) {
  $user_url= $_GET['touser'];
}

if ( empty( $_POST['plan_hour'] ) )
		$_POST['plan_hour'] = '0.0';

if ( empty( $_POST['csa_remark1'] ) ){
$csa_remark1 = "'',";
}else{
$csa_remark1 = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['csa_remark1']), "text"));
}

if ( empty( $_POST['csa_tag'] ) ){
$csa_tag = "'',";
}else{
$csa_tag = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['csa_tag']), "text"));
}

//for wbs!
$wbs_id = "-1";
if (isset($_GET['wbsID'])) {
  $wbs_id = $_GET['wbsID'];
}

$task_id = "-1";
if (isset($_GET['taskID'])) {
  $task_id = $_GET['taskID'];
}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_task = sprintf("SELECT *, 
tk_project.id as proid  
FROM tk_task 
inner join tk_project on tk_task.csa_project=tk_project.id 
WHERE TID = %s", GetSQLValueString($task_id, "int"));
$Recordset_task = mysql_query($query_Recordset_task, $tankdb) or die(mysql_error());
$row_Recordset_task = mysql_fetch_assoc($Recordset_task);
$totalRows_Recordset_task = mysql_num_rows($Recordset_task);

if ($wbs_id == "2"){
$wbs = $task_id.">".$wbs_id;
} else {
$wbs = $row_Recordset_task['csa_remark5'].">".$row_Recordset_task['TID'].">".$wbs_id; 
}


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tk_task (test02, csa_remark1, csa_from_dept, csa_from_user, csa_to_dept, csa_to_user, csa_project, csa_type, csa_text, csa_priority, csa_temp, csa_plan_st, csa_plan_et, csa_plan_hour, csa_remark2, csa_create_user, csa_last_user, csa_remark4, csa_remark5, csa_remark6, csa_project_sub, csa_remark7, csa_remark8, test01, test03, test04) VALUES ($csa_tag $csa_remark1 %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, '0', '', '', '', '', '')",
                       GetSQLValueString($_POST['csa_from_dept'], "text"),
                       GetSQLValueString($_POST['csa_from_user'], "text"),
                       GetSQLValueString($_POST['csa_to_dept'], "text"),
                       GetSQLValueString($_POST['csa_to_user'], "text"),
                       GetSQLValueString($project_id, "text"),
                       GetSQLValueString($_POST['csa_type'], "text"),
                       GetSQLValueString($_POST['csa_text'], "text"),
                       GetSQLValueString($_POST['csa_priority'], "text"),
                       GetSQLValueString($_POST['csa_temp'], "text"),
					   GetSQLValueString($_POST['plan_start'], "text"),
					   GetSQLValueString($_POST['plan_end'], "text"),
					   GetSQLValueString($_POST['plan_hour'], "text"),
					   GetSQLValueString($_POST['csa_remark2'], "text"),
					   GetSQLValueString($_POST['csa_create_user'], "text"),
					   GetSQLValueString($_POST['csa_last_user'], "text"),
					   GetSQLValueString($task_id, "text"),
					   GetSQLValueString($wbs, "text"),
					   GetSQLValueString($wbs_id, "text"));



  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());
  
  $newID = mysql_insert_id();
  $newName = $_SESSION['MM_uid'];

$insertSQL2 = sprintf("INSERT INTO tk_log (tk_log_user, tk_log_action, tk_log_type, tk_log_class, tk_log_description) VALUES (%s, %s, %s , 1, '' )",
                       GetSQLValueString($newName, "text"),
                       GetSQLValueString($multilingual_log_addtask, "text"),
                       GetSQLValueString($newID, "text"));  
  $Result2 = mysql_query($insertSQL2, $tankdb) or die(mysql_error());

if ($project_url == 1){
$insertGoTo = "project_view.php?recordID=$project_id";
} else if ($user_url == 1){
$insertGoTo = "user_view.php?recordID=$user_id";
}

else {
  $insertGoTo = "default_task_edit.php?editID=$newID";
}


  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  
$taskurl = "<a href='".$host_url."index.php'>".$title."</a>";


if ( $mail_service_create == "on" ){
wss_post_office($mailto, "$multilingual_user_newtask1".$title." $multilingual_user_newtask2", " $multilingual_user_newtask1 ".$taskurl." $multilingual_user_newtask2"); }
  
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_user = "SELECT * FROM tk_user WHERE tk_user_rank <>0  ORDER BY tk_display_name ASC";
$Recordset_user = mysql_query($query_Recordset_user, $tankdb) or die(mysql_error());
$row_Recordset_user = mysql_fetch_assoc($Recordset_user);
$totalRows_Recordset_user = mysql_num_rows($Recordset_user);

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_type = "SELECT * FROM tk_task_tpye ORDER BY task_tpye_backup1 ASC";
$Recordset_type = mysql_query($query_Recordset_type, $tankdb) or die(mysql_error());
$row_Recordset_type = mysql_fetch_assoc($Recordset_type);
$totalRows_Recordset_type = mysql_num_rows($Recordset_type);

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_project = sprintf("SELECT * FROM tk_project WHERE id = %s",
                       GetSQLValueString($project_id, "int"));  
$Recordset_project = mysql_query($query_Recordset_project, $tankdb) or die(mysql_error());
$row_Recordset_project = mysql_fetch_assoc($Recordset_project);
$totalRows_Recordset_project = mysql_num_rows($Recordset_project);

mysql_select_db($database_tankdb, $tankdb);
$query_tkstatus = "SELECT * FROM tk_status WHERE task_status_backup2 <>1 ORDER BY task_status_backup1 ASC";
$tkstatus = mysql_query($query_tkstatus, $tankdb) or die(mysql_error());
$row_tkstatus = mysql_fetch_assoc($tkstatus);
$totalRows_tkstatus = mysql_num_rows($tkstatus);
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WSS - <?php echo $multilingual_taskadd_title; ?></title>
<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/lhgcheck.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/lhgcore.js"></script>
<script type="text/javascript" src="srcipt/lhgcheck.js"></script>


<link type="text/css" href="skin/themes/base/ui.all.css" rel="stylesheet" />
<script type="text/javascript" src="skin/jquery-1.3.2.js"></script>
<script type="text/javascript" src="skin/ui/ui.core.js"></script>
<script type="text/javascript" src="skin/ui/ui.datepicker_<?php echo $language; ?>.js" charset="utf-8"></script>
<script type="text/javascript">
	$(function() {
		$("#datepicker").datepicker({showOn: 'button', buttonImage: 'skin/themes/base/images/calendar.gif', buttonImageOnly: true});
	
	
    $('#datepicker2').datepicker({
			changeMonth: true,
			changeYear: true
		});
		$('#datepicker3').datepicker({
			changeMonth: true,
			changeYear: true
		});
		
		});
    </script>



<script type="text/javascript">
<!--
J.check.rules = [
	{ name: 'select4', mid: 'csa_to_user_msg', requir: true, type: 'group', noselected: '', warn: '<?php echo $multilingual_default_required1; ?>' },
	{ name: 'select2', mid: 'csa_from_user_msg', requir: true, type: 'group', noselected: '', warn: '<?php echo $multilingual_default_required1; ?>' },
	{ name: 'csa_type', mid: 'csa_type_msg', requir: true, type: 'group', noselected: '', warn: '<?php echo $multilingual_default_required3; ?>' },
	{ name: 'datepicker2', mid: 'csa_plan_st_msg', requir: true, type: 'date',  warn: '<?php echo $multilingual_error_date; ?>' },
	{ name: 'datepicker3', mid: 'csa_plan_et_msg', requir: true, type: 'date',  warn: '<?php echo $multilingual_error_date; ?>' },
	{ name: 'csa_text', mid: 'csa_text_msg', requir: true, type: '',  warn: '<?php echo $multilingual_default_required4; ?>'},
	{ name: 'plan_hour', mid: 'plan_hour_msg', type: 'rang', min: 0, warn: '<?php echo $multilingual_default_required5; ?>' }
   
];

window.onload = function()
{
    J.check.regform('myform');
}

function option_gourl(str)
{
if(str == '-1')window.open('task_type_list.php');
if(str == '-2')window.open('user_add.php');
if(str == '-3')window.open('project_add.php');
}
//-->
</script>
<script charset="utf-8" src="editor/kindeditor.js"></script>
<script charset="utf-8" src="editor/lang/zh_CN.js"></script>
<script>
        var editor;
        KindEditor.ready(function(K) {
                editor = K.create('#csa_remark1', {
			width : '99%',
			height: '350px',
			items:[
        'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'cut', 'copy', 'paste',
        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
        'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
        'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
        'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',
        'flash', 'media', 'insertfile', 'table', 'hr', 'map', 'code', 'pagebreak', 'anchor', 
        'link', 'unlink', '|', 'about'
]
});
        });
		
function submitform()
{
    document.myform.cont.value='<?php echo $multilingual_global_wait; ?>';
	document.myform.cont.disabled=true;
	document.getElementById("btn5").click();
}		
</script>
</head>
<body>
<?php require('head.php'); ?>
<br />
<form action="<?php echo $editFormAction; ?>" method="post" name="myform" id="myform">
  <table align="center" class="fontsize-s input_task_table glink">
    <tr>
      <td colspan="2" nowrap="nowrap" valign="bottom">
	  <span class="font_big18 fontbold float_left"><?php echo $multilingual_taskadd_title; ?></span>

	  <div class="structure">
	  <span class="float_left" >
	  <?php echo $multilingual_default_taskproject; ?> <a href="project_view.php?recordID=<?php echo $row_Recordset_project['id']; ?>" ><?php echo $row_Recordset_project['project_name']; ?></a>	  </span>
	  <span class="ui-icon month_next float_left"></span>
	  <?php if ($task_id <> -1) { ?>
	  
	  
	  <span class="float_left">
	   <?php echo $multilingual_default_task_parent; ?>: 
	  <a href="default_task_edit.php?editID=<?php echo $row_Recordset_task['TID']; ?>" ><?php echo $row_Recordset_task['csa_text']; ?></a>	  </span>
	   <?php } else {
	 echo $multilingual_subtask_root;
	  } ?>
	  <span class="clearboth"></span></div></td>
      <td align="right"><span class="red">*</span><?php echo $multilingual_global_required; ?></td>
    </tr>
    <tr valign="baseline">
      <td colspan="3" nowrap="nowrap"><span class="input_task_title margin-y" style="margin-top:0px;"><?php echo $multilingual_default_task_section1; ?></span></td>
    </tr>
    <tr valign="baseline">
      <td width="33%" nowrap="nowrap">
	  <?php echo $multilingual_default_task_to; ?>:<span class="red">*</span><br>
<input type="text" id="select3" name="csa_to_dept" value="0001" style="display:none;" />
          
        <select id="select4" name="csa_to_user" onChange="option_gourl(this.value)">
        <option value="" ><?php echo $multilingual_global_select; ?></option>
          <?php
do {  
?>
          <option value="<?php echo $row_Recordset_user['uid']?>" 
		  <?php if (!(strcmp($row_Recordset_user['uid'], $user_id))) {echo "selected=\"selected\"";} ?>
		  ><?php echo $row_Recordset_user['tk_display_name']?></option>
          <?php
} while ($row_Recordset_user = mysql_fetch_assoc($Recordset_user));
  $rows = mysql_num_rows($Recordset_user);
  if($rows > 0) {
      mysql_data_seek($Recordset_user, 0);
	  $row_Recordset_user = mysql_fetch_assoc($Recordset_user);
  }
?>
<?php if ($_SESSION['MM_rank'] > "4") { ?>
<option value="-2" class="gray" >+<?php echo $multilingual_user_new; ?></option>
<?php } ?>
        </select>
      <span id="csa_to_user_msg"></span>	  </td>
      <td width="33%">&nbsp;</td>
      <td width="34%"><?php echo $multilingual_default_task_from; ?><span class="gray"><?php echo $multilingual_exam_tip; ?></span>:<span class="red">*</span> <br>
        <input type="text" id="select1" name="csa_from_dept" value="0001" style="display:none;"  />
        
        <select id="select2" name="csa_from_user" onChange="option_gourl(this.value)">
          <option value=""  <?php if (!(strcmp("", "{$_SESSION['MM_uid']}"))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_global_select; ?></option>
          <?php
do {  
?>
          <option value="<?php echo $row_Recordset_user['uid']?>"<?php if (!(strcmp($row_Recordset_user['uid'], "{$_SESSION['MM_uid']}"))) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset_user['tk_display_name']?></option>
          <?php
} while ($row_Recordset_user = mysql_fetch_assoc($Recordset_user));
  $rows = mysql_num_rows($Recordset_user);
  if($rows > 0) {
      mysql_data_seek($Recordset_user, 0);
	  $row_Recordset_user = mysql_fetch_assoc($Recordset_user);
  }
?>
<?php if ($_SESSION['MM_rank'] > "4") { ?>
<option value="-2" class="gray" >+<?php echo $multilingual_user_new; ?></option>
<?php } ?>
        </select>
      <span id="csa_from_user_msg"></span>
      <input name="csa_create_user" type="text"  id="csa_create_user" value="<?php echo "{$_SESSION['MM_uid']}"; ?>"  style="display:none"> 
	  <input name="csa_last_user" type="text"  id="csa_last_user" value="<?php echo "{$_SESSION['MM_uid']}"; ?>" style="display:none"></td>
    </tr>
    <tr valign="baseline">
      <td colspan="3" nowrap="nowrap"><span class="input_task_title  margin-y"><?php echo $multilingual_default_task_section2; ?></span></td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" nowrap="nowrap"><?php echo $multilingual_default_task_title; ?>:<span class="red">*</span><span id="csa_text_msg"></span>
      <br><input name="csa_text" id="csa_text" type="text" value="" size="50" class="width-p100-big"><br /><br /></td>
      <td nowrap="nowrap"><?php echo $multilingual_default_task_type; ?>:<span class="red">*</span><br>
        <select name="csa_type" id="csa_type" onChange="option_gourl(this.value)" class="listbox-big">
          <option value=""><?php echo $multilingual_global_select; ?></option>
          <?php
do {  
?>
          <option value="<?php echo $row_Recordset_type['id']?>"><?php echo $row_Recordset_type['task_tpye']?></option>
          <?php
} while ($row_Recordset_type = mysql_fetch_assoc($Recordset_type));
  $rows = mysql_num_rows($Recordset_type);
  if($rows > 0) {
      mysql_data_seek($Recordset_type, 0);
	  $row_Recordset_type = mysql_fetch_assoc($Recordset_type);
  }
?>
<?php if ($_SESSION['MM_rank'] > "4") { ?>
<option value="-1" class="gray" >+<?php echo $multilingual_tasktype_new; ?></option>
<?php } ?>
        </select>
      <span id="csa_type_msg"></span>
	   <b title="<?php echo $multilingual_default_task_catips2; ?>">[?]</b></td>
    </tr>
    <tr valign="baseline">
      <td colspan="3" nowrap="nowrap" class="glink"><?php echo $multilingual_default_task_description; ?>:<br>        <textarea id="csa_remark1" name="csa_remark1" ></textarea>     </td>
    </tr>
    <tr valign="baseline">
      <td colspan="3" >
	  <br /><?php echo $multilingual_default_tasktag; ?>:<br />
<input name="csa_tag" id="csa_tag" type="text" value="" class="width-p100" >	</td>
    </tr>
    <tr valign="baseline">
      <td colspan="3" nowrap="nowrap"><span class="input_task_title  margin-y"><?php echo $multilingual_default_task_others; ?></span></td>
    </tr>
    <tr valign="baseline">
      <td ><?php echo $multilingual_default_task_priority; ?>:<br>
        <select name="csa_priority">
          <option value="5" ><?php echo $multilingual_dd_priority_p5; ?></option>
          <option value="4" ><?php echo $multilingual_dd_priority_p4; ?></option>
          <option value="3" SELECTED=“SELECTED”><?php echo $multilingual_dd_priority_p3; ?></option>
          <option value="2" ><?php echo $multilingual_dd_priority_p2; ?></option>
          <option value="1" ><?php echo $multilingual_dd_priority_p1; ?></option>
      </select></td>
      <td>
	  <?php echo $multilingual_default_tasklevel; ?>:<br>
        <select name="csa_temp">
        <option value="5"><?php echo $multilingual_dd_level_l5; ?></option>
        <option value="4"><?php echo $multilingual_dd_level_l4; ?></option>
		<option value="3" SELECTED=“SELECTED”><?php echo $multilingual_dd_level_l3; ?></option>
		<option value="2"><?php echo $multilingual_dd_level_l2; ?></option>
		<option value="1"><?php echo $multilingual_dd_level_l1; ?></option>
      </select>	  	  </td>
      <td>
	   <?php echo $multilingual_default_taskstatus; ?><br />
	  <select name="csa_remark2" id="csa_remark2" >
                <?php
do {  
?>
                <option value="<?php echo $row_tkstatus['id']?>"><?php echo $row_tkstatus['task_status']?></option>
                <?php
} while ($row_tkstatus = mysql_fetch_assoc($tkstatus));
  $rows = mysql_num_rows($tkstatus);
  if($rows > 0) {
      mysql_data_seek($tkstatus, 0);
	  $row_tkstatus = mysql_fetch_assoc($tkstatus);
  }
?>
</select>	  </td>
    </tr>
    <tr valign="baseline">
      <td colspan="3" nowrap="nowrap"><span class="input_task_title "><?php echo $multilingual_default_task_section4; ?></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap"><?php echo $multilingual_default_task_planstart; ?>:<span class="red">*</span><br>
      <input type="text" name="plan_start" id="datepicker2" value="<?php echo date('Y-m-d'); ?>" size="20"  /><span id="csa_plan_st_msg"></span></td>
      <td nowrap="nowrap"><?php echo $multilingual_default_task_planend; ?>:<span class="red">*</span><br>
      <input type="text" name="plan_end" id="datepicker3" value="<?php echo date('Y-m-d'); ?>" size="20"  /><span id="csa_plan_et_msg"></span></td>
      <td nowrap="nowrap"><?php echo $multilingual_default_task_planhour; ?>:<br>
      <input type="text" name="plan_hour" id="plan_hour"  value="" size="20"  />
      <?php echo $multilingual_global_hour; ?><span id="plan_hour_msg"></span></td>
    </tr>

    <tr valign="baseline">
      <td colspan="3" nowrap="nowrap">
      <span class="input_task_submit">
      <div class="float_right"><input type="submit" value="<?php echo $multilingual_global_action_save; ?>" class="button"
	  <?php if( $_SESSION['MM_Username'] == $multilingual_dd_user_readonly){
	  echo "disabled='disabled'";
	  } ?> 
	   name="cont"
	   />
	   <input type="submit"  id="btn5" value="<?php echo $multilingual_global_action_save; ?>"  style="display:none" />
	   &nbsp;&nbsp;
      <input name="button" type="button" id="button" onClick="javascript:history.go(-1);" value="<?php echo $multilingual_global_action_cancel; ?>"  class="button" /></div>
	  </span>       </td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
<?php require('foot.php'); ?>
</body>
</html>
<?php
mysql_free_result($Recordset_user);
mysql_free_result($Recordset_project);
mysql_free_result($Recordset_type);
?>