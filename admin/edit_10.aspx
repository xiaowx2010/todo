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
	SetToolBar();//���ù�����,ͬʱ��ȡ�̶�URL����    
	dtable = "gmis_Mo_10";
    filter = " Mocode=" + id;
    flds = new string[] { "fld_10_1" , "fld_10_2","creatorcode"};
    types = "001";
    if (!IsPostBack) {//�Ȱ��б�
        creatorcode.Value = GetUID();//������ID
		if(id != "0"){//������ֵ
		    MIS.BindData(dtable,filter,flds);
		}
        
	}   
}


</script>
<body style="padding:10px;text-align:center">
<form id="form1" runat="server">
<!--ѡ�-->
<!--ѡ�-->
<!--����������-->
    <!--#include file="toolbarleft.aspx"--> 		
			<!--�ұ߹̶���ť-->
			<!--#include file="toolbar.aspx"-->
			<!--�ұ߹̶���ť-->
	<!--#include file="toolbarright.aspx"-->   
<!--����������-->
<input id="creatorcode" runat="server" type="hidden" />
<table width="100%"  border="0" cellpadding="3" cellspacing="1" class="table_graybgcolor" >
	<tr>
	    <td align="right"  class="td_viewcontent_title">�������⣺</td>
	    <td colspan="3" class="td_viewcontent_content"><input id="fld_10_1" style="width:95%;" maxlength="200" class="boxbline" type="text" runat="server" /></td>
    </tr>
    <tr>
	    <td align="right" class="td_viewcontent_title" valign="top">�������ݣ�</td>
    
	    <td colspan="3" class="td_viewcontent_content"><X:Editor id="fld_10_2" height="300" width="100%" BasePath="Common/Editor/" runat="server" /></td>
    </tr>
</table>
<!--��̬���ɽ���-->
</form>
</body>
</html>