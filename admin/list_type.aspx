<%@ Page Language="c#" Inherits="MIS.WebApplication.Controls.CheckLoginPage" %>

<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
<html>
<head>
    <X:HtmlHead ID="MIS" runat="server">
    </X:HtmlHead>
    <meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5">
</head>
<!--#include file="func.aspx"-->

<script runat="server" language="C#">
		private void Page_Load(object sender, System.EventArgs e)
		{			
			SetToolBar();//设置工具条,同时获取固定URL参数			
			list.Rows = GetListRows();
            list.SqlStr = GetListSQL(Convert.ToInt32(navindex) * list.Rows, list.Rows);
        }
        private string GetListSQL(int fromcount, int pagerows)
        {
            return "declare @allcount int;select @allcount=count(1) from gmis_Type;select @allcount as allcount,'" + StringUtility.StringToBase64("view") + "'," + mid + ",typecode," + navindex + ",typename,typebrief,typekind,typestate,'&nbsp;' as btnstr from gmis_type where TypeCode not in (select top " + fromcount + " TypeCode from gmis_Type order by typeindex) and TypeCode in (select top " + (fromcount + pagerows) + " TypeCode from gmis_Type order by typeindex) order by typeindex";
        }
        public override void BeforeOutput(DataTable dt, int rowi)
        {	//处理当前页数据		

            DataRow dr = dt.Rows[rowi];
            if (mua.IndexOf(";3;") != -1)
            {
                dr["btnstr"] += "<a href=\"getpage.aspx?aid=" + StringUtility.StringToBase64("edit") + "&mid=" + mid + "&id=" + dr["typecode"].ToString() + "&navindex=" + navindex + "\"><img src=\"images/icons/tb02.gif\" title=\"编辑\" align=\"absmiddle\" border=\"0\"></a>";
            }

            if (mua.IndexOf(";4;") != -1)
            {
                dr["btnstr"] += "<a href=\"javascript:if(confirm('确认删除吗！')){document.location='getpage.aspx?aid=" + StringUtility.StringToBase64("delete") + "&mid=" + mid + "&id=" + dr["typecode"].ToString() + "&navindex=" + navindex + "';}\"><img src=\"images/icons/tb03.gif\" title=\"删除\" align=\"absmiddle\" border=\"0\"></a>";
            }

        }
</script>

<body style="padding: 10px; text-align: center">
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
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="righttableline">
                    <tr class="tr_listtitle">
                        <td width="45%" align="left">
                            数据层名</td>
                        <td width="24%" align="left">
                            简称</td>
                        <td width="15%" align="left">
                            类别</td>
                        <td width="8%" align="left">
                            状态</td>
                        <td width="7%" align="center">
                            操作</td>
                    </tr>
            </X:Template>
            <X:Template id="listtemp1" runat="server">
                <tr class="tr_listcontent">
                    <td class="tdpadd-LR-3">
                        <a title="查看明细" href="view_type.aspx?aid=*&mid=*&id=*&navindex=*">*</a></td>
                    <td class="tdpadd-LR-3">
                        *</td>
                    <td class="tdpadd-center-3">
                        *</td>
                    <td class="tdpadd-center-3">
                        *</td>
                    <td class="tdpadd-icon">
                        *</td>
                </tr>
            </X:Template>
            <X:Template id="listtemp2" runat="server">
                <tr class="tr_listcontent">
                    <td>
                        &nbsp;</td>
                    <td>
                        &nbsp;</td>
                    <td>
                        &nbsp;</td>
                    <td>
                        &nbsp;</td>
                    <td>
                        &nbsp;</td>
                </tr>
            </X:Template>
            <X:NavStyle5 ID="NavStyle" PageUrl="getpage.aspx" runat="server">
            </X:NavStyle5>
        </X:ListTable>
    </form>
</body>
</html>
