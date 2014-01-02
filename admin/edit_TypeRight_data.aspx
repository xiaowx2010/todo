<%@ Page language="c#"  Inherits="MIS.WebApplication.Controls.CheckLoginPage"%>
<%@ Import Namespace="System.Data" %>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
<script runat=server language=C#>
    DataTable dtAction;
    DataTable dtType;
    
		private void Page_Load(object sender, System.EventArgs e)
		{
			this.Response.Cache.SetCacheability(HttpCacheability.ServerAndNoCache);
            
            Response.Write("<base n=\"区域\" t=\"1\" o=\"1\">");
            Response.Write(GetTypeStr("0"));
            Response.Write("</base>");
        }

    private DataTable GetTypes()
    {
        if (dtType == null)
        {
            SqlDB db = new SqlDB();
            db.SqlString = "select Typecode,Typename,Typeuppercode,'1' as useactions,(case when exists (select 1 from gmis_Type m2 where m2.Typeuppercode=m1.Typecode) then 1 else 0 end) as hassubType from gmis_Type m1 where Typestate = '启用' order by Typeindex";
            dtType = db.GetDataTable(); 
        }
        return dtType;
    }

    private string GetTypeStr(string Typeuppercode)
    {
        StringBuilder sb = new StringBuilder();

        DataRow[] drs = GetTypes().Select("Typeuppercode=" + Typeuppercode);
        string c;
        foreach (DataRow dr in drs)
        {
            sb.Append("<d n=\"" + dr["Typename"].ToString() + "\" c=\"" + dr["Typecode"].ToString() + "\">");
            sb.Append(BindActions(dr["Typecode"].ToString(),dr["useactions"].ToString()));

            if (dr["hassubType"].ToString() == "1")
            {
                sb.Append(GetTypeStr(dr["Typecode"].ToString()));
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
            db.SqlString = "select 1 as actioncode,'图层权限' as actionname";
            dtAction = db.GetDataTable(); 
        }
        return dtAction;
    }

    
    private string BindActions(string Typecode,string actionstr)
    {
        StringBuilder sb = new StringBuilder();
        string stractions = actionstr.Replace(";", ",");
        if (stractions.Length > 0)
        {
            DataRow[] drs = GetActions().Select("actioncode in (" + stractions + ")"," actioncode desc");
            foreach (DataRow dr in drs)
            {
                sb.Append("<i n=\"" + dr["actionname"].ToString() + "\" c=\"" + Typecode + "-" + dr["actioncode"].ToString() + "\" />");
            }
        }
        
        return sb.ToString();
    }

    
</script>