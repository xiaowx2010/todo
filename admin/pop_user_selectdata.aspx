<%@ Page language="c#" %>
<%@ Import Namespace="System.Data" %>
<%@ Import Namespace="MIS.WebApplication.Controls" %>
<script runat=server language=C#>
		private void Page_Load(object sender, System.EventArgs e)
		{
			this.Response.Cache.SetCacheability(HttpCacheability.ServerAndNoCache);
			Response.Write("<base>");
            SqlDB db = new SqlDB();            
            db.SqlString = "select usergroupcode,usergroupname from gmis_usergroup  order by usergroupname";
            DataTable dt = db.GetDataTable();
            foreach (DataRow dr in dt.Rows)
            {
                
                    Response.Write("<i n=\"" + dr["usergroupname"].ToString() + "\" c=\"" +dr["usergroupcode"].ToString() + "\"/>");
                
               
            }
			
			Response.Write("</base>");
			
		}
</script>