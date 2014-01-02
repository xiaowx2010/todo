<!-- 内部邮件：用户选择 [弹出窗口] -->
<%@ Page Language="c#"  Inherits="MIS.WebApplication.Controls.CheckLoginPage"%>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
<HTML>
<HEAD>	
	<X:HtmlHead ID="MIS" runat="server"></X:HtmlHead>
	<meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5">
</HEAD>
<!--#include file="func.aspx"-->
	<script runat=server language=C#>
	string p_fstr="";
	private void Page_Load(object sender, System.EventArgs e)
	{
        string uidstr = GetQueryString("uidstr",""); 
        string type = GetQueryString("type","0");
        if (!IsPostBack)
        {
            BindListControl(selecttype, "usergroupcode", "usergroupname", "select usergroupcode,usergroupname from gmis_usergroup where  UsergroupState='启用' ", "--所有用户--");
            SetFilter(selecttype, "0");
            if(uidstr.Trim(',')!="")
            {
				p_fstr=" and usercode not in ("+uidstr.Trim(',')+")";
				BindListControl(oselectuser, "usercode", "username", "select usercode,username from gmis_user where  UserState='启用' and usercode in ("+uidstr.Trim(',')+") order by username");
				BindOselectedUser();
			}
			
			BindListControl(selectuser, "usercode", "username", "select usercode,username from gmis_user where  UserState='启用' "+p_fstr+" order by username");
		    typestate.Value=type;			
        }
	        
			
		}
        private void Change_Type(object sender, System.EventArgs e)
        {
            selectuser.Items.Clear();
            BindOselectedUser();
            if (selecttype.SelectedItem != null && selecttype.SelectedItem.Value != "0")
            {
                
                BindListControl(selectuser, "usercode", "username", "select usercode,username from gmis_user where UserState='启用' and usercode not in (" + oselectpycode.Value + "0) and usergroupcode=" + selecttype.SelectedItem.Value);
            }
            else
            {
                BindListControl(selectuser, "usercode", "username", "select usercode,username from gmis_user where UserState='启用' and usercode not in (" + oselectpycode.Value + "0) " );
            }           
        }
  
        private void Add_Select(object sender, System.EventArgs e)
        {
            for (int i = 0; i < selectuser.Items.Count; i++)
            {
                if(selectuser.Items[i].Selected == true)
                {
                    //已选择
                    oselectuser.Items.Add(new ListItem(selectuser.SelectedItem.Text, selectuser.SelectedValue));
                    //未选择
                    selectuser.Items.Remove(new ListItem(selectuser.SelectedItem.Text, selectuser.SelectedValue));            
                    BindOselectedUser();
                    break;
                }
            }
            
        }
	
	    //添加所有
        private void Add_AllSelect(object sender, System.EventArgs e)
        {
			////从未选择列表，添加已选择列表
			for(int i=0;i<selectuser.Items.Count;i++)
			{
				oselectuser.Items.Add(new ListItem(selectuser.Items[i].Text,selectuser.Items[i].Value));
			}	
            //移除
			for(int i=0;i<oselectuser.Items.Count;i++)
			{
				selectuser.Items.Remove(new ListItem(oselectuser.Items[i].Text,oselectuser.Items[i].Value));
            }	 
			BindOselectedUser();
          
        }
	    //移除单项
        private void Remove_Select(object sender, System.EventArgs e)
        {
            for (int i = 0; i < oselectuser.Items.Count; i++)
            {
                if (oselectuser.Items[i].Selected == true)
                { 
                    selectuser.Items.Add(new ListItem(oselectuser.SelectedItem.Text, oselectuser.SelectedValue));
                    oselectuser.Items.Remove(new ListItem(oselectuser.SelectedItem.Text, oselectuser.SelectedValue));            
			        BindOselectedUser();
                    break;
                }
            }
                
         
        }
	    //移除所有
         private void Remove_AllSelect(object sender, System.EventArgs e)
        {
			//从已选择列表，移到未选择列表
			for(int i=0;i<oselectuser.Items.Count;i++)
			{
				selectuser.Items.Add(new ListItem(oselectuser.Items[i].Text,oselectuser.Items[i].Value));
			}	
            //移除已选择除表
			for(int i=0;i<selectuser.Items.Count;i++)
			{
				
				oselectuser.Items.Remove(new ListItem(selectuser.Items[i].Text,selectuser.Items[i].Value));
			}	 
			BindOselectedUser();
          
        }
        private void BindOselectedUser()
        {                  
            string pycodestr = "",pytxtstr="";
            for (int i = 0; i < oselectuser.Items.Count; i++)
            {
                pycodestr += oselectuser.Items[i].Value + ",";
                pytxtstr += oselectuser.Items[i].Text + ",";

            }
            oselectpycode.Value = pycodestr;
            oselectpytxt.Value = pytxtstr.Trim(',');
        }         	
	</script>
	
	<body bgcolor="#E2E2E2" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<form id="form1" runat="server">
		    <input id="oselectpycode" type="hidden" runat="server">
		    <input id="oselectpytxt" type="hidden" runat="server">
		    <input id="typestate" type="hidden" runat="server">
			<table align="center">
				<tr>
					<td>
						<table>
							<tr>
								<td>
								<asp:DropDownList ID="selecttype" OnSelectedIndexChanged="Change_Type"  Width="150" runat="server" AutoPostBack="true"></asp:DropDownList>
								</td>									 
							</tr>
							<tr>
								<td>								
									<asp:ListBox ID="selectuser" runat="server" Width="150"  Height="200"></asp:ListBox>								
								</td>
							</tr>
						</table>
						
					</td>
					<td>
						<table>	
														<tr>
							<td align="center">
								<asp:LinkButton id="btn_right1" onclick="Add_Select" runat="server"><img src="images/but_right1.gif" style="cursor:hand;" value=">" title="单项添加" border=0></asp:LinkButton>
							</td>
							</tr>	
							<tr>
							<td align="center">
								<asp:LinkButton id="btn_right2" onclick="Add_AllSelect" runat="server"><img src="images/but_right2.gif" style="cursor:hand;" value=">>" title="添加所有" border=0></asp:LinkButton>
							</td>
							</tr>
							<tr>
							<td align="center">
								<asp:LinkButton id="btn_left1" onclick="Remove_Select" runat="server"><img src="images/but_left1.gif" style="cursor:hand;" value=">" title="单项移除" border=0></asp:LinkButton>
							</td>
							</tr>	
							<tr>
							<td align="center">
								<asp:LinkButton id="btn_left2" onclick="Remove_AllSelect" runat="server"><img src="images/but_left2.gif" style="cursor:hand;" value=">>" title="移除所有" border=0></asp:LinkButton>
							</td>
							</tr>					
							<tr>
								<td><img src="images/but_submit.gif" value="确定" onclick="javascript:SaveSelected();" style="cursor:hand;">
								</td>
							</tr>
						</table>
					</td>
					<td><table>
							<tr>
								<td>已选内容</td>
							</tr>
							<tr>
								<td><asp:ListBox ID="oselectuser" runat="server" Width="150"  Height="200"></asp:ListBox></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<script language="javascript">
<!--

function SaveSelected()
{
	
	if(window.form1.typestate.value=="1")
	{
		
		opener.form1.fld_28_2_text.value=window.form1.oselectpytxt.value;
		opener.form1.fld_28_2.value=window.form1.oselectpycode.value;
	}

	if(window.form1.typestate.value=="2")
	{
		opener.form1.fld_28_3_text.value=window.form1.oselectpytxt.value;
		opener.form1.fld_28_3.value=window.form1.oselectpycode.value;
	}
	if(window.form1.typestate.value=="3")
	{
		opener.form1.fld_28_4_text.value=window.form1.oselectpytxt.value;
		opener.form1.fld_28_4.value=window.form1.oselectpycode.value;
	}
	window.close();
	//alert(window.form1.oselectpytxt.value+"    "+window.form1.oselectpycode.value);
}

-->
			</script>
		</form>
	</body>
</html>