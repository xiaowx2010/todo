<?php
$fd = null; //判断是否是文件夹
if (isset($_GET['folder'])) {
  $fd = $_GET['folder'];
}

$projectpage = "-1"; //判断是否是项目列表
if (isset($_GET['projectpage'])) {
  $projectpage = $_GET['projectpage'];
}


$projectlist = "-1";
if (isset($_GET['pl'])) {
  $projectlist = $_GET['pl'];
}

$project_id= "-1";
if (isset($_GET['projectID'])) {
  $project_id = $_GET['projectID'];
}

$project_name= "-1";
if (isset($_GET['projectNAME'])) {
  $project_name = $_GET['projectNAME'];
}

$folder_name= "-1";
if (isset($_GET['folderNAME'])) {
  $folder_name = $_GET['folderNAME'];
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

$pfiles = "-1"; //判断是否是项目文档
if (isset($_GET['pfile'])) {
  $pfiles = $_GET['pfile'];
}

$searchf = "-1"; //判断是否点击了搜索
if (isset($_GET['search'])) {
  $searchf = $_GET['search'];
}

if ($project_id <> "-1") {
  $inproject = " inner join tk_project on tk_document.tk_doc_class1=tk_project.id ";
} else { $inproject = " ";}

$filenames = "";
if (isset($_GET['filetitle'])) {
  $filenames = $_GET['filetitle'];
}

if($pagetabs=="mcfile"){
$multilingual_breadcrumb_filelist = $multilingual_project_file_myfile;
}else if ($pagetabs=="mefile") {
$multilingual_breadcrumb_filelist = $multilingual_project_file_myeditfile;
}else {
$multilingual_breadcrumb_filelist = $multilingual_project_file_allfile;
}

mysql_select_db($database_tankdb, $tankdb);
$query_DetailRS1 = sprintf("SELECT *, 
tk_user1.tk_display_name as tk_display_name1, 
tk_user2.tk_display_name as tk_display_name2 FROM tk_document 
inner join tk_user as tk_user1 on tk_document.tk_doc_create=tk_user1.uid  
inner join tk_user as tk_user2 on tk_document.tk_doc_edit=tk_user2.uid 
$inproject 
WHERE tk_document.docid = %s", GetSQLValueString($colname_DetailRS1, "int"));
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

mysql_select_db($database_tankdb, $tankdb);
$query_projectname = sprintf("SELECT * FROM tk_project 
inner join tk_user on tk_project.project_to_user=tk_user.uid 
WHERE tk_project.id = %s", GetSQLValueString($project_id, "int"));
$projectname = mysql_query($query_projectname, $tankdb) or die(mysql_error());
$row_projectname = mysql_fetch_assoc($projectname);

$fileid = $row_DetailRS1['tk_doc_class2'];
mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_pfilename = sprintf("SELECT * FROM tk_document WHERE docid = %s", GetSQLValueString($fileid, "int"));
$Recordset_pfilename = mysql_query($query_Recordset_pfilename, $tankdb) or die(mysql_error());
$row_Recordset_pfilename = mysql_fetch_assoc($Recordset_pfilename);
$totalRows_Recordset_pfilename = mysql_num_rows($Recordset_pfilename);

$maxRows_Recordset_file = 10;
$pageNum_Recordset_file = 0;
if (isset($_GET['pageNum_Recordset_file'])) {
  $pageNum_Recordset_file = $_GET['pageNum_Recordset_file'];
}
$startRow_Recordset_file = $pageNum_Recordset_file * $maxRows_Recordset_file;


if ($searchf == "1"){
$inprolist = "tk_doc_title LIKE %s AND tk_doc_backup1 <> 1";
$inprolists = "%" . $filenames . "%";
}else if ($colname_DetailRS1=="-1" && $project_id <> "-1" && $pagetabs == "allfile") {
  $inprolist = " tk_doc_class1 = %s  AND  tk_doc_class2 = 0 ";
  $inprolists = $project_id;
  
} else if ($pagetabs == "mcfile"){
$inprolist = " tk_doc_create = %s AND tk_doc_backup1 = 0 ";
$inprolists = $_SESSION['MM_uid'];
} 
 else if ($pagetabs == "mefile"){
$inprolist = " tk_log.tk_log_user = %s AND tk_log.tk_log_class = 2 AND tk_doc_backup1 = 0 ";
$inprolists = $_SESSION['MM_uid'];
} else { 
  $inprolist = " tk_doc_class2 = %s  ";
  $inprolists = $colname_DetailRS1;
} 
if($pagetabs == "mefile" ){
$where1 = "inner join tk_log on tk_document.docid=tk_log.tk_log_type";
$where2 = "GROUP BY tk_document.docid";
}else{
$where1 = "";
$where2 = "";
}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_file = sprintf("SELECT * FROM tk_document 
inner join tk_user on tk_document.tk_doc_edit =tk_user.uid 
$where1 
WHERE $inprolist
								
								$where2 ORDER BY tk_doc_backup1 DESC, tk_doc_edittime DESC", 
								GetSQLValueString($inprolists, "text")
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
        stristr($param, "totalRows_Recordset_file") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset_file = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset_file = sprintf("&totalRows_Recordset_file=%d%s", $totalRows_Recordset_file, $queryString_Recordset_file);

$docid = $colname_DetailRS1;
$maxRows_Recordset_actlog = 10;
$pageNum_Recordset_actlog = 0;
if (isset($_GET['pageNum_Recordset_actlog'])) {
  $pageNum_Recordset_actlog = $_GET['pageNum_Recordset_actlog'];
}
$startRow_Recordset_actlog = $pageNum_Recordset_actlog * $maxRows_Recordset_actlog;

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset_actlog = sprintf("SELECT * FROM tk_log 
inner join tk_user on tk_log.tk_log_user =tk_user.uid 
								 WHERE tk_log_type = %s AND tk_log_class = 2 
								
								ORDER BY tk_log_time DESC", 
								GetSQLValueString($docid, "text")
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


if($pfiles==1){
$filepro=$project_id;
}else{
$filepro = "-1";
if (isset($row_DetailRS1['tk_doc_class1'])) {
  $filepro = $row_DetailRS1['tk_doc_class1'];
}
}

$filepid = "-1";
if (isset($row_DetailRS1['docid'])) {
  $filepid  = $row_DetailRS1['docid'];
}

if($filepid == "-1" && $pfiles=="1"){
$filepid = "0";
}

$host_url="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER["QUERY_STRING"];
$host_url=strtr($host_url,"&","!");

if ($projectpage == 1){ //显示项目列表
$maxRows_Recordset1 = get_item( 'maxrows_project' );
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;


$colinputtitle_Recordset1 = "";
if (isset($_GET['inputtitle'])) {
  $colinputtitle_Recordset1 = $_GET['inputtitle'];
}

mysql_select_db($database_tankdb, $tankdb);
$query_Recordset1 = sprintf("SELECT * FROM tk_project 
							
							inner join tk_user on tk_project.project_to_user=tk_user.uid
							WHERE project_name LIKE %s ORDER BY tk_project.project_lastupdate DESC",  
GetSQLValueString("%" . $colinputtitle_Recordset1 . "%", "text"));
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
}
?>

<table align="center" class="fontsize-s glink" width="100%">
<tr>
<td><!--面包屑 -->
<?php if ( $colname_DetailRS1 <> "-1" || $project_id <> "-1" || $projectpage == 1) { // 如果是一级页面不显示任何面包屑 ?>

<?php if ( $searchf == "1") { //搜索结果面包屑 ?>
 <span class="float_left"><a href="file.php?pagetab=<?php echo $pagetabs;?>"><?php echo $multilingual_breadcrumb_filelist; ?></a></span>
 <span class="ui-icon month_next float_left"></span>
 <span class="float_left"><?php echo $multilingual_project_file_searchr; ?>:<?php echo $filenames; ?></span>
	 
<?php } else if ( $pfiles== "1") { // 项目文档面包屑 ?>
 <span class="float_left"><a href="file.php?pagetab=<?php echo $pagetabs;?>"><?php echo $multilingual_breadcrumb_filelist; ?></a></span>
	  	  
	  <span class="ui-icon month_next float_left"></span>
	  <span class="float_left"><a href="file.php?projectpage=1&pagetab=allfile"><?php echo $multilingual_project_file; ?></a></span>
	  
	   <span class="ui-icon month_next float_left"></span>
	  <span class="float_left">
	  <?php if($row_DetailRS1['tk_doc_title'] <> null) {?>
	  <a href="file.php?projectID=<?php echo $row_projectname['id']; ?>&pfile=1&pagetab=<?php echo $pagetabs;?>"><?php echo $row_projectname['project_name']; ?></a>
	  <?php } else {?>
	  <?php echo $row_projectname['project_name']; ?>
	  <?php } ?>
	  </span>
	  
	  <?php if($row_DetailRS1['tk_doc_class2'] > "0") {?>
	  <span class="ui-icon month_next float_left"></span>
	  <span class="float_left">... <a href="file.php?recordID=<?php echo $row_DetailRS1['tk_doc_class2']; ?>&folder=1&projectID=<?php echo $project_id; ?>&pfile=1&pagetab=allfile"><?php echo $row_Recordset_pfilename['tk_doc_title']; ?></a></span>
	  <?php } ?>
	  
	  <?php if($row_DetailRS1['tk_doc_title'] <> null) {?>
	  <span class="ui-icon month_next float_left"></span>
	  <span class="float_left"><?php echo $row_DetailRS1['tk_doc_title']; ?></span>
	  <?php } ?>
<?php } else { ?>


	  <span class="float_left"><?php 
	  if($project_id == "-1"  ){
	  echo "<a href='file.php?&pagetab=".$pagetabs."'>".$multilingual_breadcrumb_filelist."</a>";
	  }else{
	  echo $multilingual_breadcrumb_projectlist;
	  } ?></span>
  
   <?php if ($project_id <> "-1") {  //返回项目详情的面包屑 ?>
	  <span class="ui-icon month_next float_left"></span>
	  <span class="float_left"><a href="project_view.php?recordID=<?php echo $row_DetailRS1['tk_doc_class1']; ?>"><?php echo $row_DetailRS1['project_name']; ?></a></span>
	  <?php } ?>
	  
	  <?php if($row_DetailRS1['tk_doc_class2'] > "0") {?>
	  <span class="ui-icon month_next float_left"></span>
	  <span class="float_left">... <a href="file.php?recordID=<?php echo $row_DetailRS1['tk_doc_class2']; ?>&folder=1&projectID=<?php echo $project_id; ?>&pagetab=allfile"><?php echo $row_Recordset_pfilename['tk_doc_title']; ?></a></span>
	  <?php } ?>
	  
	  
	  <span class='ui-icon month_next float_left'></span>
	  <span class="float_left"><?php echo $row_DetailRS1['tk_doc_title']; ?></span>
	  <?php } ?>  
	  <?php if ($projectpage == 1) {echo $multilingual_project_file; }?>
	  
	 <?php } //如果是一级页面不显示任何面包屑 ?>	
</td><!--面包屑 -->
  <td align="right">
 <div class="float_left" > <form id="form1" name="form1" method="get" action="file.php">
  <?php echo $multilingual_project_file_search; ?>:<input type="text" name="filetitle" id="filetitle"><input name="search" type="text" id="search" value="1" style="display:none;"><input name="pagetab" type="text" id="pagetab" value="allfile" style="display:none;">
              <input type="submit" value="<?php echo $multilingual_global_action_ok; ?>" class="button" />
            </form>
  </div>
 <?php if($_SESSION['MM_rank'] > "1") { ?> 
 <?php if($colname_DetailRS1 == "-1" || $fd == "1") { ?> 
  <?php if($pagetabs=="allfile"){ ?>
 <div class="float_right" >

	  <a class="icon_folder" style="margin-top:0px;"></a><a href="file_add.php?projectid=<?php echo $filepro; ?>&pid=<?php echo $filepid; ?>&folder=1<?php if ( $pfiles== "1") {
	  echo "&pfile=1";
	  }?>&pagetab=<?php echo $pagetabs;?>" class="file_create_a"><?php echo $multilingual_project_file_addfolder; ?></a>

	  <a class="icon_file" style="margin-top:0px; "></a>
	  <a href="file_add.php?projectid=<?php echo $filepro; ?>&pid=<?php echo $filepid; ?><?php if ( $pfiles== "1") {
	  echo "&pfile=1";
	  }?>&pagetab=<?php echo $pagetabs;?>" class="file_create_a"><?php echo $multilingual_project_file_addfile; ?></a></div>
<?php } else {?>  
 <div class="float_right" >
	  <a class="icon_file" style="margin-top:0px; "></a>
	  <a href="file_add.php?projectid=<?php echo $filepro; ?>&pid=<?php echo $filepid; ?><?php if ( $pfiles== "1") {
	  echo "&pfile=1";
	  }?>&pagetab=<?php echo $pagetabs;?>" class="file_create_a"><?php echo $multilingual_project_file_addfile; ?></a></div>
<?php } ?>
<?php } ?>
<?php } ?>	  

<?php if($colname_DetailRS1 <> "-1" && $fd <> "1" && $projectpage <> 1){ //文档详情显示操作按钮 ?>


	   <a href="word.php?fileid=<?php echo $colname_DetailRS1; ?>" class="icon_word"><?php echo $multilingual_project_file_word; ?></a> 

	  &nbsp;
	  <?php if($_SESSION['MM_rank'] > "1") { ?>
	  	 
	 <input name="" type="button" class="button" onclick="javascript:self.location='file_edit.php?editID=<?php echo $row_DetailRS1['docid']; ?>&projectID=<?php 
	  if ( $pfiles== "1" || $colname_DetailRS1 == "-1") { 
	  echo $project_id;
	  } else {
	  echo "-1";
	  } ?>&pid=<?php echo $row_DetailRS1['tk_doc_class2']; ?>&folder=0<?php if ( $pfiles== "1") {
	  echo "&pfile=1";
	  }?>&pagetab=<?php echo $pagetabs;?>';" value="<?php echo $multilingual_global_action_edit; ?>" />
	 
	 
	  <?php } ?>

<input name="button" type="button" id="button" onclick="javascript:history.go(-1)" value="<?php echo $multilingual_global_action_back; ?>"  class="button"  />
<?php } //文档详情显示操作按钮 ?>	  
  </td>
</tr>

<?php if($colname_DetailRS1 <> "-1" && $fd <> "1" && $projectpage <> 1){ //显示文档详情 ?>

<tr>
    <td colspan="2"><span class="input_task_title margin-y" style="margin-top:0px;"><?php echo $row_DetailRS1['tk_doc_title']; ?></span></td>
</tr>
  <?php if($row_DetailRS1['tk_doc_description'] <> null) { ?>
  <tr>
    <td colspan="2" ><?php 
	 
	
	echo $row_DetailRS1['tk_doc_description']; 
	?><br />
	
	<?php if ($row_DetailRS1['tk_doc_attachment'] <> "" && $row_DetailRS1['tk_doc_attachment'] <> " ") { //显示附件下载地址，如果有 ?>
	 <span class="input_task_submit">
	  <a href="<?php echo $row_DetailRS1['tk_doc_attachment']; ?>" class="icon_download"><?php echo $multilingual_project_file_download; ?></a>
	  </span>
	  <?php } ?>
	</td>
  </tr>

  <?php } ?>
  <?php if($totalRows_Recordset_actlog > 0){ //显示操作记录，如果有 ?>
	<tr valign="baseline">
      <td colspan="2" nowrap="nowrap"><span class="input_task_title "><?php echo $multilingual_log_title; ?></span></td>
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
              <a href="<?php printf("%s?pageNum_Recordset_actlog=%d%s", $currentPage, 0, $queryString_Recordset_actlog); ?>#task"><?php echo $multilingual_global_first; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset_actlog > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset_actlog=%d%s", $currentPage, max(0, $pageNum_Recordset_actlog - 1), $queryString_Recordset_actlog); ?>#task"><?php echo $multilingual_global_previous; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset_actlog < $totalPages_Recordset_actlog) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset_actlog=%d%s", $currentPage, min($totalPages_Recordset_actlog, $pageNum_Recordset_actlog + 1), $queryString_Recordset_actlog); ?>#task"><?php echo $multilingual_global_next; ?></a>
              <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_Recordset_actlog < $totalPages_Recordset_actlog) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset_actlog=%d%s", $currentPage, $totalPages_Recordset_actlog, $queryString_Recordset_actlog); ?>#task"><?php echo $multilingual_global_last; ?></a>
              <?php } // Show if not last page ?></td>
        </tr>
      </table></td>
<td align="right">   <?php echo ($startRow_Recordset_actlog + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset_actlog + $maxRows_Recordset_actlog, $totalRows_Recordset_actlog) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset_actlog ?>)&nbsp;&nbsp;&nbsp;&nbsp;</td>
</tr>
</table> 	
	</td>
	</tr>
	<?php } ?>
  <?php } else if(($colname_DetailRS1 == "-1" || $fd == "1" ) && $projectpage <> 1 ) { //显示文档列表 ?>
  <!--file start -->
<?php if($row_DetailRS1['tk_doc_description'] <> "&nbsp;" && $row_DetailRS1['tk_doc_description'] <> "" && $projectpage <> 1){ //显示文件夹详情 ?>
  <tr valign="baseline">
      <td colspan="2" nowrap="nowrap">
	  <span class="input_task_title  margin-y" style="margin-top:0px;">
	  <?php echo $multilingual_project_file_description; ?>
	  </span>
      </td>
</tr>
<tr valign="baseline">
	  <td colspan="2" >
	  <?php echo $row_DetailRS1['tk_doc_description']; ?>
	  </td>
  </tr>
<?php } ?>  
  <tr valign="baseline">
      <td colspan="2" nowrap="nowrap">
	  <span class="input_task_title  margin-y" style="margin-top:0px;">
	  <div class="float_left"><?php echo $multilingual_project_file_list; ?></div>
	  
	  </span>
	  </td>
  </tr>
	<?php if ($colname_DetailRS1=="-1" && $project_id == "-1" && $searchf <> "1" && $pagetabs == "allfile" ){ ?>	
	<tr valign="baseline">
      <td colspan="2" nowrap="nowrap"  class="comment_list">
	 <a href="file.php?projectpage=1&pagetab=allfile" class="icon_folder"><?php echo $multilingual_project_file; ?></a>
	  </td>
	</tr>
<?php } ?>
	<?php if($totalRows_Recordset_file > "0"){?>
	

	
	<?php do { ?>
		<tr valign="baseline">
      <td colspan="2" nowrap="nowrap"  class="comment_list">
	  <div class="float_left">
	  
	  
	  <a href="file.php?recordID=<?php echo $row_Recordset_file['docid']; ?><?php 
	  if($row_Recordset_file['tk_doc_backup1']=="1"){
	  echo "&folder=".$row_Recordset_file['tk_doc_backup1'];
	  } ?>&projectID=<?php echo $project_id; ?><?php if ( $pfiles== "1") {
	  echo "&pfile=1";
	  }?>&pagetab=<?php echo $pagetabs;?>
	  " class="
	  <?php 
	  if($row_Recordset_file['tk_doc_backup1']=="1"){
	  echo "icon_folder";
	  }else{
	  echo "icon_file";
	  } ?>
	  "><?php echo $row_Recordset_file['tk_doc_title']; ?></a>
	
	
	  <?php if ($row_Recordset_file['tk_doc_attachment'] <> ""  && $row_Recordset_file['tk_doc_attachment'] <> " ") {  ?>
	 
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
	  <a href="file_edit.php?editID=<?php echo $row_Recordset_file['docid']; ?>&projectID=<?php 
	  if ( $pfiles== "1" || $colname_DetailRS1 == "-1") { 
	  echo $project_id;
	  } else {
	  echo $row_DetailRS1['tk_doc_class1'];
	  } ?>&pid=<?php echo $row_DetailRS1['docid']; ?>&folder=<?php echo $row_Recordset_file['tk_doc_backup1']; ?><?php if ( $pfiles== "1") {
	  echo "&pfile=1";
	  }?>&pagetab=<?php echo $pagetabs;?>">
	  <?php echo $multilingual_global_action_edit; ?></a> 
	  &nbsp;
	  <?php if ($_SESSION['MM_rank'] > "4" || $row_Recordset_file['tk_doc_create'] == $_SESSION['MM_uid']) {  ?>
	  <?php if ($_SESSION['MM_Username'] <> $multilingual_dd_user_readonly) {  ?>
	   <a  class="mouse_hover" 
	  onclick="javascript:if(confirm( '<?php 
	   if ($row_Recordset_file['tk_doc_backup1'] == 0){
	  echo $multilingual_global_action_delconfirm;}
	  else {
	  echo $multilingual_global_action_delconfirm5;} ?>'))self.location='file_del.php?delID=<?php echo $row_Recordset_file['docid']; ?>&projectID=<?php echo $row_DetailRS1['tk_doc_class1']; ?>&pid=<?php echo $row_DetailRS1['docid']; ?>&url=<?php echo $host_url; ?>';"
	  ><?php echo $multilingual_global_action_del; ?></a>
	  <?php } else {  
	   echo $multilingual_global_action_del; 
	    }  ?>
	  <?php }  ?>
	  <?php } ?>
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
              <a href="<?php printf("%s?pageNum_Recordset_file=%d%s", $currentPage, 0, $queryString_Recordset_file); ?>#task"><?php echo $multilingual_global_first; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset_file > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset_file=%d%s", $currentPage, max(0, $pageNum_Recordset_file - 1), $queryString_Recordset_file); ?>#task"><?php echo $multilingual_global_previous; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset_file < $totalPages_Recordset_file) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset_file=%d%s", $currentPage, min($totalPages_Recordset_file, $pageNum_Recordset_file + 1), $queryString_Recordset_file); ?>#task"><?php echo $multilingual_global_next; ?></a>
              <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_Recordset_file < $totalPages_Recordset_file) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset_file=%d%s", $currentPage, $totalPages_Recordset_file, $queryString_Recordset_file); ?>#task"><?php echo $multilingual_global_last; ?></a>
              <?php } // Show if not last page ?></td>
        </tr>
      </table></td>
