<%@ Page Language="c#" Inherits="MIS.WebApplication.Controls.Page"%>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
<!--#include file="func.aspx"-->
<script runat="server" language="C#">
	private void Page_Load(object sender, System.EventArgs e)
	{
		
	    string uname = GetQueryString("uname", "");
	    string upass = GetQueryString("upass", "");
	    
	 
        if(!IsPostBack)
        {
			if(uname!="" && upass!="")
			{
				string LoginInfo = CheckLogin1(uname,upass);
				if(LoginInfo.Length > 1 && (LoginInfo[0] == '0' || LoginInfo[0] == '1' || LoginInfo[0] == '2'))
				{
                    ClearSESSION();
                    SetSESSION("uname", uname);
                    SetSESSION("SM", LoginInfo[0].ToString());
                    SetSESSION("uid", LoginInfo.Substring(1));
                    //用户登录                    
                    mid = GetFirstModule();
                    Response.Write("mid"+mid);
                   if(mid!="")
                   {
                        string h_mbrief = "";
                        h_mbrief = GetModuleBrief(mid);
                        aid = "list";                      
                        Response.Redirect("default.aspx");
                    }
					
				}
			}
			Response.Redirect("login.aspx");
        }
	}
	private string CheckLogin1(string uidStr, string pwdStr)
	{   
		string ReString = ""; 
		if (uidStr.ToLower() == "gmis")
		{
			db.SqlString = "SELECT userCode FROM gmis_user where username='" + uidStr.ToLower() + "'";
			if (db.GetDataTable().Rows.Count == 0)
			{
				if (pwdStr == ConfigurationSettings.AppSettings["PRODUCT_NAME"].ToString())
				{
					ReString = "20";
					                 
				}
				else
					ReString = "用户名或密码出错！";
			}
		}
		else
		{
			try
			{
				db.SqlString = "SELECT usercode,username,userstate,userrole FROM gmis_user WHERE username='" + uidStr.Replace("\'", "") + "' ";           
				DataTable dt = db.GetDataTable();           
				if (dt.Rows.Count == 0)
				{
					return "用户名或密码出错！";
				}
				else
				{
					DataRow dr = dt.Rows[0];
					if (dr["userstate"].ToString() == "禁用")
					{
						ReString = "该用户已被禁用！";
					}
					else
					{
						ReString = "0";
						if (dr["userrole"].ToString() == "系统管理员")
							ReString = "1";
						ReString += dr["usercode"].ToString();
					}
				}
			}
			catch (Exception e)
			{
				ReString = e.Message;//"出现未知的错误，用户登录失败！";
			}
		}
		return ReString;
	}
</script>
