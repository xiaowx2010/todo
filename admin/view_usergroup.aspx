<%@ Page Language="c#" Inherits="MIS.WebApplication.Controls.CheckLoginPage"%>
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
            SetToolBar();//���ù�����,ͬʱ��ȡ�̶�URL���� 
			view.SqlStr = "select usergroupname,usergroupstate,usergroupdesc from gmis_usergroup where usergroupcode="+id;
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
<!--����-->
<X:Content id="view" runat="server">
<X:Template id="Template1" runat="server">
<table width="100%"  border="0" cellpadding="3" cellspacing="1" class="table_graybgcolor">
    <tr> 
        <td align="right" class="td_viewcontent_title">�û���ɫ��</td>
        <td class="td_viewcontent_content">*&nbsp;</td>
        <td align="right" class="td_viewcontent_title">״̬��</td>
        <td class="td_viewcontent_content">*&nbsp;</td>
    </tr>
    <tr> 
        <td align="right" class="td_viewcontent_title">��ע��</td>
        <td class="td_viewcontent_content" colspan="3">*&nbsp;</td>
    </tr>
</table>
</X:Template>                    
</X:Content>                                
</form>
</body>
</html>