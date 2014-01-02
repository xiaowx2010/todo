<%@ Import Namespace="System.Data" %>
<%@ Import Namespace="System.IO" %>
<%@ Import Namespace="System.Web.UI.WebControls" %>

<script runat="server" language="C#">
    //提升下降栏目位置
    private string ChangeModuleIndex(string dir)
    {
        int h_opid = 0;
        StringBuilder sb = new StringBuilder();
        sb.Append("declare @thisindex int;declare @thatindex int;declare @thatid int;declare @upid int;declare @thisindexstr varchar(100);declare @thatindexstr varchar(100);");
        sb.Append("set @thisindex=0;set @thatindex=0;set @thatid=0;set @upid=0;set @thisindexstr='';set @thatindexstr=''; ");
        sb.Append("select @thisindex=moduleposition,@upid=moduleuppercode,@thisindexstr=moduleindex from gmis_module where modulecode=" + id + " ;");
        if (dir.ToLower() == "btn_up")
        {
            sb.Append("select @thatindex=max(moduleposition) from gmis_module where moduleposition<@thisindex and moduleuppercode=@upid ;");
        }
        else
        {
            sb.Append("select @thatindex=min(moduleposition) from gmis_module where moduleposition>@thisindex and moduleuppercode=@upid ;");

        }
        sb.Append("if @thatindex>0 begin select @thatid=modulecode,@thatindexstr=moduleindex from gmis_module where moduleposition=@thatindex and moduleuppercode=@upid ;");
        sb.Append("update gmis_module set moduleposition=@thisindex where modulecode=@thatid ;update gmis_module set moduleposition=@thatindex where modulecode=" + id + " ;");
        sb.Append("update gmis_module set moduleindex=replace(moduleindex,@thisindexstr,'temp') where moduleindex like @thisindexstr+'%' ;");
        sb.Append("update gmis_module set moduleindex=replace(moduleindex,@thatindexstr,@thisindexstr) where moduleindex like @thatindexstr+'%' ;");
        sb.Append("update gmis_module set moduleindex=replace(moduleindex,'temp',@thatindexstr) where moduleindex like 'temp%' ;");
        sb.Append(" end");
        db.SqlString = sb.ToString();
        //Response.Write(sb.ToString());
        string exeinfo = db.TransUpdate();
        return exeinfo;
    }
    //生成列表HTML
    private string CreateListHTML(string mid)
    {
        DataRow[] fdrs = GetFields(mid).Select(" oneditlist=1");
        string h_tempheadhtml = "", h_temp1html = "", h_temp2html = "", h_width = ""; ;

        foreach (DataRow dr in fdrs)
        {
            h_width = (dr["editlistcolumnwidth"].ToString() == "0") ? "" : " width=" + dr["editlistcolumnwidth"].ToString() + "%";
            h_tempheadhtml += "\t\t\t<td " + h_width + ">" + dr["caption"].ToString() + "</td>\n";
            h_temp1html += "\t\t\t<td>*</td>\n";
            h_temp2html += "\t\t\t<td>&nbsp;</td>\n";
        }
        h_tempheadhtml += "\t\t\t<td width=\"5%\">操作</td>\n";
        h_temp1html += "\t\t\t<td align=\"center\">*</td>\n";
        h_temp2html += "\t\t\t<td>&nbsp;</td>\n";
        StringBuilder sb = new StringBuilder();
        sb.Append("<X:ListTable ID=\"list\" Rows=\"20\" IsProPage=true runat=\"server\">\n");
        sb.Append("\t<X:Template id=\"listtemphead\" type=\"head\" runat=\"server\">\n");
        sb.Append("\t\t<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"righttableline\">\n");
        sb.Append("\t\t<tr class=\"tr_listtitle\" align=\"left\">\n");
        sb.Append(h_tempheadhtml);
        sb.Append("\t\t</tr>\n");
        sb.Append("\t</X:Template>\n");
        sb.Append("\t<X:Template id=\"listtemp1\" runat=\"server\">\n");
        sb.Append("\t\t<tr class=\"tr_listcontent\">\n");
        sb.Append(h_temp1html);
        sb.Append("\t\t</tr>\n");
        sb.Append("\t</X:Template>\n");
        sb.Append("\t<X:Template id=\"listtemp2\" runat=\"server\">\n");
        sb.Append("\t\t<tr class=\"tr_listcontent\">\n");
        sb.Append(h_temp2html);
        sb.Append("\t\t</tr>\n");
        sb.Append("\t</X:Template>\n");
        sb.Append("\t<X:NavStyle5 ID=\"NavStyle\" PageUrl=\"list_" + mid + ".aspx\" runat=\"server\"></X:NavStyle5>\n");
        sb.Append("\t</X:ListTable> \n");
        return sb.ToString();
    }
    //生成编辑HTML
    private string CreateEditHTML(string mid,string aid)
    {
        DataRow[] fdrs = GetFields(mid).Select(" OnEdit=1");
        string h_editmust = "", h_temphtml = "";
        bool isfullline = true, newline = true;
        foreach (DataRow dr in fdrs)
        {
            isfullline = Convert.ToBoolean(dr["Fullline"]);
            h_editmust = "";
            if (Convert.ToBoolean(dr["OneditMust"]) && aid=="edit") h_editmust = "<font color=\"#ff6600\">*</font>";
            if (newline)
            {
                if (isfullline)
                {
                    h_temphtml += "\t<tr>\n\t\t<td width=\"100\" align=\"right\"  class=\"td_viewcontent_title\">" + h_editmust + dr["Caption"].ToString() + "</td>\n\t\t<td colspan=\"3\" class=\"td_viewcontent_content\">";
                }
                else
                {
                    h_temphtml += "\t<tr>\n\t\t<td width=\"100\" align=\"right\"  class=\"td_viewcontent_title\">" + h_editmust + dr["Caption"].ToString() + "</td>\n\t\t<td class=\"td_viewcontent_content\" style=\"width:35%\">";
                }
            }
            else
            {
                if (isfullline)
                {
                    h_temphtml += "\n\t\t<td width=\"100\" align=\"right\"  class=\"td_viewcontent_title\">&nbsp;</td>\n\t\t<td class=\"td_viewcontent_content\" style=\"width:35%\">&nbsp;</td>\n\t</tr>\n\t<tr>" +
                                "\n\t\t<td width=\"100\" align=\"right\"  class=\"td_viewcontent_title\">" + h_editmust + dr["Caption"].ToString() + "</td>\n\t\t<td colspan=\"3\" class=\"td_viewcontent_content\" >";
                }
                else
                {
                    h_temphtml += "\n\t\t<td width=\"100\" align=\"right\"  class=\"td_viewcontent_title\">" + h_editmust + dr["Caption"].ToString() + "</td>\n\t\t<td class=\"td_viewcontent_content\" style=\"width:35%\">";
                }
            }
            if (aid == "edit")
            {
                h_temphtml += GetFieldControls(dr, isfullline);
            }
            else
            {
                h_temphtml += "*";
            }
            if (newline && !isfullline)
            {
                newline = false;
                h_temphtml += "</td>\n\t\t";
            }
            else
            {
                newline = true;
                h_temphtml += "</td>\n\t\t</tr>\n\t";
            }
        }
        if (!newline)
        {
            h_temphtml += "\n\t\t<td width=\"100\" align=\"right\"  class=\"td_viewcontent_title\">&nbsp;</td>\n\t\t<td class=\"td_viewcontent_content\" style=\"width:35%\">&nbsp;</td>\n\t\t</tr>\n\t";
        }
        
        StringBuilder sb = new StringBuilder();
        if (aid == "view")
        {
            sb.Append("<X:Content id=\"view\" runat=\"server\">\n<X:Template id=\"Template1\" runat=\"server\">\n");
        }
        sb.Append("<table width=\"100%\"  border=\"0\" cellpadding=\"3\" cellspacing=\"1\" class=\"table_graybgcolor\">\n");
        sb.Append(h_temphtml);
        sb.Append("</table>\n");
        if (aid == "view")
        {
            sb.Append("</X:Template>\n</X:Content>\n");
        }
        return sb.ToString();
    }
    //根据字段生成控件
    private string GetFieldControls(DataRow dr, bool isfullline)
    {

        string ftype = dr["fieldtypecode"].ToString();
        string h_controlshtml = "";
        if (ftype == "3")
        {//格式文本
            h_controlshtml = "<X:Editor id=\"" + dr["fieldname"].ToString() + "\" height=\"300\" width=\"100%\" BasePath=\"Common/Editor/\" runat=\"server\" />";
        }
        else if (ftype == "4")
        {//附件
            h_controlshtml = "<X:Editor id=\"" + dr["fieldname"].ToString() + "\" height=\"100\" toolbarset=\"UpLoad\" width=\"100%\" BasePath=\"Common/Editor/\" runat=\"server\" />";
        }
        else if (ftype == "9")
        {//简单格式文本

            h_controlshtml = "<X:Editor id=\"" + dr["fieldname"].ToString() + "\" height=\"100\" toolbarset=\"Basic\" width=\"100%\" BasePath=\"Common/Editor/\" runat=\"server\" />";
        }
        else if (ftype == "5" || ftype == "6")
        {
            //多选一
            if (isfullline)
                h_controlshtml = "<asp:DropDownList id=\"" + dr["fieldname"].ToString() + "\" style=\"width:98%;\" runat=\"server\" />";
            else
                h_controlshtml = "<asp:DropDownList id=\"" + dr["fieldname"].ToString() + "\" style=\"width:95%;\" runat=\"server\" />";


        }
        else if (ftype == "7")
        {//日期

            h_controlshtml = "<input id=\"" + dr["fieldname"].ToString() + "\" class=\"boxbline\" style=\"width:160px;\" type=\"text\" runat=\"server\" readonly>&nbsp;&nbsp;<X:GetDate id=\"get_" + dr["fieldname"].ToString() + "\" return=\"" + dr["fieldname"].ToString() + "\" runat=\"server\" />";
        }
        else if (ftype == "8")
        {//简单文本
            if (isfullline)
                h_controlshtml = "<TextArea id=\"" + dr["fieldname"].ToString() + "\" class=\"boxbline\" style=\"width:98%;height:50px;\" runat=\"server\" ></TextArea>";
            else
                h_controlshtml = "<TextArea id=\"" + dr["fieldname"].ToString() + "\" class=\"boxbline\" style=\"width:95%;height:50px;\" runat=\"server\" ></TextArea>";
        }
        else
        {//短语

            if (isfullline)
                h_controlshtml = "<input id=\"" + dr["fieldname"].ToString() + "\" class=\"boxbline\" style=\"width:98%;\" maxlength=\"" + dr["DataLength"].ToString() + "\" runat=\"server\" >";
            else
                h_controlshtml = "<input id=\"" + dr["fieldname"].ToString() + "\" class=\"boxbline\" style=\"width:95%;\"  maxlength=\"" + dr["DataLength"].ToString() + "\" runat=\"server\" >";



        }
        return h_controlshtml;

    }
</script>