<td align="right">   <?php echo ($startRow_Recordset_file + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset_file + $maxRows_Recordset_file, $totalRows_Recordset_file) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset_file ?>)&nbsp;&nbsp;&nbsp;&nbsp;

</td>
</tr>
</table>
	</td>
    </tr>
<?php } else if($colname_DetailRS1=="-1" && $project_id == "-1" && $totalRows_Recordset_file == "0"){?>
<tr>
<td colspan="2">
<div class="update_bg">
    <?php echo $multilingual_project_file_nofile; ?>
</div>
</td>
</tr>
<?php } else {?>
<tr>
<td colspan="2">
<div class="update_bg">
    <?php echo $multilingual_project_file_nofile; ?>
</div>
</td>
</tr>
<?php } ?>
	
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr> 
<!--file end -->	
<?php } ?>
<?php if($projectpage == 1){ ?>
<tr valign="baseline">
      <td colspan="2" nowrap="nowrap">
	  <span class="input_task_title  margin-y" style="margin-top:0px;">
	  <?php echo $multilingual_project_file; ?>
	  </span>
      </td>
</tr>
	<?php if($totalRows_Recordset1 > "0"){?>
	
	<?php do { ?>
		<tr valign="baseline">
      <td nowrap="nowrap"  class="comment_list"  colspan="2">
	  <div class="float_left">
	  <a href="file.php?projectID=<?php echo $row_Recordset1['id']; ?>&folder=1&pfile=1&pagetab=<?php echo $pagetabs;?>
	  " class="icon_folder"><?php echo $row_Recordset1['project_name']; ?></a>
	  </div>
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
	<tr valign="baseline">
      <td  colspan="2">
<table class="rowcon" border="0" align="center">
<tr>
<td>   <table border="0">
        <tr>
          <td><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, 0, $queryString_Recordset1); ?>#task"><?php echo $multilingual_global_first; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, max(0, $pageNum_Recordset1 - 1), $queryString_Recordset1); ?>#task"><?php echo $multilingual_global_previous; ?></a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, min($totalPages_Recordset1, $pageNum_Recordset1 + 1), $queryString_Recordset1); ?>#task"><?php echo $multilingual_global_next; ?></a>
              <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, $totalPages_Recordset1, $queryString_Recordset1); ?>#task"><?php echo $multilingual_global_last; ?></a>
              <?php } // Show if not last page ?></td>
        </tr>
      </table></td>
<td align="right">   <?php echo ($startRow_Recordset1 + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset1 + $maxRows_Recordset1, $totalRows_Recordset1) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset1 ?>)&nbsp;&nbsp;&nbsp;&nbsp;</td>
</tr>
</table>	</td>
    </tr>
<?php } else {?>
<tr>
<td>
<div class="update_bg">
    <?php echo $multilingual_project_file_nofile; ?></div></td>
</tr>
<?php } ?>
<?php } ?>
</table>
<p>&nbsp;</p>

<?php
mysql_free_result($DetailRS1);
?>