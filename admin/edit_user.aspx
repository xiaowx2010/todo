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
            SetToolBar();//设置工具条,同时获取固定URL参数
            
            dtable = "gmis_user";
            filter = "usercode=" + id;
            flds = new string[] { "username", "userpassword", "deptcode", "userstate", "usertel", "useremail", "userdesc","usergroupcode","userrole","realname" };
            mflds = new string[] { "用户名|username", "密码|userpassword" };
            types = "0010000000";

            if (!IsPostBack)
            {   
                userrole.Items.Add("普通用户");
                userrole.Items.Add("系统管理员");
                userstate.Items.Add("启用");
                userstate.Items.Add("隐藏");
                userstate.Items.Add("禁用");
                BindListControl(deptcode, "deptcode", "deptname", "select deptcode,deptname from gmis_dept");
                
                if (id != "0")
                {//绑定所有值
                    MIS.BindData(dtable, filter, flds);
                    userpassword.Value = StringUtility.Base64ToString(userpassword.Value);
					db.SqlString="select usergroupcode,usergroupname from gmis_usergroup where usergroupcode in ("+usergroupcode.Value+")";
			        DataTable gdt=db.GetDataTable();                   
					string h_name="";
					foreach(DataRow idr in gdt.Rows)
					{
						h_name+=idr["usergroupname"].ToString()+",";
					}
					usergroupcode_txt.Value=h_name.Trim(',');
                }
                
            }
        }
        public override void BeforeSave()
        {
            //电子邮件格式验证
            if(useremail.Value.Length>0)
            {
                if(!IsEmail(useremail.Value))
                {
                    SetSESSION("alert", "电子邮件格式错误！");
                }
            }
            
            //验证是否有重名
            if (id == "0" && username.Value.Trim().Length != 0)
            {
                SqlDB db = new SqlDB();
                db.SqlString = "select usercode from gmis_user where username='" + MIS.GetControlValue(username).Trim() + "'";
                if (db.GetDataTable().Rows.Count > 0)
                {
                    SetSESSION("alert", "已有重名用户！");
                }
            }
            userpassword.Value = StringUtility.StringToBase64(userpassword.Value);
        }
	</script>
<body style="padding:10px;text-align:center">
<form id="form1" runat="server">
<!--选项卡-->
<!--选项卡-->
<!--操作工具条-->
    <!--#include file="toolbarleft.aspx"--> 
            <td style="width:100%"><div id="alert" runat="server"></div></td>
			<!--右边固定按钮-->
			<!--#include file="toolbar.aspx"-->
			<!--右边固定按钮-->
	   <!--#include file="toolbarright.aspx"--> 
<!--操作工具条-->
<!--内容-->
<table width="100%"  border="0" cellpadding="3" cellspacing="1" class="table_graybgcolor">
    <tr> 
        <td align="right"  class="td_viewcontent_title"><font color="red">*</font>用户名：</td>
        <td class="td_viewcontent_content">
        <input id="username" type="text" style="width:160px" maxlength="16" class="boxbline" runat="server" /></td>
        <td align="right"  class="td_viewcontent_title"><font color="red">*</font>密码：</td>
        <td class="td_viewcontent_content">
        <input id="userpassword" type="text" style="width:160px" maxlength="100" class="boxbline" runat="server" /></td>
    </tr>
    <tr> 
        <td align="right"  class="td_viewcontent_title"><font color="red">*</font>真实姓名：</td>
        <td class="td_viewcontent_content">
		<input id="realname" type="text" style="width:160px" maxlength="20" class="boxbline" runat="server" /></td>
        <td align="right"  class="td_viewcontent_title">所属部门：</td>
        <td class="td_viewcontent_content">
		<asp:DropDownList id="deptcode" style="width:160px" runat="server"/></td>
        
    </tr>
    <tr> 
        <td align="right"  class="td_viewcontent_title">用户角色：</td>
        <td class="td_viewcontent_content" colspan="3">
        <table><tr><td width="95%" nowrap><input id="usergroupcode" runat="server" type="hidden" /><input id="usergroupcode_txt" style="width:95%"  class="boxbline" type="text" runat="server" readonly/></td><td style="padding-left:5px;width:120px;" nowrap><X:Button ID="btn_selectfold" Type="toolbar" Mode="on" Url="javascript:__doOpenView('pop_user_select.aspx?slist='+base64encode(form1.usergroupcode.value),390,260,10,50)" runat="server" Text="选择角色"></X:Button></td></tr></table></td>
    </tr>
    <tr> 
        <td align="right"  class="td_viewcontent_title">电话：</td>
        <td class="td_viewcontent_content" colspan="3">
        <input id="usertel" type="text" style="width:95%" maxlength="200" class="boxbline" runat="server"></td>
    </tr>
    <tr> 
        <td align="right"  class="td_viewcontent_title">电子邮件：</td>
        <td class="td_viewcontent_content" colspan="3">
        <input id="useremail" type="text" style="width:95%" maxlength="200" class="boxbline" runat="server"></td>
    </tr>
    <tr>
        <td align="right"  class="td_viewcontent_title">用户类型：</td>
        <td class="td_viewcontent_content">
        <asp:DropDownList ID="userrole" runat="server" Width="160" runat="server"></asp:DropDownList>
        </td>
        
        <td align="right"  class="td_viewcontent_title">状态：</td>
        <td class="td_viewcontent_content">
        <asp:DropDownList ID="userstate" Width="160" runat="server"></asp:DropDownList>
     </td>
    </tr>
    <tr> 
        <td align="right"  class="td_viewcontent_title">备注：</td>
        <td class="td_viewcontent_content" colspan="3">
        <textarea id="userdesc" rows="4" style="width:95%; height:150px;" class="boxbline" runat="server"></textarea></td>
    </tr>
</table>         
</form>
</body>
</html>
