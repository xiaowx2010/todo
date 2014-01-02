<!--部门管理 [编辑页]-->
<%@ Page Language="c#"  Inherits="MIS.WebApplication.Controls.CheckLoginPage"%>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
<html>
<head>	
	<X:HtmlHead ID="MIS" runat="server"></X:HtmlHead>
	<meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5">
	<script type="text/javascript">
	</script>
</head>
<!--#include file="func.aspx"-->
<script runat="server" language="C#">
private void Page_Load(object sender, System.EventArgs e)
{
	SetToolBar();//设置工具条,同时获取固定URL参数
    
	dtable = "gmis_Dept";
	filter = "DeptCode="+id;
    flds = new string[] { "DeptName", "DeptLocked", "DeptLevel", "DeptUpperCode", "DeptDesc", "DeptPosition" };
	types = "011101";

    if (!IsPostBack)
    {
        DeptLocked.Items.Add(new ListItem("启用", "0"));
        DeptLocked.Items.Add(new ListItem("禁用", "1"));
        BindDeptLevel();
        if (id != "0")
        {
            MIS.BindData(dtable, filter, flds);//绑定数据 

            if (DeptLevel.SelectedItem != null)
            {
                BindListControl(DeptUpperCodeSelect, "deptcode", "deptname", "select deptcode,deptname from gmis_dept where deptlocked=0 and DeptLevel=" + Convert.ToString(Convert.ToInt32(DeptLevel.SelectedItem.Value) - 1));

                if (DeptUpperCodeSelect.Items.Count == 0)
                {
                    DeptUpperCodeSelect.Items.Add(new ListItem("无", "0"));
                }

                SetFilter(DeptUpperCodeSelect, DeptUpperCode.Value);

            }
        }
        else
        {
            if (DeptLevel.SelectedItem != null)
            {
                BindListControl(DeptUpperCodeSelect, "deptcode", "deptname", "select deptcode,deptname from gmis_dept where deptlocked=0 and DeptLevel=" + Convert.ToString(Convert.ToInt32(DeptLevel.SelectedItem.Value) - 1));

                if (DeptUpperCodeSelect.Items.Count == 0)
                {
                    DeptUpperCodeSelect.Items.Add(new ListItem("无", "0"));
                }
            }
        }
    }
}

private void BindDeptLevel()
{
    SqlDB db = new SqlDB();
    db.SqlString = "select max(DeptLevel)+1 as deptlevel from gmis_dept where deptlocked=0";
    DataTable dt = db.GetDataTable();
    if (dt.Rows.Count > 0 && !Convert.IsDBNull(dt.Rows[0]["deptlevel"]))
    {
        int count = Convert.ToInt32(dt.Rows[0]["deptlevel"].ToString());
        for (int i = 0; i < count; i++)
        {
            DeptLevel.Items.Add(new ListItem((i + 1).ToString(), (i + 1).ToString()));
        }
    }
    else
    {
        DeptLevel.Items.Add(new ListItem("1", "1"));
    }
}

