<%@ Page Language="c#"  Inherits="MIS.WebApplication.Controls.CheckLoginPage"%>
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
	SetToolBar();//设置工具条,同时获取固定URL参数    
	dtable = "gmis_Mo_10";
    filter = " Mocode=" + id;
    flds = new string[] { "fld_10_1" , "fld_10_2","creatorcode"};
    types = "001";
    if (!IsPostBack) {//先绑定列表
        creatorcode.Value = GetUID();//创建人ID
		if(id != "0"){//绑定所有值
		    MIS.BindData(dtable,filter,flds);
		}
        
	}   
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
<input id="creatorcode" runat="server" type="hidden" />
<table width="100%"  border="0" cellpadding="3" cellspacing="1" class="table_graybgcolor" >
	<tr>
	    <td align="right"  class="td_viewcontent_title">公告主题：</td>
	    <td colspan="3" class="td_viewcontent_content"><input id="fld_10_1" style="width:95%;" maxlength="200" class="boxbline" type="text" runat="server" /></td>
    </tr>
    <tr>
	    <td align="right" class="td_viewcontent_title" valign="top">公告内容：</td>
    
	    <td colspan="3" class="td_viewcontent_content"><X:Editor id="fld_10_2" height="300" width="100%" BasePath="Common/Editor/" runat="server" /></td>
    </tr>
</table>
<!--动态生成结束-->
</form>
</body>
</html>