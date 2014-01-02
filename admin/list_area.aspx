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
       
        SetToolBar();//���ù�����,ͬʱ��ȡ�̶�URL���� 
   
        if (!IsPostBack)
        {
            BindListControl(selectlevel, "level", "leveltxt", "select distinct arealevel as level,'�� '+cast(arealevel as varchar(10))+' ��' as leveltxt from gmis_area", "ȫ���㼶");
            string h_level="0";
            if (GetSESSION("filter") != "")
            {     
			    //ʹ��������ʽȡ��Session�������     
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
    BindListData();//���б�ؼ�����Դ
 }
 private void BindListData()
 {
	string h_fstr="";
	if(selectlevel.SelectedItem!=null && selectlevel.SelectedItem.Value!="0")
	{
		h_fstr=" and arealevel="+selectlevel.SelectedItem.Value+"";
	}
		
    int pagesize =  GetListRows();//��ȡ�б�ÿҳ��ʾ�ļ�¼��
	int fromcount=Convert.ToInt32(navindex)*pagesize;//�����ѷ���ҳ���ļ�¼��
	string tablename="gmis_area";//���ݱ�����
    string orderstr = " order by areaindex";//��������;
	//�����б�ؼ���ʾ����
	list.Rows=pagesize;
	//�����б�ؼ�����Դ
    list.SqlStr = "declare @allcount int;select @allcount=count(1) from " + tablename + " where 1=1 " + h_fstr + " ;select @allcount as allcount,'' as preTopic,'" + StringUtility.StringToBase64("view") + "'," + mid + ",areacode,areaname as ����,case when areauppercode=0 then '&nbsp;' else (select areaname from " + tablename + " t1 where t1.areacode=t2.areauppercode ) end as ֱ���ϼ� ,arealevel,areastate,'' as btnstr,areacode,arealevel as level from " + tablename + " t2 where areacode in (select top " + (fromcount + pagesize) + " areacode from " + tablename + " where 1=1 " + h_fstr + " " + orderstr + ") and areacode not in (select top " + fromcount + " areacode from " + tablename + " where 1=1 " + h_fstr + "" + orderstr + " ) " + h_fstr + " " + orderstr + " ";
    

 }
 
 //����ǰҳ���ݣ������������ʾ�Ĳ���
public override void BeforeOutput(DataTable dt, int rowi)
{	

    DataRow dr = dt.Rows[rowi];
    //�ж϶Ա�ģ���Ƿ��б༭Ȩ�ޣ�3����༭������ID(�ɴӲ���ģ��鿴)
    if (mua.IndexOf(";3;") != -1)
    {
        dr["btnstr"] += "<a href=\"getpage.aspx?aid=" + StringUtility.StringToBase64("edit") + "&mid=" + mid + "&id=" + dr["areacode"].ToString() + "&navindex=" + navindex + "\"><img src=\"images/icons/tb02.gif\" title=\"�༭\" align=\"absmiddle\" border=\"0\"></a>";
    }
   
    //�ж϶Ա�ģ���Ƿ��б༭Ȩ�ޣ�4����༭������ID(�ɴӲ���ģ��鿴)
    if (mua.IndexOf(";4;") != -1)
    {
        dr["btnstr"] += "<a href=\"javascript:if(confirm('ȷ��ɾ����')){document.location='getpage.aspx?aid=" + StringUtility.StringToBase64("delete") + "&mid=" + mid + "&id=" + dr["areacode"].ToString() + "&navindex=" + navindex + "';}\"><img src=\"images/icons/tb03.gif\" title=\"ɾ��\" align=\"absmiddle\" border=\"0\"></a>";
    }
   
    for (int i = 1; i < Convert.ToInt32(dr["level"].ToString()); i++)
    {
        dr["preTopic"] = "&nbsp;&nbsp;&nbsp;&nbsp;" + dr["preTopic"].ToString();
    }
}
    //ɸѡ�㼶
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
        <!--ѡ�-->
        <!--ѡ�-->
        <!--����������-->
        <!--#include file="toolbarleft.aspx"-->
        <td>
            <asp:DropDownList ID="selectlevel" runat="server" Style="width: 120px;" AutoPostBack="true"
                OnSelectedIndexChanged="Change_Level">
            </asp:DropDownList></td>
        <!--�ұ߹̶���ť-->
        <!--#include file="toolbar.aspx"-->
        <!--�ұ߹̶���ť-->
        <!--#include file="toolbarright.aspx"-->
        <!--����������-->
        <X:ListTable ID="list" Rows="20" IsProPage="true" runat="server">
            <X:Template id="listtemphead" type="head" runat="server">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="righttableline">
                    <tr class="tr_listtitle">
                        <td width="56%" align="left">
                            ��������</td>
                        <td width="20%" align="left">
                            ֱ���ϼ�</td>
                        <td width="8%" align="left">
                            ����</td>
                        <td width="8%" align="left">
                            ״̬</td>
                        <td width="8%" align="center">
                            ����</td>
                    </tr>
            </X:Template>
            <X:Template id="listtemp1" runat="server">
                <tr class="tr_listcontent">
                    <td align="left">
                     *<a title="�鿴��ϸ" href="view_area.aspx?aid=*&mid=*&id=*">*</a></td>
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
