<%@ Page Language="c#"  Inherits="MIS.WebApplication.Controls.CheckLoginPage"%>
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
            SetToolBar();//���ù�����,ͬʱ��ȡ�̶�URL���� 
            dtable = "gmis_area";
            filter = " areacode=" + id;
            flds = new string[] { "areaname",  "areastate", "areauppercode", "arealevel", "areadesc" };
            mflds = new string[] { "������|areaname"};
            types = "00110";

            if (!IsPostBack)
            {                
                
                    BindListControl(arealevel, "level", "leveltxt", "select distinct arealevel as level,'�� '+cast(arealevel as varchar(10))+' ��' as leveltxt from "+dtable+"");
                    
                    if (arealevel.Items.Count == 0)
                    {
                        arealevel.Items.Add(new ListItem("�� 1 ��", "1"));
                    }else
                    {
			             arealevel.Items.Add(new ListItem("�� "+(arealevel.Items.Count+1)+" ��", (arealevel.Items.Count+1).ToString()));
                    }
                    
                    if(id!="0")
                    {
			            MIS.BindData(dtable,filter,flds);
                    } 
                           
                    if (arealevel.SelectedItem != null && arealevel.SelectedItem.Value != "1")
                    {
                        BindListControl(areauppertxt, "uppercode", "uppertxt", "select mocode as uppercode,fld_19_1 as uppertxt from " + dtable + " where arealevel="+(Convert.ToInt32(arealevel.SelectedItem.Value)-1)+"");
                    }
                    SetFilter(areauppertxt,areauppercode.Value);
                    
           }
        }


        private void Up_Click(object sender, EventArgs e)
        {
           
        }
        public override void BeforeSave()
        {
            if (arealevel.SelectedItem != null && arealevel.SelectedValue != "1" && areauppertxt.SelectedItem == null)
            {
                SetSESSION("alert", "ֱ���ϼ�����Ϊ�գ�");	
            }
            
            areauppercode.Value = (areauppertxt.SelectedItem != null) ? areauppertxt.SelectedItem.Value : "0";
            if(areaname.Value!="")
            {
		        string h_fstr="";
		        if(id!="0")
		        {
			        h_fstr=" and areacode<>"+id+"";
		        }    
		        DataTable dt=db.GetDataTable(db.ConnStr,"select top 1 areacode from "+dtable+" where areaname='"+areaname.Value+"' and areauppercode="+areauppercode.Value+""+h_fstr);		
		        if(dt.Rows.Count>0)
		        {
			        SetSESSION("alert","�Ѵ���ͬ������"+areaname.Value);			
		        }
            }
            if(GetSESSION("alert")!="")
            {
		        Response.Redirect("execommand.aspx?aid="+StringUtility.StringToBase64(aid)+"&mid="+mid+"&tid="+tid+"&id="+id+"&pid="+pid);
            }

        }
        //�ı�㼶
    private void Change_Level(object sender, System.EventArgs e)
    {
        areauppertxt.Items.Clear();
        if (MIS.GetControlValue(arealevel)!="" && MIS.GetControlValue(arealevel) != "1")
        {
            BindListControl(areauppertxt, "uppercode", "uppertxt", "select areacode as uppercode,areaname as uppertxt from " + dtable + " where arealevel=" + (Convert.ToInt32(MIS.GetControlValue(arealevel)) - 1) + " and areacode<>"+id);
        }
    }
     //����/����
private void Click_ChangeIndex(object sender, System.EventArgs e)
{
    string idstr = ((Control)sender).ID;
    alertmess.InnerText = ChangeOneTypeIndex(idstr,dtable,"areacode","areaindex","areaposition","areauppercode");
}


	</script>
	<script type="text/javascript">
	var pop ;	
	function ClearLink(){
		if(pop!=null){
			pop.close();
		}
	}
	function IniLink(){
		pop=null;		
	}
	</script>
<body style="padding:10px;text-align:center" onbeforeunload="ClearLink()">
<script type="text/javascript">
function GetData(args){	
	if(window.document.form1.areadata != null){
			window.document.form1.areadata.value=args;
		}	
	window.document.focus();
}
</script>
<form id="form1" runat="server">
<!--ѡ�-->
<!--ѡ�-->
<!--����������-->
    <!--#include file="toolbarleft.aspx"--> 
           <!--����Զ��尴ť-->
	<td id="tb_6" visible="false" runat="server" style="padding-left: 5px; width: 55px;"
            nowrap>
            <X:Button ID="btn_up" Type="toolbar" Mode="on" Text="����" OnClick="Click_ChangeIndex"
                runat="server">
            </X:Button>
        </td>
        <td id="tb_7" visible="false" runat="server" style="padding-left: 5px; width: 55px;"
            nowrap>
            <X:Button ID="btn_down" Type="toolbar" Mode="on" Text="����" OnClick="Click_ChangeIndex"
                runat="server">
            </X:Button>
        </td>

			<!--�ұ߹̶���ť-->
			<!--#include file="toolbar.aspx"-->
			<!--�ұ߹̶���ť-->
	   <!--#include file="toolbarright.aspx"--> 
<!--����������-->


<table width="100%"  border="0" cellpadding="3" cellspacing="1" class="table_graybgcolor">
    <tr> 
        <td class="td_viewcontent_title" align="right">��������</td>
        <td class="td_viewcontent_content" >
        <input id="areaname" type="text" style="width:98%" maxlength="200" runat="server" />
        </td>
  
        <td class="td_viewcontent_title" align="right">״̬��</td>
        <td class="td_viewcontent_content">
        <select id="areastate" style="width:160px" runat="server">
            <option>����</option>
            <option>����</option>
            <option>����</option>
        </select>
        </td>
    </tr>
	<tr>
		<td width="100" align="right"  class="td_viewcontent_title">�㼶:</td>
		<td class="td_viewcontent_content" style="width:35%"><asp:DropDownList id="arealevel" AutoPostBack=true OnSelectedIndexChanged="Change_Level"  style="width:95%;"  runat="server" /></td>
		
		<td width="100" align="right"  class="td_viewcontent_title">ֱ���ϼ�:</td>
		<td class="td_viewcontent_content" style="width:35%">
		<input id="areauppercode" type="hidden" runat="server">
		<asp:DropDownList id="areauppertxt" style="width:95%;"  runat="server" />
		</td>
		</tr>
    <tr>
        <td class="td_viewcontent_title" align="right" valign="top">���ܣ�</td>
        <td class="td_viewcontent_content" colspan="3" align="right">
        <X:Editor id="areadesc" height="300"  BasePath="Common/Editor/" runat="server"/>         
        </td>
    </tr>
</table>        
</form>
</body>
</html>
