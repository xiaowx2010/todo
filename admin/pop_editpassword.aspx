<%@ Page Language="c#"  Inherits="MIS.WebApplication.Controls.CheckLoginPage" Debug="true" %>
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
	username.InnerText=GetSESSION("uname");
}

private void Save_Click(object sender,System.EventArgs e)
{
	if(userpassword.Value != "" && userrepassword.Value != "")
	{
		if(userpassword.Value == userrepassword.Value)
		{
			db.SqlString = "update gmis_user set userpassword='"+StringUtility.StringToBase64(userpassword.Value)+"' where usercode="+GetUID();
			alert.InnerText = db.UpdateTable();
		}
		else
		{
			alert.InnerText = "密码与确认密码不相同！";
		}
	}
	else
	{
		alert.InnerText = "密码与确认密码不能为空！";
	}
}

private void CloseWin_Click(object sender,System.EventArgs e)
{
	Response.Write("<s"+"cript>window.close();</s"+"cript>");	
}
</script>

<body style="padding:10px;text-align:center">
<form id="form1" runat="server">
<!--选项卡-->
<!--选项卡-->
<!--操作工具条-->
<!--#include file="toolbarleft.aspx"--> 
<td width="100%" ><span id="alertmess" runat="server" style="color:Red;padding-left:5px;"></span></td>
<td style="padding-left:5px;width:55px;" nowrap>
<X:Button id="btn_savepw" type="toolbar" mode="on" text="保 存" onclick="Save_Click" runat="server"></X:Button>
</td> 
<td style="padding-left:5px;width:75px;" nowrap>
<X:Button id="btn_close" type="toolbar" mode="on" text="关闭窗口" onclick="CloseWin_Click" runat="server"></X:Button>
</td> 
<!--#include file="toolbarright.aspx"--> 
<!--操作工具条-->
<!--内容-->
<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="table_graybgcolor">
<tr>
	<td class="td_viewcontent_title" align="right" style="padding-right:5px;">用户名：</td>
	<td class="td_viewcontent_content">
		<span id="username" runat="server"></span>
	</td>
</tr>
<tr>
	<td class="td_viewcontent_title" align="right" style="padding-right:5px;">密码：</td>
	<td class="td_viewcontent_content">
		<input id="userpassword" type="password" style="width:200px" maxlength="200" runat="server">
	</td>
</tr>
<tr> 
	<td class="td_viewcontent_title" align="right" style="padding-right:5px;">确认密码：</td>
	<td class="td_viewcontent_content">
		<input id="userrepassword" type="password" style="width:200px" maxlength="200" runat="server">
	</td>
</tr>
</table>
<div id="alert" align="center" runat="server"></div>
<!--内容-->
</form>
</body>
</html>
