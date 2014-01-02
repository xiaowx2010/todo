<!--案件分类(分级别),编辑页-->
<%@ Page Language="c#"  Inherits="MIS.WebApplication.Controls.CheckLoginPage"%>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
<HTML>
<HEAD>	
	<X:HtmlHead ID="MIS" runat="server"></X:HtmlHead>
	<meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5">
</HEAD>
<!--#include file="func.aspx"-->

<script language="C#" runat="server">
    string p_sellist = "";
private void Page_Load(object sender, System.EventArgs e)
{
    this.Response.Cache.SetCacheability(HttpCacheability.ServerAndNoCache);
    id = GetQueryString("id", "0");
    
    if (!IsPostBack)
    {
        p_sellist = GetBindActions(id);
    }
}
    
//保存之前的数据处理
private void Click_SaveRight(object sender, System.EventArgs e)
{
    string exeinfo = "提示信息";

    if (actiondata.Value != "" && id != "0")
    {
        Hashtable ht = new Hashtable();
        string[] h_actiondata = actiondata.Value.Split('|');
      
        if (h_actiondata.Length > 1)
        {
            string[] h_actiondatacode = h_actiondata[1].Split(',');
            if (h_actiondatacode.Length > 0)
            {
                string[] h_arrtmpac;
               
                foreach (string ac in h_actiondatacode)
                {
                    h_arrtmpac = ac.Split('-');
                    if (h_arrtmpac.Length > 1)
                    {
                        if (!ht.ContainsKey(h_arrtmpac[0]))
                        {
                            ht.Add(h_arrtmpac[0].Trim(), h_arrtmpac[1].Trim());
                        }
                        else
                        {
                            ht[h_arrtmpac[0].Trim()] += ";" + h_arrtmpac[1].Trim(); 
                        }
                    }
                }
                
                if (ht.Count>0)
                {
                    StringBuilder sbsql = new StringBuilder();
                    StringBuilder sbextType = new StringBuilder("0,");
                    
                    foreach (DictionaryEntry mcode in ht)
                    {
                        sbsql.Append("if exists (select 1 from gmis_Typeright where usergroupcode=" + id + " and Typecode=" + mcode.Key.ToString().Trim(';') + ") begin update gmis_Typeright set rightcontent='" + mcode.Value.ToString() + "' where usergroupcode=" + id + " and Typecode=" + mcode.Key.ToString() + " end else begin insert into gmis_Typeright (Typecode,usergroupcode,rightcontent) values (" + mcode.Key.ToString() + "," + id + ",'" + mcode.Value.ToString().Trim(';') + "') end;");
                        sbextType.Append(mcode.Key.ToString() + ",");
                       
                    }

                    if (sbsql.Length > 0)
                    {
                        sbsql.Append("delete gmis_Typeright where usergroupcode=" + id + " and Typecode not in (" + sbextType.ToString().Trim(',') + ");");
                        exeinfo = db.UpdateTable(db.ConnStr, sbsql.ToString());
                        Response.Redirect("edit_Typeright.aspx?&id=" + id);
                        
                    } 
                }
            }
        } 
    }

    divAlert.InnerText = exeinfo;

}

private string GetBindActions(string usergroupcode)
{
    StringBuilder sbAction = new StringBuilder();
    DataTable dt = db.GetDataTable(db.ConnStr, "select Typecode,rightcontent from gmis_Typeright where usergroupcode=" + id+" and rightcontent<>''");
    
    string[] h_arrma;
    foreach (DataRow dr in dt.Rows)
    {
        h_arrma = dr["rightcontent"].ToString().Split(';');
        foreach (string ac in h_arrma)
        {
            sbAction.Append(dr["Typecode"].ToString() + "-" + ac + ","); 
        }
    }


    return sbAction.ToString().Trim(',');
}


</script>
<body style="padding:10px;text-align:center; background-color:Transparent; ">
<form id="form1" runat="server">
<input id="actiondata" type="hidden" runat="server" />
<input id="iscover" type="hidden" runat="server" />
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td nowrap style="width:400px;">
        <div id="divTypeRight" style="width:400px; height:200px;">
        <script type="text/javascript">
        WriteFlash("MultiSelect7","images/MultiSelect.swf?app=MultiSelect7&mode=3&cols=4&width=400&height=200&dataroot=edit_Typeright_data.aspx&Type=1&sellist=<%=p_sellist%>", 400, 200, "");
        function MultiSelect7_DoFSCommand(command, args)
        {
	        if(command == "resize"){
		        document.getElementById("MultiSelect7").height = Number(args);
	        }else if (command == "status"){
		        window.status = args;
	        }else if(command == "data"){
		        document.getElementById('actiondata').value = args;		
		        //window.status = args;
	        }else if(command == "ext"){
		        //alert(command+":"+args);
	        }else{
		        //alert(command+":"+args);
	        }
        }
        </script>
        </div>  
    </td>    
    <td valign="top" style="padding-top:5px;" nowrap><X:Button id="btn_saveright" type="toolbar" mode="on"  icon="tb05" text="保存权限" onclick="Click_SaveRight"  runat="server"></X:Button>
    <div id="divAlert" style="color:Red; margin-top:5px;" runat="server"></div>
    </td>
    <td width="100%"></td>
</tr>
</table>
 


<SCRIPT language=VBScript>
Function VBGetSwfVer(i)
	on error resume next
	Dim swControl, swVersion
	swVersion = 0

	set swControl = CreateObject("ShockwaveFlash.ShockwaveFlash." + CStr(i))
	if (IsObject(swControl)) then
    		swVersion = swControl.GetVariable("$version")
	end if
    	VBGetSwfVer = swVersion
End Function
</SCRIPT>


</form>

</body>
</html>
