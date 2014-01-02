<%@ Page language="c#" Inherits="MIS.WebApplication.Controls.Page"%>
<%@ Import Namespace="System.Data" %>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
<!--#include file="func.aspx"-->
<script runat=server language=C#>
		private void Page_Load(object sender, System.EventArgs e)
		{
			this.Response.Cache.SetCacheability(HttpCacheability.ServerAndNoCache);
			
			Response.Write("<base>");
			db.SqlString = "select modulecode,modulename,moduleindex from gmis_module where moduleuppercode=0 and modulestate=0 order by moduleindex";
           
			DataTable dt=db.GetDataTable();		
			string _t="1",h_link="";
			foreach(DataRow dr in dt.Rows)
			{   
			    
				if(IsSystemDeveloper() || IsSystemManager() || Isshowthis(dr["moduleindex"].ToString()))
				{			
					_t=((";1;").IndexOf(";"+dr["modulecode"].ToString()+";")>-1)?"1":"0";
					switch(dr["modulecode"].ToString())
					{
					    default:
					        h_link="getbtns.aspx";
					        break;
					}				
					Response.Write("<i n=\""+dr["modulename"].ToString()+"\" c=\""+dr["modulecode"].ToString()+"\" d=\""+h_link+"?rid="+dr["modulecode"].ToString()+"\" t=\""+_t+"\"/>");
				}
				
			}			
			
			Response.Write("</base>");
		}
</script>