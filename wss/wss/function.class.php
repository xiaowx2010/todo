<?php
//get item
mysql_select_db($database_tankdb,$tankdb);
function get_item( $item ) {
global $tankdb;
$sql_item = "SELECT tk_item_value FROM tk_item WHERE tk_item_key = '$item'";

$Recordset_item = mysql_query($sql_item, $tankdb) or die(mysql_error());
$row_Recordset_item = mysql_fetch_assoc($Recordset_item);
return $row_Recordset_item['tk_item_value'];
}

$version = "1.3.0";
?>