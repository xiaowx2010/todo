<%@ Page Language="c#" Inherits="MIS.WebApplication.Controls.CheckLoginPage" %>
<%@ Import Namespace="System.Data" %>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
<HTML>
	<HEAD>
		<X:HtmlHead ID="MIS" runat="server"></X:HtmlHead>
		<meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5">
	</HEAD>
	<script runat="server" language="C#">
    private void Page_Load(object sender, System.EventArgs e)
    {
       if(GetSESSION("uname")!="")
       {
           userinfo.InnerHtml = "欢迎，<span style=\"color:red\">" + GetUserName() + "</span>";
            userinfo.InnerHtml+=" | <a href=\"javascript:__doOpenView('pop_editpassword.aspx',390,260,10,50)\">修改密码</a>";
       }
          
    }
    private void Click_Exit(object sender,System.EventArgs e)
    {
        ClearSESSION();
        Session.Clear();
        Response.Write("<s" + "cript>window.parent.location='login.aspx'" + "</s" + "cript>");
    }
        private void Go_Desktop(object sender, System.EventArgs e)
        {
            Response.Write("<s" + "cript>window.parent.document.getElementById('main').src='Desktop.aspx'" + "</s" + "cript>");
        }
    private string GetUserName()
    {
        SqlDB h_db = new SqlDB();
        DataTable h_dt = h_db.GetDataTable(h_db.ConnStr, "select realname from gmis_user where usercode=" + GetSESSION("uid"));

        return (h_dt.Rows.Count > 0) ? h_dt.Rows[0]["realname"].ToString() : GetSESSION("uname");
    }
</script>
<body>
<form id="form1" runat="server">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr valign=top>
    <td width="687"><img src="images/top.jpg" width="687" height="44"></td>
    <td align="right" background="images/topbg.gif" class="top_font1225_000000" style="padding-right:10px; ">
    <table width=100%><tr><td><div id="newsroll"></div></td><td style="width:260px;" nowrap align=right> <asp:LinkButton ID="go_desktop" Text="个人桌面" runat="server" OnClick="Go_Desktop"></asp:LinkButton> |<span id="userinfo" runat="server"></span> | <asp:LinkButton ID="btn_exit" Text="退 出" runat="server" OnClick="Click_Exit"></asp:LinkButton></td></tr></table></td>
  </tr>
</table>
<script language=javascript>
<!--
CreateControl("newsroll","NewsRoll","images/NewsRoll.swf?mode=3&dataroot=news.aspx&color=000000&hover=EC0202&win=_blank&line=1&head=&width=160&height=40&bg=topbg.gif", 160, 44, "");

//-->
</script>
<SCRIPT language=VBScript>
Function VBGetSwfVer(i)
	on error resume next
	Dim swControl, swVersion
	swVersion = 0

	set swControl = CreateObject("ShockwaveFlash.ShockwaveFlash." + CStr(i))
	if (IsObject(swControl)) then
    		swVersion = swControl.GetVariable("$version")
	end if
    	VBGetSwfVer = swVersion
End Function
</SCRIPT>
</form>
</body>
</html>

