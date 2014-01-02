<%@ Page Language="c#" Inherits="MIS.WebApplication.Controls.CheckLoginPage"%>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
<HTML>
<HEAD>	
	<X:HtmlHead ID="MIS" runat="server"></X:HtmlHead>
	<meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5">
</HEAD>
<!--#include file="func.aspx"-->
<script language="C#" runat="server">
private void Page_Load(object sender, System.EventArgs e)
{    

	if(!IsSystemDeveloper())
	{
		Response.Redirect("getpage.aspx");
	}
    SetToolBar();//设置工具条,同时获取固定URL参数    
    if (!IsPostBack)
    {        
       
    }
    
    view.SqlStr = "select modulename,modulebrief,modulelevel,(select t1.modulename from gmis_module t1 where t1.modulecode=t2.moduleuppercode) as moduleuppername,useactions,listhelp,edithelp,viewhelp from gmis_module t2 where modulecode="+id+" order by moduleindex asc ";
   
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
      <td align="right" class="td_viewcontent_title">模块名：</td>
      <td class="td_viewcontent_content">*&nbsp;</td>
      <td align="right"  class="td_viewcontent_title">模块标识：</td>
      <td class="td_viewcontent_content">*&nbsp;</td>
    </tr> 
    <tr>
      <td align="right"  class="td_viewcontent_title">模块层级：</td>
      <td class="td_viewcontent_content">*&nbsp;</td>
      <td align="right"  class="td_viewcontent_title">直属上级：</td>
      <td class="td_viewcontent_content">*&nbsp;</td>
    </tr> 
        <tr> 
        <td class="td_viewcontent_title" align="right">相关操作：</td>
        <td class="td_viewcontent_content" colspan="3">
        *&nbsp;
        </td>
    </tr>
    <tr>
      <td align="right"  class="td_viewcontent_title">列表页帮助：</td>
      <td class="td_viewcontent_content" colspan="3">*&nbsp;</td>      
    </tr>
    <tr>
      <td align="right"  class="td_viewcontent_title">编辑页帮助：</td>
      <td class="td_viewcontent_content" colspan="3">*&nbsp;</td>      
    </tr>
    <tr>
      <td align="right"  class="td_viewcontent_title">查看页帮助：</td>
      <td class="td_viewcontent_content" colspan="3">*&nbsp;</td>      
    </tr> 
  </table>
  </X:Template>
  </X:Content>
<!--内容-->
</form>
</body>
</html>