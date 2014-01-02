<%@ Page language="c#"  Inherits="MIS.WebApplication.Controls.CheckLoginPage"%>
<%@ Import Namespace="System.Data" %>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
<script runat=server language=C#>
    DataTable dtAction;
    DataTable dtArea;
    
		private void Page_Load(object sender, System.EventArgs e)
		{
			this.Response.Cache.SetCacheability(HttpCacheability.ServerAndNoCache);
            
            Response.Write("<base n=\"区域\" t=\"1\" o=\"1\">");
            Response.Write(GetAreaStr("0"));
            Response.Write("</base>");
        }

    private DataTable GetAreas()
    {
        if (dtArea == null)
        {
            SqlDB db = new SqlDB();
            db.SqlString = "select Areacode,Areaname,Areauppercode,'1' as useactions,(case when exists (select 1 from gmis_Area m2 where m2.Areauppercode=m1.Areacode) then 1 else 0 end) as hassubArea from gmis_Area m1 where Areastate='启用' order by Areaindex";
            dtArea = db.GetDataTable(); 
        }
        return dtArea;
    }

    private string GetAreaStr(string Areauppercode)
    {
        StringBuilder sb = new StringBuilder();

        DataRow[] drs = GetAreas().Select("Areauppercode=" + Areauppercode);
        string c;
        foreach (DataRow dr in drs)
        {
            sb.Append("<d n=\"" + dr["Areaname"].ToString() + "\" c=\"" + dr["Areacode"].ToString() + "\">");
            sb.Append(BindActions(dr["Areacode"].ToString(),dr["useactions"].ToString()));

            if (dr["hassubArea"].ToString() == "1")
            {
                sb.Append(GetAreaStr(dr["Areacode"].ToString()));
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
            db.SqlString = "select 1 as actioncode,'区域权限' as actionname";
            dtAction = db.GetDataTable(); 
        }
        return dtAction;
    }

    
    private string BindActions(string Areacode,string actionstr)
    {
        StringBuilder sb = new StringBuilder();
        string stractions = actionstr.Replace(";", ",");
        if (stractions.Length > 0)
        {
            DataRow[] drs = GetActions().Select("actioncode in (" + stractions + ")"," actioncode desc");
            foreach (DataRow dr in drs)
            {
                sb.Append("<i n=\"" + dr["actionname"].ToString() + "\" c=\"" + Areacode + "-" + dr["actioncode"].ToString() + "\" />");
            }
        }
        
        return sb.ToString();
    }

    
</script>