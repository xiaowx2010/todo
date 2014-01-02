<%@ Page Language="c#"  Inherits="MIS.WebApplication.Controls.CheckLoginPage" Debug=true%>
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


            list.Rows = GetListRows();
            list.SqlStr = GetListSQL(Convert.ToInt32(navindex) * list.Rows, list.Rows);
        }
        private string GetListSQL(int fromcount,int pagerows)
        {
            return "declare @allcount int;select @allcount=count(1) from gmis_usergroup;select @allcount as allcount,'" + StringUtility.StringToBase64("view") + "'," + mid + ",usergroupcode," + navindex + ",usergroupname,usergroupstate,'' as btnstr from gmis_usergroup where usergroupcode not in (select top " + fromcount + " usergroupcode from gmis_usergroup order by usergroupname) and usergroupcode in (select top " + (fromcount+pagerows) + " usergroupcode from gmis_usergroup order by usergroupname) order by usergroupname";
        }
            
        public override void BeforeOutput(DataTable dt, int rowi)
        {
            //处理当前页数据	
            DataRow dr = dt.Rows[rowi];
            if (mua.IndexOf(";3;") != -1)
            {
                dr["btnstr"] += "<a href=\"getpage.aspx?aid=" + StringUtility.StringToBase64("edit") + "&mid=" + mid + "&id=" + dr["usergroupcode"].ToString() + "&navindex=" + navindex + "\"><img src=\"images/icons/tb02.gif\" title=\"编辑\" align=\"absmiddle\" border=\"0\"></a>";
            }

            if (mua.IndexOf(";4;") != -1)
            {
                dr["btnstr"] += "<a href=\"javascript:if(confirm('确认删除吗！')){document.location='getpage.aspx?aid=" + StringUtility.StringToBase64("delete") + "&mid=" + mid + "&id=" + dr["usergroupcode"].ToString() + "&navindex=" + navindex + "';}\"><img src=\"images/icons/tb03.gif\" title=\"删除\" align=\"absmiddle\" border=\"0\"></a>";
             
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
<X:ListTable ID="list" Rows="20" IsProPage=true runat="server">
<X:Template id="listtemphead" type="head" runat="server">
<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="righttableline">
	<tr class="tr_listtitle">
		<td width="78%" class="tdbgground">用户角色</td>
		<td width="14%" class="tdbgground">状态</td>
		<td width="8%" class="tdbgground">操作</td>
    </tr>
</X:Template>
<X:Template id="listtemp1" runat="server">
	<tr class="tr_listcontent">
	    <td class="tdpadd-LR-3"><a title="查看明细" href="view_usergroup.aspx?aid=*&mid=*&id=*&navindex=*">*</a>&nbsp;</td>
	    <td class="tdpadd-center-3">*&nbsp;</td>
	    <td class="tdpadd-icon">*&nbsp;</td>
    </tr>
</X:Template>
<X:Template id="listtemp2" runat="server">
	<tr class="tr_listcontent">
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
