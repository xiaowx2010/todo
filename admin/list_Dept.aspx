<%@ Page Language="c#"  Inherits="MIS.WebApplication.Controls.CheckLoginPage"%>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
<html>
<head>	
	<X:HtmlHead ID="MIS" runat="server"></X:HtmlHead>
	<meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5" />
<script language="javascript" type="text/javascript">
</script>
</head>
<!--#include file="func.aspx"-->
	<script runat="server" language="C#">
        private void Page_Load(object sender, System.EventArgs e)
        {
            SetToolBar();//设置工具条,同时获取固定URL参数
            
			string deptlevel = GetQueryString("deptlevel", "0");
		
            if (!IsPostBack)
            {
                BindListControl(selectlevel, "DeptLevel", "levelname", "select distinct DeptLevel,'第'+Cast(DeptLevel as varchar(50))+'级' as levelname from gmis_dept where  DeptLevel!=0 ","全部");
                SetFilter(selectlevel, deptlevel);
            }
           
          BindListData();

        }
        
         private void BindListData()
        {
	        string h_fstr = "";
          
			if(selectlevel.SelectedItem!=null&&selectlevel.SelectedItem.Value!="0")
			{
				h_fstr += " and DeptLevel=" + selectlevel.SelectedItem.Value + "";
			}
        		
            int pagesize =  GetListRows();//获取列表每页显示的记录数
	        
	        string orderstr = " order by deptindex";//排序条件;
	        //设置列表控件显示行数
	        list.Rows=pagesize;
	        
	        //设置列表控件数据源
            list.SqlStr = "declare @allcount int;select @allcount=count(1) from gmis_dept where 1=1 " + h_fstr + " ;select top " + pagesize + " @allcount as allcount,'' as prename,'" + StringUtility.StringToBase64("view") + "'," + mid + ",deptcode," + navindex + ",deptname,case when (select t1.DeptName from gmis_dept t1 where t1.DeptCode=t2.DeptUpperCode) is null then '' else (select t1.DeptName from gmis_dept t1 where t1.DeptCode=t2.DeptUpperCode) end as DeptName,CASE  DeptLocked  when 0 then '启用' when 1 then '禁用' else '其他'end ,'&nbsp;' as btnstr,deptlevel from gmis_dept t2 where deptcode not in (select top " + pagesize*Convert.ToInt32(navindex) + " deptcode from gmis_dept where 1=1 " + h_fstr + " " + orderstr + ") " + h_fstr + " " + orderstr + " ";
           

       }
        
        public override void BeforeOutput(DataTable dt, int rowi)
        {	//处理当前页数据
            DataRow dr = dt.Rows[rowi];
            
            
            if(!Convert.IsDBNull(dr["deptLevel"]))
            {
                for(int i=1;i<Convert.ToInt32(dr["deptLevel"]);i++)
                {
                    dr["prename"]="&nbsp;&nbsp;&nbsp;&nbsp;"+dr["prename"].ToString();
                }  
            }
            
            if (mua.IndexOf(";3;") != -1)
            {
                dr["btnstr"] += "<a href=\"getpage.aspx?aid=" + StringUtility.StringToBase64("edit") + "&mid=" + mid + "&id=" + dr["deptcode"].ToString() + "&navindex=" + navindex + "\"><img src=\"images/icons/tb02.gif\" title=\"编辑\" align=\"absmiddle\" border=\"0\"></a>";
            }
            if (mua.IndexOf(";4;") != -1)
            {
                dr["btnstr"] += "<a href=\"javascript:if(confirm('确认要删除吗！')){document.location='getpage.aspx?aid=" + StringUtility.StringToBase64("delete") + "&mid=" + mid + "&id=" + dr["deptcode"].ToString() + "&navindex=" + navindex + "';}\"><img src=\"images/icons/tb03.gif\" title=\"删除\" align=\"absmiddle\" border=\"0\"></a>";
            }
      
        }

        //筛选
        private void Change_Level(object sender, System.EventArgs e)
        {
            string deptlevel="0";
           if (selectlevel.SelectedItem != null)
		    {       
				deptlevel=selectlevel.SelectedValue;
			} 
			
			
			Response.Redirect("list_dept.aspx?mid=" + mid+"&deptlevel="+deptlevel);
        }
        
  

</script>
<body style="margin:10px 5px 10px 10px;;text-align:center">
<form id="form1" runat="server">
<!--选项卡-->
<!--选项卡-->
<!--操作工具条-->
    <!--#include file="toolbarleft.aspx"-->
 
                    <td class="font_blackcolor" nowrap>层级</td>
					<td  style="padding-left:5px"><asp:DropDownList ID="selectlevel" style="width:80px;" AutoPostBack="true" runat="server" OnSelectedIndexChanged="Change_Level"></asp:DropDownList></td>
                    
                  
			<!--右边固定按钮-->
			<!--#include file="toolbar.aspx"-->
			<!--右边固定按钮-->
	<!--#include file="toolbarright.aspx"-->   
<!--操作工具条-->
<X:ListTable ID="list" Rows="20" IsProPage="true" runat="server">
<X:Template id="listtemphead" type="head" runat="server">
<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="righttableline">
  <tr class="tr_listtitle">
    <td width="60%" align="left" >部门名称</td>
    <td width="20%" align="left" >直属上级</td>
    <td width="10%" align="left" >状态</td>
    <td width="10%" align="center" >操作</td>
  </tr>
  </X:Template>
<X:Template id="listtemp1" runat="server">
    <tr class="tr_listcontent">
        <td align="left">*<a href="view_dept.aspx?aid=*&mid=*&id=*&navindex=*" title="查看明细">*&nbsp;</a></td>
        <td align="left">*&nbsp;</td>
        <td align="left">*&nbsp;</td>
        <td align="left">*&nbsp;</td>
    </tr>
</X:Template>
<X:Template id="listtemp2" runat="server">
    <tr  class="tr_listcontent">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr> 
</X:Template>
<X:NavStyle5 ID="NavStyle" PageUrl="list_Dept.aspx" runat="server"></X:NavStyle5>
</X:ListTable>  
</form>
</body>
</html>
