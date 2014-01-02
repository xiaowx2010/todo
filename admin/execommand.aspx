<%@ Page Language="c#" debug="true"  Inherits="MIS.WebApplication.Controls.CheckLoginPage"%>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
<HTML>
<HEAD>	
	<X:HtmlHead ID="MIS" runat="server"></X:HtmlHead>
	<meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5">
</HEAD>
<!--#include file="func.aspx"-->
<!--#include file="restructmodule.aspx"-->
<script language="C#" runat="server">
private void Page_Load(object sender, System.EventArgs e)
{
    mid = GetQueryString("mid", "0");
    aid = GetQueryString("aid", "list");
    id = GetQueryString("id", "0");
    pid=GetQueryString("pid", "0");
    navindex = GetQueryString("navindex", "0");
    if (GetSESSION("alert") != "")
    {
        alert.Style.Add("color", "red");
        alert.InnerHtml = GetSESSION("alert");
        SetSESSION("alert", "");
        btn_back.Visible = true;
        btn_backlist.Visible = false;
    }
    else
    {
        if (GetSESSION("sql") != "")
        {
            string h_sql = GetSESSION("sql");
			
            if (GetSESSION("extsql") != "")
            {
                h_sql = "Begin " + h_sql.Trim(';') +";"+ GetSESSION("extsql").Trim(';') + "; End";
                SetSESSION("extsql", "");
            }
			Response.Write(h_sql);
            string Exeinfo = "";
            Exeinfo = MIS.ExeBySql(h_sql, 1);
             //Exeinfo += h_sql;          
            SetSESSION("sql", "");
            if (Exeinfo.IndexOf("操作成功！") > -1)
            {
                switch (GetModuleName(mid))
                {
                    case "模块":
                        alert.InnerHtml=MIS.ExeBySql(UpdateTypeIndex("gmis_module", "modulecode", "modulelevel", "moduleindex", "moduleposition", "moduleuppercode"), 1);
                        string scriptstr = "<s" + "cript> window.parent.frames(1).location=\"nav.aspx?pid=1&mid=" + mid + "\";</scrip" + "t>";
                        Page.RegisterStartupScript("upnav", scriptstr);
                        break;
                    case "区域管理":
                        alert.InnerHtml = MIS.ExeBySql(UpdateTypeIndex("gmis_area", "areacode", "arealevel", "areaindex", "areaposition", "areauppercode"), 1);
                        break;
                    case "部门":
                        alert.InnerHtml = MIS.ExeBySql(UpdateTypeIndex("gmis_dept", "deptcode", "deptlevel", "deptindex", "deptposition", "deptuppercode"), 1);
                        break;
                    case "图层管理":
                        alert.InnerHtml = MIS.ExeBySql(UpdateTypeIndex("gmis_type", "typecode", "typelevel", "typeindex", "typeposition", "typeuppercode"), 1);
                        break;
                    
                    default:
                        break;
                }
               
        
                if (id == "0")//新增
                {
                    btn_newadd.Visible = true;//继续新增
                    btn_newadd.Url = "getpage.aspx?aid=" + StringUtility.StringToBase64(aid) + "&mid=" + mid + "&pid=" + pid;
                }          
               
            }
            alert.InnerHtml = Exeinfo;
        }
    }

    switch (mid)
    {
        case "3":
            btn_backlist.Url = "getpage.aspx?aid=" + StringUtility.StringToBase64("edit") + "&mid=" + mid + "&id=0&navindex=" + navindex + "&pid=" + pid;
            break;
        default:
            btn_backlist.Url = "getpage.aspx?aid=" + StringUtility.StringToBase64("list") + "&mid=" + mid + "&id=0&navindex=" + navindex + "&pid=" + pid;
            break;
    }
    
}


</script>
<body style="padding:10px;text-align:center">
<form id="form1" runat="server">
<!--选项卡-->
<!--选项卡-->
<!--操作工具条-->

<!--操作工具条-->
<!--内容-->

<table width="100%" style="height:400px" border="0" cellpadding="3" cellspacing="1" >
    <tr valign="top"> 
        <td align="center" style="padding-top:100px;">
			<table width="300" style="height:100px" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center"><div id="alert" runat="server"></div></td>
				</tr>
				<tr>
					<td align="center">
					<X:Button id="btn_back" type="toolbar" mode="on"  text="返回" Url="javascript:window.history.back()" runat="server"></X:Button>	
					</td>
				</tr>				
				<tr>
					<td align="center">
						<X:Button id="btn_backlist" type="toolbar" mode="on"  text="返回列表"  Url="javascript:window.history.back()" runat="server"></X:Button>

					</td>
				</tr>
				<tr>
					<td align="center">
						<X:Button id="btn_newadd" type="toolbar" mode="on"  text="继续新增" Visible="false"  runat="server"></X:Button>

					</td>
				</tr>
			</table>
        </td>
    </tr>
</table>

<!--内容-->
</form>
</body>
</html>