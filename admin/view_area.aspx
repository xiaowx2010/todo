<%@ Page Language="c#" Inherits="MIS.WebApplication.Controls.CheckLoginPage"%>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
<HTML>
<HEAD>	
	<X:HtmlHead ID="MIS" runat="server"></X:HtmlHead>
	<meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5">
</HEAD>
<!--#include file="func.aspx"-->
	<script runat="server" language="C#">
		private void Page_Load(object sender, System.EventArgs e)
		{
            SetToolBar();//设置工具条,同时获取固定URL参数
			view.SqlStr = "select areaname,areabrief,areastate,areadesc from gmis_area where areacode="+id;
		}

	</script>
<body style="padding:10px;text-align:center">
<form id="form1" runat="server">
<!--选项卡-->
<!--选项卡-->
<!--操作工具条-->
    <!--#include file="toolbarleft.aspx"--> 
			<!--右边固定按钮-->
			<!--#include file="toolbar.aspx"-->
			<!--右边固定按钮-->
	   <!--#include file="toolbarright.aspx"--> 
<!--操作工具条-->
<!--内容-->
<X:Content id="view" runat="server">
<X:Template id="Template1" runat="server">
<table width="100%"  border="0" cellpadding="3" cellspacing="1" class="table_graybgcolor">
    <tr> 
        <td align="right"  class="td_viewcontent_title">区域名：</td>
        <td class="td_viewcontent_content" colspan="3">*&nbsp;</td>
    </tr>
    <tr> 
        <td align="right"  class="td_viewcontent_title">简称：</td>
        <td class="td_viewcontent_content">*&nbsp;</td>
        <td align="right"  class="td_viewcontent_title">状态：</td>
        <td class="td_viewcontent_content">*&nbsp;</td>
    </tr>
    <tr>
        <td align="right"  class="td_viewcontent_title">介绍：</td>
        <td class="td_viewcontent_content" colspan="3">*&nbsp;</td>
    </tr>
</table>
</X:Template>                    
</X:Content>
</form>
</body>
</html>
