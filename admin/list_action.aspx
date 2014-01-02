<%@ Page Language="c#"  Inherits="MIS.WebApplication.Controls.CheckLoginPage"%>
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
            list.Rows = GetListRows();
            list.SqlStr = GetListSQL(Convert.ToInt32(navindex) * list.Rows, list.Rows);
        }
        private string GetListSQL(int fromcount, int pagerows)
        {
            return "declare @allcount int;select @allcount=count(1) from gmis_action;select @allcount as allcount,actioncode,'" + StringUtility.StringToBase64("view") + "'," + mid + ",actioncode," + navindex + ",actionname,case when Actionlink='' then '&nbsp;' else Actionlink end,'&nbsp;' as btnstr from gmis_action where actioncode not in (select top " + fromcount + " actioncode from gmis_action order by actioncode) and actioncode in (select top " + (fromcount + pagerows) + " actioncode from gmis_action order by actioncode) order by actioncode";
        }
		public override void BeforeOutput(DataTable dt, int rowi)
		{
			DataRow dr = dt.Rows[rowi];   
			if (mua.IndexOf(";3;") != -1)
			{
                dr["btnstr"] += "<a href=\"getpage.aspx?aid=" + StringUtility.StringToBase64("edit") + "&mid=" + mid + "&id=" + dr["actioncode"].ToString() + "&navindex=" + navindex + "\"><img src=\"images/icons/tb02.gif\" title=\"编辑\" align=\"absmiddle\" border=\"0\"></a>";
			}

			if (mua.IndexOf(";4;") != -1)
			{
				dr["btnstr"] += "<a href=\"javascript:if(confirm('确认删除吗！')){document.location='getpage.aspx?aid=" + StringUtility.StringToBase64("delete") + "&mid=" + mid + "&id=" + dr["actioncode"].ToString() + "&navindex=" + navindex + "';}\"><img src=\"images/icons/tb03.gif\" title=\"删除\" align=\"absmiddle\" border=\"0\"></a>";
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
<X:ListTable ID="list" Rows="20" IsProPage="true" runat="server">
<X:Template id="listtemphead" type="head" runat="server">
<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="righttableline">
    <tr class="tr_listtitle">
        <td width="25" align="center">ID</td>
		<td width="60%" align="left">操作名</td>
		<td width="32%" align="left">外部链接</td>
		<td width="8%"align="center">操作</td>
    </tr>
</X:Template>
<X:Template id="listtemp1" runat="server">
	<tr class="tr_listcontent">
	    <td align="center">*</td>
		<td align="left"><a href="view_action.aspx?aid=*&mid=*&id=*&navindex=*" title="查看明细">*</a></td>
		<td align="left">*</td>
		<td align="center">*</td>
	</tr>
</X:Template>
<X:Template id="listtemp2" runat="server">
	<tr class="tr_listcontent">
	    <td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr> 
 </X:Template>
<X:NavStyle5 ID="NavStyle" PageUrl="getpage.aspx" runat="server"></X:NavStyle5>
</X:ListTable>        
</form>
</body>
</html>
