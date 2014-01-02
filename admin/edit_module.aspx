<!-- 系统管理：模块管理 [编辑] -->

<%@ Page Language="c#" Inherits="MIS.WebApplication.Controls.CheckLoginPage" %>
<%@ Import Namespace="System.IO" %>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
<html>
<head>
    <X:HtmlHead ID="MIS" runat="server">
    </X:HtmlHead>
    <meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5">
</head>
<!--#include file="func.aspx"-->
<!--#include file="modulefunc.aspx"-->
<script language="C#" runat="server">
	
    private void Page_Load(object sender, System.EventArgs e)
    {
		if(!IsSystemDeveloper())
		{
			Response.Redirect("getpage.aspx");
		}
		
        SetToolBar();//设置工具条,同时获取固定URL参数    
        tabid=GetQueryString("tabid","1");
        dtable = "gmis_module";
        filter = " modulecode=" + id;
        flds = new string[] { "modulename", "modulebrief", "modulestate", "modulelevel", "moduleuppercode", "useactions", "moduleposition", "moduleicon"};
        mflds = new string[] { "模块名|modulename" };
        types = "00111000010000";
		
		/**追加选项卡**/
		AddTab("1","基本信息");
		if(id!="0")
		{
			AddTab("2","字段结构");
		}
		/**追加选项卡**/
        string h_fstr = "";
        if (id != "0")
        {
            h_fstr = " or modulecode=" + id + "";
        }
        if (!IsPostBack)
        {
            BindListControl(useactions, "actioncode", "actionname", "select actioncode,actionname from gmis_action where modulecode=0 " + h_fstr);
            BindListControl(modulelevel, "modulelevel", "levelname", "select distinct modulelevel,'第'+Cast(modulelevel as varchar(50))+'级' as levelname from gmis_module where modulestate=0 and modulelevel<>0");

            modulestate.Items.Add(new ListItem("启用", "0"));
            modulestate.Items.Add(new ListItem("禁用", "1"));
            int h_level = modulelevel.Items.Count + 1;
            modulelevel.Items.Add(new ListItem("第" + h_level.ToString() + "级", h_level.ToString()));

            if (modulelevel.SelectedItem != null && modulelevel.SelectedItem.Value != "1")
            {
                BindListControl(moduleuppercodetxt, "modulecode", "modulename", "select modulecode,modulename from gmis_module where modulestate=0 and modulelevel=" + (Convert.ToInt32(modulelevel.SelectedItem.Value) - 1) + " and modulename not in ('操作','模块','字段管理')");
            }
            if (id != "0")
            {//绑定所有值
                MIS.BindData(dtable, filter, flds);
                if (modulelevel.SelectedItem != null && modulelevel.SelectedItem.Value != "1")
                {
                    BindListControl(moduleuppercodetxt, "modulecode", "modulename", "select modulecode,modulename from gmis_module where modulestate=0 and modulelevel=" + (Convert.ToInt32(modulelevel.SelectedItem.Value) - 1) + "");
                }
                SetFilter(moduleuppercodetxt, moduleuppercode.Value);
               
            }

            if (id != "0" && IsSystemDeveloper())
            {
                string h_mtable = GetModuleTableName(id);
                tb_createtable.Visible = !CheckSysObject(h_mtable);
                tb_6.Visible = true;
                tb_7.Visible = true;
                tb_createfile.Visible = true;
            }
            else
            {
                tb_createfile.Visible = false;
                tb_createtable.Visible = false;
                tb_6.Visible = false;
                tb_7.Visible = false;
            }
        }

    }
    //改变模块层级
    private void Change_Level(object sender, System.EventArgs e)
    {
        moduleuppercodetxt.Items.Clear();
        if (modulelevel.SelectedItem != null && modulelevel.SelectedItem.Value != "1")
        {
            BindListControl(moduleuppercodetxt, "modulecode", "modulename", "select modulecode,modulename from gmis_module where modulestate=0 and modulelevel=" + (Convert.ToInt32(modulelevel.SelectedItem.Value) - 1) + "  and modulename not in ('操作','模块','字段管理')");
        }
    }
    public override void BeforeSave()
    {
        moduleuppercode.Value = (moduleuppercodetxt.SelectedItem != null && moduleuppercodetxt.SelectedItem.Value != "") ? moduleuppercodetxt.SelectedItem.Value : "0";
        string h_fstr = "";
        if (moduleuppercode.Value != "")
        {
            h_fstr = " and moduleuppercode=" + moduleuppercode.Value + " ";

        }
        db.SqlString = "select max(moduleposition)+1 from " + dtable + " where 1=1 " + h_fstr;
        if (db.GetDataTable().Rows.Count > 0)
            moduleposition.Value = db.GetDataTable().Rows[0][0].ToString();
        else
            moduleposition.Value = "1";
        //新增
        if (id == "0")
        {
            if (modulename.Value.Trim() != "")
            {
                db.SqlString = "select modulecode from " + dtable + " where modulename='" + modulename.Value.Trim() + "'" + h_fstr;
                if (db.GetDataTable().Rows.Count > 0)
                {
                    SetSESSION("alert", "已有重名模块！");
                    Response.Redirect("execommand.aspx?mid=" + mid + "");
                }
            }
            SetSESSION("extsql", "SELECT @@IDENTITY AS 'ModuleCode'; DECLARE @MID int; SELECT @MID=@@IDENTITY;INSERT INTO gmis_field (fieldname,caption,modulecode,fieldtypecode,datalength,fieldindex,defaultvalue,fielddatasrc,oneditlist,editlistcolumnwidth,onedit,fullline,oneditmust,fieldstatus,isvital,IsPrimaryKey) VALUES ('MoCode','自动编号',@MID,2,4,1,'0','','0',0,'0','0','0',0,'1','1');INSERT INTO gmis_field (fieldname,caption,modulecode,fieldtypecode,datalength,fieldindex,defaultvalue,fielddatasrc,oneditlist,editlistcolumnwidth,onedit,fullline,oneditmust,fieldstatus,isvital) VALUES ('CreatorCode','创建者编号',@MID,2,4,2,'0','','0',0,'0','0','0',0,'1');INSERT INTO gmis_field (fieldname,caption,modulecode,fieldtypecode,datalength,fieldindex,defaultvalue,fielddatasrc,oneditlist,editlistcolumnwidth,onedit,fullline,oneditmust,fieldstatus,isvital,IsPrimaryKey) VALUES ('CreateDate','创建时间',@MID,7,8,3,'getdate()','','0',0,'0','0','0',0,'1','1')");
        }

		

    }
    //生成模块数据表
    private void Click_CreateTable(object sender, System.EventArgs e)
    {

        string h_mtable = GetModuleTableName(id);
        string exeinfo = "";
        DataRow[] fdr = GetFields(id).Select("fieldname<>'mocode'"," fieldindex ");
        StringBuilder createtablesb = new StringBuilder();
        if (fdr.Length > 0)
        {
            createtablesb.Append("Begin if not exists (select id from sysobjects where id = object_id('" + h_mtable + "')) create table [dbo].[" + h_mtable + "] (MoCode int IDENTITY(1,1) PRIMARY KEY CLUSTERED");
            foreach (DataRow dr in fdr)
            {
                string h_datalength = "";
                if (dr["sqlname"].ToString().ToLower() == "varchar")
                {
                    h_datalength = "(" + dr["datalength"].ToString() + ")";
                }
                string h_defaultvalue = dr["defaultvalue"].ToString();
                if (h_defaultvalue == "")
                {
                    h_defaultvalue = "''";
                }
                createtablesb.Append("," + dr["fieldname"].ToString() + " " + dr["sqlname"].ToString() + h_datalength + " not null Default(" + h_defaultvalue + ")");

            }
            createtablesb.Append("); End");
        }
        if (createtablesb.Length > 0)
        {
            db.SqlString = createtablesb.ToString();
            exeinfo = db.UpdateTable();
        }
        alertmess.InnerText = exeinfo;
    }
    //生成模块文件edit,list,view
    private void Click_CreateFile(object sender, System.EventArgs e)
    {
        string head_path = Server.MapPath("/admin/common/head.txt");
        string foot_path = Server.MapPath("/admin/common/foot.txt");
        StringBuilder h_sb = new StringBuilder();
        StringBuilder f_sb = new StringBuilder();
        if (File.Exists(head_path) && File.Exists(foot_path))
        {

            using (StreamReader sr = new StreamReader(head_path, System.Text.Encoding.Default, true))
            {
                String line;
                while ((line = sr.ReadLine()) != null)
                {
                    h_sb.Append(line + "\n");
                }
            }

            using (StreamReader sr = new StreamReader(foot_path, System.Text.Encoding.Default, true))
            {
                String line;              
                while ((line = sr.ReadLine()) != null)
                {
                    f_sb.Append(line + "\n");
                }
            }

            if (h_sb.Length > 0 && f_sb.Length > 0)
            {
                bool flag_create = false, flag_change = false;
                string cfile_path = "";
                System.Text.Encoding temp = System.Text.Encoding.Default;
                FileInfo fi;
                string[] h_aidarr = new string[] { "list", "edit", "view" };
                foreach (string h_aid in h_aidarr)
                {
                    cfile_path = Server.MapPath("/admin/" + h_aid + "_" + id + ".aspx");
                    flag_create = false;
                    flag_change = false;
                    if (!File.Exists(cfile_path))
                    {
                        fi = new FileInfo(cfile_path);
                        using (FileStream fs = fi.Create()) //写入到新创建的文件
                        {
                            byte[] b = temp.GetBytes("");
                            if (h_aid.ToLower() == "list")//列表
                            {
                                b = temp.GetBytes("<!--" + modulename.Value + ",列表页-->\n" + h_sb.ToString() + "<!--动态生成html,开始-->\n" + CreateListHTML(id) + "<!--动态生成html,结束-->\n" + f_sb.ToString());
                            }
                            else if (h_aid.ToLower() == "edit")//编辑
                            {
                                b = temp.GetBytes("<!--" + modulename.Value + ",编辑页-->\n" + h_sb.ToString() + "<!--动态生成html,开始-->\n" + CreateEditHTML(id,"edit") + "<!--动态生成html,结束-->\n" + f_sb.ToString());
                            }
                            else if (h_aid.ToLower() == "view")//查看
                            {
                                b = temp.GetBytes("<!--" + modulename.Value + ",查看页-->\n" + h_sb.ToString() + "<!--动态生成html,开始-->\n" + CreateEditHTML(id, "view") + "<!--动态生成html,结束-->\n" + f_sb.ToString());
                            }
                            fs.Write(b, 0, b.Length);
                            fs.Close();
                            flag_create = true;
                        }
                    }
                    else
                    {
                        h_sb.Remove(0, h_sb.Length);
                        using (StreamReader sr = new StreamReader(cfile_path, System.Text.Encoding.Default, true))
                        {
                            String line;
                            while ((line = sr.ReadLine()) != null)
                            {
                                if (line.IndexOf("<!--动态生成html,开始-->") > -1)
                                    break;
                                h_sb.Append(line + "\n");
                                
                            }
                        }
                        fi = new FileInfo(cfile_path);
                        fi.Delete();//把原来的文件删除;
                        using (FileStream fs = fi.Create()) //写入到新创建的文件
                        {
                            byte[] b = temp.GetBytes(""); ;
                            if (h_aid.ToLower() == "list")//列表
                            {
                                b = temp.GetBytes(h_sb.ToString() + "<!--动态生成html,开始-->\n" + CreateListHTML(id) + "<!--动态生成html,结束-->\n" + f_sb.ToString());
                            }
                            else if (h_aid.ToLower() == "edit")//编辑
                            {
                                b = temp.GetBytes(h_sb.ToString() + "<!--动态生成html,开始-->\n" + CreateEditHTML(id, "edit") +"<!--动态生成html,结束-->\n"+ f_sb.ToString());
                            }
                            else if (h_aid.ToLower() == "view")//查看
                            {
                                b = temp.GetBytes(h_sb.ToString() + "<!--动态生成html,开始-->\n" + CreateEditHTML(id, "view") +"<!--动态生成html,结束-->\n"+ f_sb.ToString());
                            }
                            fs.Write(b, 0, b.Length);
                            fs.Close();
                            flag_change = true;
                        }
                    }
                }
                if (flag_create)
                {
                    alertmess.InnerText = "成功，创建文件！";
                }
                else if (flag_change)
                {
                    alertmess.InnerText = "成功，修改文件！";
                }
                else
                {
                    alertmess.InnerText = "文件格式可能被破坏，无法创建/修改！";
                }

            }

        }
        else
        {
            alertmess.InnerText = "模版文件common/head.txt或common/foot.txt不存在！";
        }
      
    }
    //模块上移/下移
    private void Click_ChangeIndex(object sender, System.EventArgs e)
    {
        string idstr = ((Control)sender).ID;
        alertmess.InnerText = ChangeModuleIndex(idstr);
    }
   //动态添加选项卡//