private void Change_Level(object sender, System.EventArgs e)
{
    DeptUpperCodeSelect.Items.Clear();
    if (DeptLevel.SelectedItem != null && DeptLevel.SelectedItem.Value != "0")
    {
        BindListControl(DeptUpperCodeSelect, "DeptCode", "DeptName", "select DeptCode,DeptName from gmis_Dept where DeptLevel=" + (Convert.ToInt32(DeptLevel.SelectedItem.Value) - 1) + " and deptcode<>" + id);
    }
    if (DeptLevel.SelectedItem.Value == "0")
    {
        DeptUpperCodeSelect.Items.Add(new ListItem("无", "0"));
    }

}

    public override void BeforeSave()
    {
        if (id == "0" && DeptName.Value.Trim() != "")
        {
            string h_fstr = "";
            if (DeptUpperCodeSelect.SelectedItem != null && DeptUpperCodeSelect.SelectedItem.Value != "0")
            {
                h_fstr = " and deptuppercode = " + DeptUpperCodeSelect.SelectedItem.Value;
            }
            if (DeptLevel.SelectedItem.Value == "1")
            {
                h_fstr = " and deptlevel = 1";
            }

            db.SqlString = "select * from gmis_dept where deptname='" + DeptName.Value.Trim() + "' " + h_fstr + "";
            if (db.GetDataTable().Rows.Count > 0)
            {
                SetSESSION("alert", "已有重名部门！");
                Response.Redirect("execommand.aspx?mid=" + mid + "&pid=" + pid + "&id=" + id);
            }
        }



        if (DeptLevel.SelectedItem.Value != "1" && DeptUpperCodeSelect.SelectedItem == null)
        {
            SetSESSION("alert", "无可选上级部门，请先创建一个一级部门！");
            Response.Redirect("execommand.aspx?mid=" + mid + "&pid=" + pid + "&id=" + id);
        }

        if (DeptLevel.SelectedItem != null && DeptUpperCodeSelect.SelectedItem != null)
        {
            db.SqlString = "select max(deptposition)+1 from gmis_dept where 1=1 and deptlevel=" + DeptLevel.SelectedValue + " and DeptUpperCode =" + DeptUpperCodeSelect.SelectedValue;
            if (db.GetDataTable().Rows.Count > 0)
                DeptPosition.Value = db.GetDataTable().Rows[0][0].ToString();
            else
                DeptPosition.Value = "1";

            DeptUpperCode.Value = DeptUpperCodeSelect.SelectedValue;
        }
        else
        {
            SetSESSION("alert", "层级和直属上级不能为空！");
            Response.Redirect("execommand.aspx?mid=" + mid + "&pid=" + pid + "&id=" + id);
        }
    }

    //上移/下移
    private void Click_ChangeIndex(object sender, System.EventArgs e)
    {
        string idstr = ((Control)sender).ID;
        alertmess.InnerText = ChangeOneTypeIndex(idstr, dtable, "deptcode", "deptindex", "deptposition", "deptuppercode");
    }

</script>
<body style="margin:10px 5px 10px 10px;;text-align:center">
<form id="form1" runat="server">
<!--选项卡-->
<!--选项卡-->
<!--操作工具条-->
    <!--#include file="toolbarleft.aspx"--> 
    <td id="tb_6" visible="false" runat="server" style="padding-left: 5px; width: 55px;"
            nowrap>
            <X:Button ID="btn_up" Type="toolbar" Mode="on" Text="上移" OnClick="Click_ChangeIndex"
                runat="server">
            </X:Button>
        </td>
        <td id="tb_7" visible="false" runat="server" style="padding-left: 5px; width: 55px;"
            nowrap>
            <X:Button ID="btn_down" Type="toolbar" Mode="on" Text="下移" OnClick="Click_ChangeIndex"
                runat="server">
            </X:Button>
        </td>
          <!--右边固定按钮-->
			<!--右边固定按钮-->
			<!--#include file="toolbar.aspx"-->
			<!--右边固定按钮-->
	   <!--#include file="toolbarright.aspx"--> 
<!--操作工具条-->
<!--内容-->
<table width="100%"  border="0" cellpadding="3" cellspacing="1" class="table_graybgcolor">
    <tr> 
        <td align="right" class="td_viewcontent_title">部门名称：</td>
        <td class="td_viewcontent_content"><input id="DeptName" type="text" style="width:160px" maxlength="100" runat="server" /></td>
        <td align="right" class="td_viewcontent_title">状态：</td>
        <td class="td_viewcontent_content"><asp:DropDownList ID="DeptLocked" runat="server" Width="160px" ></asp:DropDownList></td>
    </tr>
    <tr> 
        <td align="right" class="td_viewcontent_title">层级：</td>
        <td class="td_viewcontent_content">
            <asp:DropDownList ID="DeptLevel" runat="server" AutoPostBack="True" OnSelectedIndexChanged="Change_Level">                
            </asp:DropDownList></td>
        <td align="right" class="td_viewcontent_title">直属上级：</td>
        <td class="td_viewcontent_content"><asp:DropDownList ID="DeptUpperCodeSelect" runat="server" Width="160px"></asp:DropDownList>
        <input id="DeptUpperCode" type="hidden" runat="server" />
        <input id="DeptPosition" style="width:250px;" type="hidden" runat="server" />
        </td>
    </tr>
    <tr>
        <td align="right" class="td_viewcontent_title">备注：</td>
        <td class="td_viewcontent_content" colspan="3"><textarea id="DeptDesc" runat="server" rows="10" cols="20" style="width:100%"></textarea></td>
    </tr>
</table>         
</form>
</body>
</html>
