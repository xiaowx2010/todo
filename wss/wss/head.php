<?php require_once('config/tank_config.php'); ?>
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

$colname_Recordset_anc = "2";

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_anc = sprintf("SELECT * FROM tk_announcement WHERE tk_anc_type = %s ORDER BY tk_anc_lastupdate DESC", GetSQLValueString($colname_Recordset_anc, "text"));
$Recordset_anc = mysql_query($query_Recordset_anc, $tankdb) or die(mysql_error());
$row_Recordset_anc = mysql_fetch_assoc($Recordset_anc);
$totalRows_Recordset_anc = mysql_num_rows($Recordset_anc);

$self =$_SERVER['PHP_SELF'];
$pagename = end(explode("/",$self));
?>
  <div class="topbar" id="headerlink">
    <div class="logo"><a href="index.php" class="logourl" >&nbsp;</a></div>
    <div class="nav_normal2">
	<a href="index.php" class="
	  <?php if($pagename == "index.php" || $pagename == "default_task_edit.php" || $pagename == "default_task_plan.php" || $pagename == "default_task_add.php") {
	  echo "nav_select";} ?>
	  "><?php echo $multilingual_head_task; ?></a>
	
      <a href="log.php" class="
	  <?php if($pagename == "log.php" ){
	  echo "nav_select";} ?>
	  "><?php echo $multilingual_head_log; ?></a>
	  
      <a href="project.php" class="
	  <?php if($pagename == "project.php" || $pagename == "project_add.php" || $pagename == "project_view.php" || $pagename == "project_edit.php"){
	  echo "nav_select";} ?>
	  "><?php echo $multilingual_head_project; ?></a>
	  
      <a href="file.php" class="
	  <?php if($pagename == "file.php" || $pagename == "file_add.php" || $pagename == "file_project.php" || $pagename == "file_edit.php"){
	  echo "nav_select";} ?>
	  "><?php echo $multilingual_head_file; ?></a>
	  
      <a href="default_user.php" class="
	  <?php if($pagename == "default_user.php" || $pagename == "user_add.php" || $pagename == "user_view.php" || $pagename == "default_user_edit.php"){
	  echo "nav_select";} ?>
	  "><?php echo $multilingual_head_user; ?></a>
	  
      <a href="default_announcement.php" class="
	  <?php if($pagename == "default_announcement.php" || $pagename == "announcement_add.php" || $pagename == "announcement_view.php" || $pagename == "announcement_edit.php"){
	  echo "nav_select";} ?>
	  "><?php echo $multilingual_head_announcement; ?></a>
    </div>
    <div class="logininfo2"><div class="float_left">
	<?php echo $multilingual_head_hello; ?> <?php echo "{$_SESSION['MM_Displayname']}"; ?>, </div> 
    
	<?php if($_SESSION['MM_rank'] > "1") { ?>
    &nbsp;&nbsp;
	<a href="default_user_edit.php?UID=<?php echo "{$_SESSION['MM_uid']}"; ?>
"><?php echo $multilingual_head_edituserinfo; ?></a><?php }  ?>

&nbsp;&nbsp;<?php if ($_SESSION['MM_rank'] > "4") {  ?><a href="setting.php?type=setting"><?php echo $multilingual_head_backend; ?></a>&nbsp;&nbsp;<?php }  ?>

<?php echo $multilingual_head_help; ?>
&nbsp;&nbsp;<a href="<?php echo $logoutAction ?>"><?php echo $multilingual_head_logout; ?></a>
</div>
</div>

 <?php if ($totalRows_Recordset_anc > 0) { // Show if recordset not empty ?> 
  <div class="ui-widget anc_div"  >
    <div class="ui-state-highlight fontsize-s" style=" padding: 5px; "> 
      <span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
      <strong><?php echo $multilingual_head_announcement; ?>: </strong> <a href="announcement_view.php?recordID=<?php echo $row_Recordset_anc['AID']; ?>"><?php echo $row_Recordset_anc['tk_anc_title']; ?></a> <span class="gray"><?php echo $row_Recordset_anc['tk_anc_lastupdate']; ?></span>&nbsp;&nbsp;&nbsp;&nbsp;  ( <a href="default_announcement.php"><?php echo $multilingual_head_more; ?>></a> )</div>
  </div>
  </div>
    <?php } // Show if recordset not empty ?>
  
  
  
  
  
  <?php
mysql_free_result($Recordset_anc);
?>

