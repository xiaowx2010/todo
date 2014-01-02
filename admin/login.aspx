  <%@ Page Language="c#"  Inherits="MIS.WebApplication.Controls.Page"%>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
 <%-- <%@ Page Language="c#"  Inherits="MIS.WebApplication.Controls.Page"%>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>--%>
<HTML>
	<HEAD>
		<X:HtmlHead id="HtmlHead1"  runat="server"></X:HtmlHead>
		<meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5">
	</HEAD>
	<!--#include file="func.aspx"-->
	<script runat=server language=C#>
		private void Page_Load(object sender, System.EventArgs e)
		{
          		
		}

        private bool IsSupperMode()
        {
            string ipstr = "A" + Request.ServerVariables["LOCAL_ADDR"].ToUpper();
            Response.Write(ipstr);
            return (ipstr.IndexOf("A::1") != -1);
        }      

        
		private void LogIn_Click(object sender, System.Web.UI.ImageClickEventArgs e){
			string info_str="";
			if(Request.Cookies["CheckCode"] == null)
			{
				info_str += "您的浏览器设置已被禁用 Cookies，您必须设置浏览器允许使用 Cookies 选项后才能使用本系统。";				
				
			} 
			if(System.String.Compare(Request.Cookies["CheckCode"].Value, checkcode.Value, true) != 0)
			{
				info_str += "验证码错误，请输入正确的验证码。";	
			}

			if(uid.Value.Trim().Length == 0 || pwd.Value.Trim().Length == 0){
				info_str += "用户名或密码不能为空！";
			}
			if(info_str.Length==0)
			{
				string LoginInfo = CheckLogin(uid.Value.Trim().Replace(" ",""),pwd.Value.Trim().Replace(" ",""));
                if(LoginInfo.Length > 1 && (LoginInfo[0] == '0' || LoginInfo[0] == '1' || LoginInfo[0] == '2'))
				{
                    if (LoginInfo[0] == '2' && !IsSupperMode())
                    {
                        return;
                    }
                    ClearSESSION();
                    SetSESSION("uname", uid.Value);
                    SetSESSION("SM", LoginInfo[0].ToString());
                    SetSESSION("uid", LoginInfo.Substring(1));
                    SetSESSION("screenwidth", screenwidth.Value);
                    SetSESSION("screenheight", screenheight.Value);
                    
                    
                    
                    //用户登录                    
                    mid = GetFirstModule();
                    if (mid == "")
                    {
                        info_str = "你的帐户还未分配权限，请联系管理员！";
                    }
                    else
                    {
                        SetSESSION("mright", GetModuleRight());
                        SetSESSION("tright", GetTypeRight());
                        SetSESSION("aright", GetAreaRight());
                        SetSESSION("MainUrl", "Desktop.aspx");
                        //SetSESSION("MainUrl","getpage.aspx?mid=" + mid + "&id=" + id + "&navindex=" + navindex);                                      
                        Response.Redirect("default.aspx?closed=1");
                    }
					
				}else
					info_str=LoginInfo;
			}
			alert.InnerText=info_str;
		}
	</script>
	<script language=javascript>
	<!--
			function GetScreen()
			{
			    document.all.screenwidth.value = window.screen.width;
				document.all.screenheight.value =  window.screen.height;	
			}
			function KeySave(){			    
				if(event.keyCode == 13)
				{		
				    __doPostBack('btn_login','');
				    return;
				}
			}
	//-->
	</script>
<body MS_POSITIONING="GridLayout" onload="GetScreen();" onkeypress="KeySave();" bgcolor="#E2E2E2">
<form id="form1" runat="server">
<div id="fold" runat="server"></div>
<input id="screenwidth" type="hidden" value="1024" runat="server" />
<input id="screenheight" type="hidden" value="768" runat="server" />
<table cellSpacing="0" cellPadding="0" border="0" width="100%" height="100%">
				<tr valign="middle" align="center">
					<td  style="text-align:center">	
					<div class="welcome" align=left>
					<div style="margin-left:550px;margin-top:410px;">
						<table cellpadding="4" cellspacing="0" >
							<tr>
								<td nowrap>用户名：</td><td><input id="uid" type="text" style="width:130px;" runat="server"></td>
							</tr>
							<tr>
								<td nowrap>密码：</td><td><input id="pwd" type="password" style="width:130px;" runat="server"></td>
							</tr>
							<tr>
								<td nowrap>验证码：</td><td><input id="checkcode" type="text" style="width:70px;" runat="server">&nbsp;<img src="common/checkcode.aspx" align="absmiddle"></td>
							</tr>
							<tr>
						        <td colspan="2" align=right><input id="login" type="image" src="images/login.gif" onserverclick="LogIn_Click" runat="server"></td>
							</tr>
						</table>
						<span id="alert" style="margin-left:0px;overflow:hidden;color:red;width:200px;" runat="server"></span>
						</div>
					</div>
</td>
</tr>
</table>

</form>
</body>
</html>