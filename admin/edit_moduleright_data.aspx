<%@ Page language="c#"  Inherits="MIS.WebApplication.Controls.CheckLoginPage"%>
<%@ Import Namespace="System.Data" %>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
<script runat=server language=C#>
    DataTable dtAction;
    DataTable dtModule;
    
		private void Page_Load(object sender, System.EventArgs e)
		{
			this.Response.Cache.SetCacheability(HttpCacheability.ServerAndNoCache);
            
            Response.Write("<base n=\"²¿ÃÅ\" t=\"1\" o=\"1\">");
            Response.Write(GetModuleStr("0"));
            Response.Write("</base>");
        }

    private DataTable GetModules()
    {
        if (dtModule == null)
        {
            SqlDB db = new SqlDB();
            db.SqlString = "select modulecode,modulename,moduleuppercode,useactions,(case when exists (select 1 from gmis_module m2 where m2.moduleuppercode=m1.modulecode) then 1 else 0 end) as hassubmodule from gmis_module m1 where modulestate=0 order by moduleindex";
            dtModule = db.GetDataTable(); 
        }
        return dtModule;
    }

    private string GetModuleStr(string moduleuppercode)
    {
        StringBuilder sb = new StringBuilder();

        DataRow[] drs = GetModules().Select("moduleuppercode=" + moduleuppercode);
        string c;
        foreach (DataRow dr in drs)
        {
            sb.Append("<d n=\"" + dr["modulename"].ToString() + "\" c=\"" + dr["modulecode"].ToString() + "\">");
            sb.Append(BindActions(dr["modulecode"].ToString(),dr["useactions"].ToString()));

            if (dr["hassubmodule"].ToString() == "1")
            {
                sb.Append(GetModuleStr(dr["modulecode"].ToString()));
            }
            
            sb.Append("</d>");
        } 
        
        return sb.ToString();
    }

    private DataTable GetActions()
    {
        if (dtAction == null)
        {
            SqlDB db = new SqlDB();
            db.SqlString = "select actioncode,actionname from gmis_action";
            dtAction = db.GetDataTable(); 
        }
        return dtAction;
    }

    
    private string BindActions(string modulecode,string actionstr)
    {
        StringBuilder sb = new StringBuilder();
        string stractions = actionstr.Replace(";", ",");
        if (stractions.Length > 0)
        {
            DataRow[] drs = GetActions().Select("actioncode in (" + stractions + ")"," actioncode desc");
            foreach (DataRow dr in drs)
            {
                sb.Append("<i n=\"" + dr["actionname"].ToString() + "\" c=\"" + modulecode + "-" + dr["actioncode"].ToString() + "\" />");
            }
        }
        
        return sb.ToString();
    }

    
</script>