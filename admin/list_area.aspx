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
   
        if (!IsPostBack)
        {
            BindListControl(selectlevel, "level", "leveltxt", "select distinct arealevel as level,'第 '+cast(arealevel as varchar(10))+' 级' as leveltxt from gmis_area", "全部层级");
            string h_level="0";
            if (GetSESSION("filter") != "")
            {     
			    //使用正则表达式取出Session里的条件     
                Regex r ;
                Match m;
                r = new Regex(".*?arealevel=[\'](?<txt>[^\']+)[\'].*?", RegexOptions.IgnoreCase);	
                m = r.Match(GetSESSION("filter"));
                if (m.Success)
                {               
                    h_level=m.Result("${txt}");
                }  
    			
            }
           
            SetFilter(selectlevel, h_level);
            
        }
    BindListData();//绑定列表控件数据源
 }
 private void BindListData()
 {
	string h_fstr="";
	if(selectlevel.SelectedItem!=null && selectlevel.SelectedItem.Value!="0")
	{
		h_fstr=" and arealevel="+selectlevel.SelectedItem.Value+"";
	}
		
    int pagesize =  GetListRows();//获取列表每页显示的记录数
	int fromcount=Convert.ToInt32(navindex)*pagesize;//计算已翻过页数的记录数
	string tablename="gmis_area";//数据表名称
    string orderstr = " order by areaindex";//排序条件;
	//设置列表控件显示行数
	list.Rows=pagesize;
	//设置列表控件数据源
    list.SqlStr = "declare @allcount int;select @allcount=count(1) from " + tablename + " where 1=1 " + h_fstr + " ;select @allcount as allcount,'' as preTopic,'" + StringUtility.StringToBase64("view") + "'," + mid + ",areacode,areaname as 名称,case when areauppercode=0 then '&nbsp;' else (select areaname from " + tablename + " t1 where t1.areacode=t2.areauppercode ) end as 直属上级 ,arealevel,areastate,'' as btnstr,areacode,arealevel as level from " + tablename + " t2 where areacode in (select top " + (fromcount + pagesize) + " areacode from " + tablename + " where 1=1 " + h_fstr + " " + orderstr + ") and areacode not in (select top " + fromcount + " areacode from " + tablename + " where 1=1 " + h_fstr + "" + orderstr + " ) " + h_fstr + " " + orderstr + " ";
    

 }
 
 //处理当前页数据，处理操作列显示的操作
public override void BeforeOutput(DataTable dt, int rowi)
{	

    DataRow dr = dt.Rows[rowi];
    //判断对本模块是否有编辑权限，3代表编辑操作的ID(可从操作模块查看)
    if (mua.IndexOf(";3;") != -1)
    {
        dr["btnstr"] += "<a href=\"getpage.aspx?aid=" + StringUtility.StringToBase64("edit") + "&mid=" + mid + "&id=" + dr["areacode"].ToString() + "&navindex=" + navindex + "\"><img src=\"images/icons/tb02.gif\" title=\"编辑\" align=\"absmiddle\" border=\"0\"></a>";
    }
   
    //判断对本模块是否有编辑权限，4代表编辑操作的ID(可从操作模块查看)
    if (mua.IndexOf(";4;") != -1)
    {
        dr["btnstr"] += "<a href=\"javascript:if(confirm('确认删除吗！')){document.location='getpage.aspx?aid=" + StringUtility.StringToBase64("delete") + "&mid=" + mid + "&id=" + dr["areacode"].ToString() + "&navindex=" + navindex + "';}\"><img src=\"images/icons/tb03.gif\" title=\"删除\" align=\"absmiddle\" border=\"0\"></a>";
    }
   
    for (int i = 1; i < Convert.ToInt32(dr["level"].ToString()); i++)
    {
        dr["preTopic"] = "&nbsp;&nbsp;&nbsp;&nbsp;" + dr["preTopic"].ToString();
    }
}
    //筛选层级
    private void Change_Level(object sender, System.EventArgs e)
    {
        string h_fstr="";
        if(selectlevel.SelectedItem!=null )
        {
            h_fstr=" and arealevel='"+selectlevel.SelectedValue+"'";
        }
        SetSESSION("filter",h_fstr);
        if(h_fstr.Length>0)
        {
            Response.Redirect("list_area.aspx?mid="+mid+"&aid="+StringUtility.StringToBase64("list")+"");
        }
    }

</script>

<body style="padding: 10px; text-align: center">
    <form id="form1" runat="server">
        <!--选项卡-->
        <!--选项卡-->
        <!--操作工具条-->
        <!--#include file="toolbarleft.aspx"-->
        <td>
            <asp:DropDownList ID="selectlevel" runat="server" Style="width: 120px;" AutoPostBack="true"
                OnSelectedIndexChanged="Change_Level">
            </asp:DropDownList></td>
        <!--右边固定按钮-->
        <!--#include file="toolbar.aspx"-->
        <!--右边固定按钮-->
        <!--#include file="toolbarright.aspx"-->
        <!--操作工具条-->
        <X:ListTable ID="list" Rows="20" IsProPage="true" runat="server">
            <X:Template id="listtemphead" type="head" runat="server">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="righttableline">
                    <tr class="tr_listtitle">
                        <td width="56%" align="left">
                            区域名称</td>
                        <td width="20%" align="left">
                            直属上级</td>
                        <td width="8%" align="left">
                            级别</td>
                        <td width="8%" align="left">
                            状态</td>
                        <td width="8%" align="center">
                            操作</td>
                    </tr>
            </X:Template>
            <X:Template id="listtemp1" runat="server">
                <tr class="tr_listcontent">
                    <td align="left">
                     *<a title="查看明细" href="view_area.aspx?aid=*&mid=*&id=*">*</a></td>
                    <td align="left">
                        *</td>
                    <td align="left">
                        *</td>
                    <td align="left">
                        *</td>
                    <td align="center">
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
                <X:NavStyle5 ID="NavStyle" PageUrl="list_area.aspx" runat="server">
                </X:NavStyle5>
            </table>
        </X:ListTable>
    </form>
</body>
</html>
