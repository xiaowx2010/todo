<%@ Page Language="c#"  Inherits="MIS.WebApplication.Controls.CheckLoginPage"%>
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
			view.SqlStr = "select typename,typekind,typebrief,typeicon,typeicon,typestate,typedesc from gmis_type where typecode="+id;
	
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
<X:Content id="view" runat="server">
<X:Template id="Template1" runat="server">
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="commonbody">
    <tr> 
        <td align="right"  class="td_viewcontent_title">���ݲ�����</td>
        <td class="td_viewcontent_content" colspan="3">*&nbsp;</td>
    </tr>
    <tr> 
        <td align="right"  class="td_viewcontent_title">���</td>
        <td class="td_viewcontent_content">*&nbsp;</td>
        <td align="right"  class="td_viewcontent_title">��ƣ�</td>
        <td class="td_viewcontent_content">*&nbsp;</td>
    </tr>
    <tr> 
        <td align="right"  class="td_viewcontent_title">ͼ�꣺</td>
        <td class="td_viewcontent_content"><OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" style="WIDTH:16px;HEIGHT:16px;" codebase="Player/swflash.cab#version=7,0,0,0" WIDTH="16" HEIGHT="16" id="IconView" ALIGN="" VIEWASTEXT>
								<PARAM NAME=movie VALUE="/SpeedMap/Shell.aspx?com=IconSystem&mode=view&id=*"> 
								<PARAM NAME=quality VALUE=high>
								<PARAM NAME=bgcolor VALUE=#EEEEEE> 
								<PARAM NAME=wmode VALUE=transparent> 
								<EMBED src="/SpeedMap/Shell.aspx?com=IconSystem&mode=view&id=*" WIDTH="16" HEIGHT="16" NAME="IconView" bgcolor=#EEEEEE TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer"></EMBED>
						</OBJECT>  
		&nbsp;
		</td>
        <td align="right"  class="td_viewcontent_title">״̬��</td>
        <td class="td_viewcontent_content">*&nbsp;</td>
    </tr>
    <tr> 
        <td align="right"  class="td_viewcontent_title">��ע��</td>
        <td class="td_viewcontent_content" colspan="3">*&nbsp;</td>
    </tr>
</table>
</X:Template>                    
</X:Content>                       
</form>
</body>
</html>
