<%@ Page Language="c#"  Inherits="MIS.WebApplication.Controls.CheckLoginPage"%>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
<HTML>
<HEAD>	
	<X:HtmlHead ID="MIS" runat="server"></X:HtmlHead>
	<meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5">
</HEAD>
<!--#include file="func.aspx"-->
	<script runat="server" language="C#">
		DataTable gdt;
        private void Page_Load(object sender, System.EventArgs e)
        {
            SetToolBar();//设置工具条,同时获取固定URL参数
            if (!IsPostBack)
            {
                BindListControl(selectuserdept, "deptcode", "deptname", "select deptcode,deptname from gmis_dept order by deptname", "所有部门");
                SetFilter(selectuserdept,GetPageParameter("selectuserdept","0"));
                
            }
            string h_fstr = "";
            if (selectuserdept.SelectedItem != null &&  selectuserdept.SelectedItem.Value != "0")
                h_fstr += " and gmis_user.deptcode=" + selectuserdept.SelectedItem.Value;
			
            
			db.SqlString="select usergroupcode,usergroupname from gmis_usergroup";
			gdt=db.GetDataTable();
			
			list.Rows = GetListRows();
            list.SqlStr = GetListSQL(Convert.ToInt32(navindex) * list.Rows, list.Rows,h_fstr);
           
        }
        private string GetListSQL(int fromcount,int pagerows,string fstr)
        {
            return "declare @allcount int;select @allcount=count(1) from gmis_user where 1=1 "+fstr+";select @allcount as allcount,'" + StringUtility.StringToBase64("view") + "'," + mid + ",usercode," + navindex + ",username,g.deptname,'' as usergroupname,userstate,'&nbsp;' as btnstr,usergroupcode from gmis_user  Left Outer Join gmis_dept g on gmis_user.deptcode=g.deptcode where usercode not in (select top " + fromcount + " usercode from gmis_user where 1=1 "+fstr+" order by username) and usercode in (select top " + (fromcount+pagerows) + " usercode from gmis_user where 1=1 "+fstr+" order by username) "+fstr+" order by username";
        }
        public override void BeforeOutput(DataTable dt, int rowi)
        {	
            //处理当前页数据
            DataRow dr = dt.Rows[rowi];
            if(dr["usergroupcode"].ToString().Trim(',').Length>0)
			{
				DataRow[] drs=gdt.Select("usergroupcode in ("+dr["usergroupcode"].ToString().Trim(',')+")"); 
				string h_name="";
				foreach(DataRow idr in drs)
				{
					h_name+=idr["usergroupname"].ToString()+",";
				}
				dr["usergroupname"]=h_name.Trim(',');
			}
			dr["deptname"]=(dr["deptname"].ToString()=="")?"&nbsp;":dr["deptname"].ToString();
            if (mua.IndexOf(";3;") != -1)
            {
                dr["btnstr"] += "<a href=\"getpage.aspx?aid=" + StringUtility.StringToBase64("edit") + "&mid=" + mid + "&id=" + dr["usercode"].ToString() + "&navindex=" + navindex + "\"><img src=\"images/icons/tb02.gif\" title=\"编辑\" align=\"absmiddle\" border=\"0\"></a>";
            }
            
            if (mua.IndexOf(";4;") != -1)
            {
                dr["btnstr"] += "<a href=\"javascript:if(confirm('确认删除吗！')){document.location='getpage.aspx?aid=" + StringUtility.StringToBase64("delete") + "&mid=" + mid + "&id=" + dr["usercode"].ToString() + "&navindex=" + navindex + "';}\"><img src=\"images/icons/tb03.gif\" title=\"删除\" align=\"absmiddle\" border=\"0\"></a>";
            }
          
        }
        private void Filter_Change(object sender, System.EventArgs e)
        {
            SetPageParameter("selectuserdept",MIS.GetControlValue(selectuserdept));
            Response.Redirect("list_user.aspx?mid="+mid+"");
            
        }
	</script>
<body style="padding:10px;text-align:center">
<form id="form1" runat="server">
<!--选项卡-->
<!--选项卡-->
<!--操作工具条-->
    <!--#include file="toolbarleft.aspx"--> 
			<td style="padding-left:5px;">
				<table  border="0" cellspacing="0" cellpadding="0" ID="Table1" align=left>
				<tr>
					<td class="font_blackcolor_B" nowrap>部门：</td>
					<td >
						<asp:DropDownList id="selectuserdept" autopostback="true" OnSelectedIndexChanged="Filter_Change" style="width:100px" runat="server"/>
					</td>
				</tr>
				</table>
			</td>			
			<!--右边固定按钮-->
			<!--#include file="toolbar.aspx"-->
			<!--右边固定按钮-->
	<!--#include file="toolbarright.aspx"-->   
<!--操作工具条-->
<X:ListTable ID="list" Rows="20" IsProPage="true" runat="server">
<X:Template id="listtemphead" type="head" runat="server">
<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="righttableline">
    <tr class="tr_listtitle">
		<td width="30%" align="left">用户名</td>
		<td width="20%" align="left">部门</td>
		<td width="30%" align="left">所属角色</td>		
		<td width="12%" align="left">状态</td>	
		<td width="8%" align="center">操作</td>
    </tr>
</X:Template>
<X:Template id="listtemp1" runat="server">
    <tr class="tr_listcontent">
        <td align="left"><a href="view_user.aspx?aid=*&mid=*&id=*&navindex=*" title="查看明细">*</a>&nbsp;</td>
        <td align="left">*&nbsp;</td>
        <td align="left">*&nbsp;</td>
        <td align="left">*&nbsp;</td>        
        <td align="center">*&nbsp;</td>
    </tr>
</X:Template>
<X:Template id="listtemp2" runat="server">
    <tr  class="tr_listcontent">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>      
    </tr> 
 </X:Template>
<X:NavStyle5 id="Navstyle2" class="tdbgground_nav" runat="server" prevtext="上一页" nexttext="下一页"></X:NavStyle5>
</X:ListTable>
</form>
</body>
</html>
