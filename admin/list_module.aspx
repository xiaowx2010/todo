<%@ Page Language="c#"  Inherits="MIS.WebApplication.Controls.CheckLoginPage"%>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
<HTML>
<HEAD>	
	<X:HtmlHead ID="MIS" runat="server"></X:HtmlHead>
	<meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5">
</HEAD>
<!--#include file="func.aspx"-->
<script language="C#" runat="server">
private void Page_Load(object sender, System.EventArgs e)
{    
	if(!IsSystemDeveloper())
	{
		Response.Redirect("getpage.aspx");
	}
 
    SetToolBar();//���ù�����,ͬʱ��ȡ�̶�URL����   
    
     
    
    if(id!="0")
    {
        SetPageParameter(null);
        SetPageParameter("id",id);
    }
    
    string h_fstr = "";
    
    if(GetPageParameter("id","0")!="0")
    {
        tb_selectlevel.Visible=false;
        tb_searchkey.Visible=false;
        tb_viewall.Visible=true;
        h_fstr = " and moduleindex like '%'+(select moduleindex from gmis_module where modulecode ="+GetPageParameter("id","0")+")+'%'";
    }
    else
    {
        if (!IsPostBack)
        {        
            BindListControl(selectlevel, "modulelevel", "levelname", "select distinct modulelevel,'��'+Cast(modulelevel as varchar(50))+'��' as levelname from gmis_module where modulestate=0 and modulelevel<>0","ȫ��ģ��");
            SetFilter(selectlevel,GetPageParameter("level","0"));
            searchkey.Value=GetPageParameter("key","");
        }
        
        string h_level=MIS.GetControlValue(selectlevel);
        string h_key=MIS.GetControlValue(searchkey);
        if (h_level != "" && h_level!="0" && id=="0")
        {
            h_fstr = " and modulelevel="+h_level+"";
        }
        if(h_key.Length>0 && id=="0")
        {
            h_fstr += " and modulename like '%"+searchkey.Value+"%'";
        } 
    
    }
      
    list.Rows = GetListRows();
   
    list.SqlStr = GetListSQL(Convert.ToInt32(navindex) * list.Rows, list.Rows,h_fstr);
}
private string GetListSQL(int fromcount,int pagerows,string fstr)
{
    return "declare @allcount int;select @allcount=count(1) from gmis_module where 1=1 "+fstr+";select @allcount as allcount,modulecode,'' as prename,'"+StringUtility.StringToBase64("view")+"',"+mid+",modulecode,"+navindex+",modulename,'"+StringUtility.StringToBase64("list")+"',"+mid+",moduleuppercode,(select t1.modulename from gmis_module t1 where t1.modulecode=t2.moduleuppercode) as moduleuppername,modulebrief,case modulestate when 0 then '����'  when 1 then '����' else '����' end ,'&nbsp;' as btnstr,modulelevel from gmis_module t2  where 1=1 "+fstr+" and modulecode not in (select top " + fromcount + " modulecode from gmis_module where 1=1 "+fstr+" order by moduleindex) and modulecode in (select top " + (fromcount+pagerows) + " modulecode from gmis_module where 1=1 "+fstr+" order by moduleindex) order by moduleindex";
}

private void Click_ViewAll(object sender, System.EventArgs e)
{
    Response.Redirect("getpage.aspx?mid="+mid);
}


