<%@ Page Language="c#" Inherits="MIS.WebApplication.Controls.CheckLoginPage"%>
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
    if (!IsPostBack)
    {        
       
    }
    
    view.SqlStr = "select modulename,modulebrief,modulelevel,(select t1.modulename from gmis_module t1 where t1.modulecode=t2.moduleuppercode) as moduleuppername,useactions,listhelp,edithelp,viewhelp from gmis_module t2 where modulecode="+id+" order by moduleindex asc ";
   
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
<X:Content ID="view" runat="server">
<X:Template id="tempview" runat="server">
<table width="100%"  border="0" cellpadding="3" cellspacing="1" class="table_graybgcolor">
    <tr>
      <td align="right" class="td_viewcontent_title">ģ������</td>
      <td class="td_viewcontent_content">*&nbsp;</td>
      <td align="right"  class="td_viewcontent_title">ģ���ʶ��</td>
      <td class="td_viewcontent_content">*&nbsp;</td>
    </tr> 
    <tr>
      <td align="right"  class="td_viewcontent_title">ģ��㼶��</td>
      <td class="td_viewcontent_content">*&nbsp;</td>
      <td align="right"  class="td_viewcontent_title">ֱ���ϼ���</td>
      <td class="td_viewcontent_content">*&nbsp;</td>
    </tr> 
        <tr> 
        <td class="td_viewcontent_title" align="right">��ز�����</td>
        <td class="td_viewcontent_content" colspan="3">
        *&nbsp;
        </td>
    </tr>
    <tr>
      <td align="right"  class="td_viewcontent_title">�б�ҳ������</td>
      <td class="td_viewcontent_content" colspan="3">*&nbsp;</td>      
    </tr>
    <tr>
      <td align="right"  class="td_viewcontent_title">�༭ҳ������</td>
      <td class="td_viewcontent_content" colspan="3">*&nbsp;</td>      
    </tr>
    <tr>
      <td align="right"  class="td_viewcontent_title">�鿴ҳ������</td>
      <td class="td_viewcontent_content" colspan="3">*&nbsp;</td>      
    </tr> 
  </table>
  </X:Template>
  </X:Content>
<!--����-->
</form>
</body>
</html>