private void AddTab(string tabcode,string tabname)
{
    if(tabcode!="1")
    {
        HtmlTableCell cell0 = new HtmlTableCell();
        cell0.InnerText=(tabcode=="0")?"":"|";
        cell0.Width="11";
        cell0.Align="center";
        tabs.Rows[0].Cells.Add(cell0);
    }
	HtmlTableCell cell = new HtmlTableCell();
	MIS.WebApplication.Controls.Button btn = new MIS.WebApplication.Controls.Button();
	btn.ID = "btn_bs_"+tabcode;
	btn.Text = tabname;
	btn.Type = "tab";	
	btn.Mode =(tabid==tabcode)?"on":"off";	
    if(tabcode=="2")
    {
		btn.Url = "edit_field.aspx?mid=3&aid="+StringUtility.StringToBase64("edit")+"&tabid="+tabcode+"&pid="+id;
    }else if(tabcode=="1")
    {
		btn.Url = "edit_module.aspx?mid="+mid+"&aid="+StringUtility.StringToBase64("edit")+"&tabid="+tabcode+"&id="+id;
	}
	
	cell.Controls.Add(btn);
	tabs.Rows[0].Cells.Add(cell);
}
</script>
<script type="text/javascript">
function confirmbutton(confirmstr,buttonname)
{
    if(confirm(confirmstr))
    {
        __doPostBack(buttonname,"");    
    }
}
</script>

