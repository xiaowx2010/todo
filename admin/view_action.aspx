<%@ Page Language="c#" Inherits="MIS.WebApplication.Controls.CheckLoginPage"%>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
<HTML>
<HEAD>	
	<X:HtmlHead ID="MIS" runat="server"></X:HtmlHead>
	<meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5">
</HEAD>
<!--#include file="func.aspx"-->
	<script runat=server language=C#>
		private void Page_Load(object sender, System.EventArgs e)
		{
			if(!IsSystemDeveloper())
			{
				Response.Redirect("getpage.aspx");
			}
			SetToolBar();//设置工具条,同时获取固定URL参数
			view.SqlStr = "select ActionName,Actionlink from gmis_Action where ActionCode="+id;
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
<X:Content ID="view" runat="server">
<X:Template id="tempview" runat="server">
<table width="100%"  border="0" cellpadding="3" cellspacing="1" class="table_graybgcolor">
    <tr>
      <td align="right"  class="td_viewcontent_title">操作名：</td>
      <td class="td_viewcontent_content" style="width:35%">*&nbsp;</td>
      <td align="right"  class="td_viewcontent_title">外部链接：</td>
      <td class="td_viewcontent_content" style="width:35%">*&nbsp;</td>
    </tr> 
</table>
</X:Template>                    
</X:Content>          
</form>
</body>
</html>