private void Filter_Change(object sender, System.EventArgs e)
{
    SetPageParameter("level",MIS.GetControlValue(selectlevel));
    SetPageParameter("key",MIS.GetControlValue(searchkey));
    Response.Redirect("list_module.aspx?mid="+mid);
}
public override void BeforeOutput(DataTable dt, int rowi)
{
    DataRow dr = dt.Rows[rowi]; 
    if(!Convert.IsDBNull(dr["modulelevel"]))
    {
        for(int i=1;i<Convert.ToInt32(dr["modulelevel"]);i++)
        {
            dr["prename"]="&nbsp;&nbsp;&nbsp;&nbsp;"+dr["prename"].ToString();
        }  
    }
    
    dr["btnstr"]+= "<a href=\"getpage.aspx?aid=" + StringUtility.StringToBase64("list") + "&mid=" + mid + "&id=" + dr["modulecode"].ToString() + "\"><img src=\"images/icons/tb16.gif\" title=\"��ʾģ�鼰�����¼�ģ���б�\" align=\"absmiddle\" border=\"0\"></a>";
    if (mua.IndexOf(";3;") != -1)
    {
        dr["btnstr"] += "<a href=\"getpage.aspx?aid=" + StringUtility.StringToBase64("edit") + "&mid=" + mid + "&id=" + dr["modulecode"].ToString() + "&navindex=" + navindex + "\"><img src=\"images/icons/tb02.gif\" title=\"�༭\" align=\"absmiddle\" border=\"0\"></a>";
    }

    if (mua.IndexOf(";4;") != -1)
    {
        dr["btnstr"] += "<a href=\"javascript:if(confirm('ȷ��ɾ����')){document.location='getpage.aspx?aid=" + StringUtility.StringToBase64("delete") + "&mid=" + mid + "&id=" + dr["modulecode"].ToString() + "&navindex=" + navindex + "';}\"><img src=\"images/icons/tb03.gif\" title=\"ɾ��\" align=\"absmiddle\" border=\"0\"></a>";
    }
   
    
    
    
}
</script>
<body style="padding:10px;text-align:center">
<form id="form1" runat="server">
<!--ѡ�-->
<!--ѡ�-->
<!--����������-->
    <!--#include file="toolbarleft.aspx"--> 
			<td id="tb_selectlevel" style="padding-left:5px;" runat="server">
				<table  border="0" cellspacing="0" cellpadding="0" ID="Table1" align=left>
				<tr>
					<td >
						<asp:DropDownList ID="selectlevel" style="width:150px;" AutoPostBack="true" OnSelectedIndexChanged="Filter_Change" runat="server">
						</asp:DropDownList>
					</td>
				</tr>
				</table>
			</td>
			<td id="tb_searchkey" style="padding-left:5px;" runat="server">
				<table  border="0" cellspacing="0" cellpadding="0" ID="Table2" align=left>
				<tr>
					<td  nowrap><input type="text" id="searchkey" runat="server" style="width:150px;" /></td>
					<td  nowrap style="padding-left:5px;"><X:Button id="btn_search" type="toolbar" mode="on" icon="tb08" text="��ѯ" onclick="Filter_Change" runat="server"></X:Button> </td>
				</tr>
				</table>
			</td>
			<td id="tb_viewall" visible="false" style="padding-left:5px;" runat="server">
				<table  border="0" cellspacing="0" cellpadding="0" ID="Table3" align=left>
				<tr>
					<td  nowrap style="padding-left:5px;"><X:Button id="btn_viewall" type="toolbar" mode="on" icon="tb15" text="�г�����ģ��" onclick="Click_ViewAll" runat="server"></X:Button> </td>
				</tr>
				</table>
			</td>
						
			<!--�ұ߹̶���ť-->
			<!--#include file="toolbar.aspx"-->
			<!--�ұ߹̶���ť-->
	<!--#include file="toolbarright.aspx"-->   
<!--����������-->
<X:ListTable ID="list" Rows="20" IsProPage="true" runat="server">
<X:Template id="listtemphead" type="head" runat="server">
<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="righttableline">
  <tr class="tr_listtitle">
    <td width="50" align="center" nowrap>ID</td>
    <td width="35%" align="left" >ģ����</td>
    <td width="35%" align="left" >ֱ���ϼ�</td>
    <td width="10%" align="left" >��ʶ</td>
    <td width="10%" align="left" >״̬</td>
    <td width="10%" align="center" >����</td>
  </tr>
  </X:Template>
  <X:Template id="listtemp1" runat="server">
  <tr class="tr_listcontent">
    <td align="center" >*</td>
    <td align="left" >*<a href="view_module.aspx?aid=*&mid=*&id=*&navindex=*" title="�鿴��ϸ">*&nbsp;</a></td>
    <td align="left" ><a href="getpage.aspx?aid=*&mid=*&id=*" title="��ʾ��ģ�鼰�����¼�ģ���б�">*&nbsp;</a></td>
    <td align="left" >*&nbsp;</td>     
    <td align="left" >*&nbsp;</td>    
    <td align="center" >*</td> 
  </tr>
  </X:Template>
  <X:Template id="listtemp2" runat="server">
  <tr  class="tr_listcontent">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr> 
 </X:Template>
 <X:NavStyle5 ID="NavStyle" PageUrl="list_module.aspx" runat="server"></X:NavStyle5>
</X:ListTable>
</form>
</body>
</html>