<body style="padding: 10px; text-align: center">
    <form id="form1" runat="server">
        <!--选项卡-->
        <div align=right>
        <table id="tabs" runat="server" border="0" cellpadding="0" cellspacing="0" ><tr></tr></table>
        </div>
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
        <td id="tb_createtable" visible="false" runat="server" style="padding-left: 5px;
            width: 115px;" nowrap>
            <X:Button ID="btn_createtable" Type="toolbar" Mode="on" Text="生成模块数据表" OnClick="Click_CreateTable"
                runat="server">
            </X:Button>
        </td>
        <td id="tb_createfile" visible="false" runat="server" style="padding-left: 5px; width: 105px;"
            nowrap>
            <X:Button ID="btn_createfile" Type="toolbar" Mode="on" Text="创建模块文件" Url="javascript:confirmbutton('确认创建文件吗？如果已对此模块文件做过开发，请点击取消。','btnCreateFile')" runat="server">
            </X:Button>
        </td>
        <!--右边固定按钮-->
        <!--#include file="toolbar.aspx"-->
        <!--右边固定按钮-->
        <!--#include file="toolbarright.aspx"-->
        <!--操作工具条-->
        <!--内容-->
        <input id="moduleposition" type="hidden" style="width: 160px" maxlength="100" runat="server">
        <table width="100%" border="0" cellpadding="3" cellspacing="1" class="table_graybgcolor">
            <tr>
                <td align="right" class="td_viewcontent_title">
                    模块名：</td>
                <td class="td_viewcontent_content">
                    <input id="modulename" type="text" class="boxbline" style="width: 95%;" runat="server"></td>
                <td align="right" class="td_viewcontent_title">
                    模块标识：</td>
                <td class="td_viewcontent_content">
                    <input id="modulebrief" type="text" class="boxbline" style="width: 95%;" runat="server"></td>
            </tr>
            <tr>
                <td align="right" class="td_viewcontent_title">
                    模块图标序号：</td>
                <td class="td_viewcontent_content">
                    <input id="moduleicon" type="text" class="boxbline" style="width: 95%;" runat="server"></td>
                <td align="right" class="td_viewcontent_title">
                    模块状态：</td>
                <td class="td_viewcontent_content">
                    <asp:DropDownList ID="modulestate" Style="width: 150px;" runat="server">
                    </asp:DropDownList>
                </td>
            </tr>
            <tr>
                <td align="right" class="td_viewcontent_title">
                    模块层级：</td>
                <td class="td_viewcontent_content">
                    <asp:DropDownList ID="modulelevel" class="boxbline" Style="width: 150px;" AutoPostBack="true"
                        OnSelectedIndexChanged="Change_Level" runat="server">
                    </asp:DropDownList>
                </td>
                <td align="right" class="td_viewcontent_title">
                    直属上级：</td>
                <td class="td_viewcontent_content">
                    <input id="moduleuppercode" type="hidden" runat="server" />
                    <asp:DropDownList ID="moduleuppercodetxt" class="boxbline" Style="width: 150px;"
                        runat="server">
                    </asp:DropDownList>
                </td>
            </tr>
            <tr>
                <td class="td_viewcontent_title" align="right">
                    相关操作：</td>
                <td class="td_viewcontent_content" colspan="3">
                    <asp:CheckBoxList ID="useactions" RepeatDirection="Horizontal" RepeatColumns="6"
                        runat="server" />
                </td>
            </tr>
            
        </table>
        <!--内容-->
        <asp:LinkButton ID="btnCreateFile" OnClick="Click_CreateFile" runat="server"></asp:LinkButton>
    </form>
</body>
</html>
