<%@ Page Language="c#"  Inherits="MIS.WebApplication.Controls.CheckLoginPage"%>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
<HTML>
<HEAD>	
	<X:HtmlHead ID="MIS" runat="server"></X:HtmlHead>
	<meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5">
</HEAD>
<!--#include file="func.aspx"-->
	<script runat="server" language="C#">
		string ss = "";
		private void Page_Load(object sender, System.EventArgs e)
		{
            this.Response.Cache.SetCacheability(HttpCacheability.ServerAndNoCache);
			SetToolBar();//设置工具条,同时获取固定URL参数 
			
			dtable = "gmis_usergroup";
			filter = " usergroupcode="+id;
			flds = new string[]{"usergroupname","usergroupstate","usergroupdesc"};
			mflds = new string[]{"用户组名|usergroupname"};
			types = "000";
			
			if (!IsPostBack) {
				if(id != "0"){//绑定所有值
					MIS.BindData(dtable,filter,flds);
				}else{
					ss = "none";
				}
			}
		}
		public override void BeforeSave(){
			if(usergroupname.Value.Trim() != ""){	
				string h_fstr=(id=="0")?"":" and usergroupcode<>"+id+"";							
				db.SqlString = "select usergroupcode from gmis_usergroup where usergroupname='"+usergroupname.Value.Trim()+"'"+h_fstr;
				if(db.GetDataTable().Rows.Count>0){
					SetSESSION("alert", "已有重名用户组！");
					Response.Redirect("execommand.aspx?mid="+mid+"");
				}
			}
		}
	</script>
<body style="padding:10px;text-align:center">
<form id="form1" runat="server">
<!--选项卡-->
<!--选项卡-->
<!--操作工具条-->
    <!--#include file="toolbarleft.aspx"--> 
			<!--右边固定按钮-->
			<!--#include file="toolbar.aspx"-->
			<!--右边固定按钮-->
	<!--#include file="toolbarright.aspx"-->   
<!--操作工具条-->
<table width="100%"  border="0" cellpadding="3" cellspacing="1" class="table_graybgcolor">
    <tr> 
        <td class="td_viewcontent_title" align="right">用户角色</td>
        <td class="td_viewcontent_content">
        <input id="usergroupname" type="text" style="width:160px" maxlength="100" runat="server" /></td>
        <td class="td_viewcontent_title" align="right">状态</td>
        <td class="td_viewcontent_content">
        <select id="usergroupstate" style="width:160px" runat="server">
            <option>启用</option>
            <option>禁用</option>
            <option>隐藏</option>
        </select></td>
    </tr>
    <tr style="display:<%=ss%>"> 
        <td class="td_viewcontent_title" align="right">模块权限</td>
        <td class="td_viewcontent_content" colspan="3">
        <iframe style="width:100%;height:220px;"  frameborder="0" scrolling="auto" src="edit_moduleright.aspx?id=<%=id%>" allowtransparency="true"></iframe></td>
    </tr>
    <tr style="display:<%=ss%>"> 
        <td class="td_viewcontent_title" align="right">区域权限</td>
        <td class="td_viewcontent_content" colspan="3">
        <iframe style="width:100%;height:220px;"  frameborder="0" scrolling="auto" src="edit_arearight.aspx?id=<%=id%>" allowtransparency="true"></iframe></td>
    </tr>
    <tr style="display:<%=ss%>"> 
        <td class="td_viewcontent_title" align="right">图层权限</td>
        <td class="td_viewcontent_content" colspan="3">
        <iframe style="width:100%;height:220px;"  frameborder="0" scrolling="auto" src="edit_typeright.aspx?id=<%=id%>" allowtransparency="true"></iframe></td>
    </tr>  
    <tr> 
        <td class="td_viewcontent_title" align="right">备注</td>
        <td class="td_viewcontent_content" colspan="3">
        <textarea id="usergroupdesc" rows="4" style="width:523px" runat="server"></textarea></td>
    </tr>
</table>     
</form>
</body>
</html>
