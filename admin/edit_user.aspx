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
            
            dtable = "gmis_user";
            filter = "usercode=" + id;
            flds = new string[] { "username", "userpassword", "deptcode", "userstate", "usertel", "useremail", "userdesc","usergroupcode","userrole","realname" };
            mflds = new string[] { "�û���|username", "����|userpassword" };
            types = "0010000000";

            if (!IsPostBack)
            {   
                userrole.Items.Add("��ͨ�û�");
                userrole.Items.Add("ϵͳ����Ա");
                userstate.Items.Add("����");
                userstate.Items.Add("����");
                userstate.Items.Add("����");
                BindListControl(deptcode, "deptcode", "deptname", "select deptcode,deptname from gmis_dept");
                
                if (id != "0")
                {//������ֵ
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
            //�����ʼ���ʽ��֤
            if(useremail.Value.Length>0)
            {
                if(!IsEmail(useremail.Value))
                {
                    SetSESSION("alert", "�����ʼ���ʽ����");
                }
            }
            
            //��֤�Ƿ�������
            if (id == "0" && username.Value.Trim().Length != 0)
            {
                SqlDB db = new SqlDB();
                db.SqlString = "select usercode from gmis_user where username='" + MIS.GetControlValue(username).Trim() + "'";
                if (db.GetDataTable().Rows.Count > 0)
                {
                    SetSESSION("alert", "���������û���");
                }
            }
            userpassword.Value = StringUtility.StringToBase64(userpassword.Value);
        }
	</script>
<body style="padding:10px;text-align:center">
<form id="form1" runat="server">
<!--ѡ�-->
<!--ѡ�-->
<!--����������-->
    <!--#include file="toolbarleft.aspx"--> 
            <td style="width:100%"><div id="alert" runat="server"></div></td>
			<!--�ұ߹̶���ť-->
			<!--#include file="toolbar.aspx"-->
			<!--�ұ߹̶���ť-->
	   <!--#include file="toolbarright.aspx"--> 
<!--����������-->
<!--����-->
<table width="100%"  border="0" cellpadding="3" cellspacing="1" class="table_graybgcolor">
    <tr> 
        <td align="right"  class="td_viewcontent_title"><font color="red">*</font>�û�����</td>
        <td class="td_viewcontent_content">
        <input id="username" type="text" style="width:160px" maxlength="16" class="boxbline" runat="server" /></td>
        <td align="right"  class="td_viewcontent_title"><font color="red">*</font>���룺</td>
        <td class="td_viewcontent_content">
        <input id="userpassword" type="text" style="width:160px" maxlength="100" class="boxbline" runat="server" /></td>
    </tr>
    <tr> 
        <td align="right"  class="td_viewcontent_title"><font color="red">*</font>��ʵ������</td>
        <td class="td_viewcontent_content">
		<input id="realname" type="text" style="width:160px" maxlength="20" class="boxbline" runat="server" /></td>
        <td align="right"  class="td_viewcontent_title">�������ţ�</td>
        <td class="td_viewcontent_content">
		<asp:DropDownList id="deptcode" style="width:160px" runat="server"/></td>
        
    </tr>
    <tr> 
        <td align="right"  class="td_viewcontent_title">�û���ɫ��</td>
        <td class="td_viewcontent_content" colspan="3">
        <table><tr><td width="95%" nowrap><input id="usergroupcode" runat="server" type="hidden" /><input id="usergroupcode_txt" style="width:95%"  class="boxbline" type="text" runat="server" readonly/></td><td style="padding-left:5px;width:120px;" nowrap><X:Button ID="btn_selectfold" Type="toolbar" Mode="on" Url="javascript:__doOpenView('pop_user_select.aspx?slist='+base64encode(form1.usergroupcode.value),390,260,10,50)" runat="server" Text="ѡ���ɫ"></X:Button></td></tr></table></td>
    </tr>
    <tr> 
        <td align="right"  class="td_viewcontent_title">�绰��</td>
        <td class="td_viewcontent_content" colspan="3">
        <input id="usertel" type="text" style="width:95%" maxlength="200" class="boxbline" runat="server"></td>
    </tr>
    <tr> 
        <td align="right"  class="td_viewcontent_title">�����ʼ���</td>
        <td class="td_viewcontent_content" colspan="3">
        <input id="useremail" type="text" style="width:95%" maxlength="200" class="boxbline" runat="server"></td>
    </tr>
    <tr>
        <td align="right"  class="td_viewcontent_title">�û����ͣ�</td>
        <td class="td_viewcontent_content">
        <asp:DropDownList ID="userrole" runat="server" Width="160" runat="server"></asp:DropDownList>
        </td>
        
        <td align="right"  class="td_viewcontent_title">״̬��</td>
        <td class="td_viewcontent_content">
        <asp:DropDownList ID="userstate" Width="160" runat="server"></asp:DropDownList>
     </td>
    </tr>
    <tr> 
        <td align="right"  class="td_viewcontent_title">��ע��</td>
        <td class="td_viewcontent_content" colspan="3">
        <textarea id="userdesc" rows="4" style="width:95%; height:150px;" class="boxbline" runat="server"></textarea></td>
    </tr>
</table>         
</form>
</body>
</html>
