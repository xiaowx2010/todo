<%@ Page Language="c#" Inherits="MIS.WebApplication.Controls.Page"%>
<%@ Import Namespace="System.IO" %>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
<!--#include file="func.aspx"-->
<script runat="server" language="C#">
	private void Page_Load(object sender, System.EventArgs e)
	{	
	    string h_mbrief="",h_mtable="";
	    mid = GetQueryString("mid", "0");//模块编号
        aid = GetQueryString("aid", "");//操作符(add,edit,delete等等)
        id = GetQueryString("id", "0");
        pid=GetQueryString("pid", "0");
        tid=GetQueryString("tid", "0");//图层分类编号
        fid=GetQueryString("fid", "0");//文档目录编号
        cid=GetQueryString("cid", "0");//信息栏目编号
        navindex = GetQueryString("navindex", "0");//翻页页码
        tabid=GetQueryString("tabid", "0");//选项卡编号
       
	    if(aid=="")
	    {
	        ClearSESSION();//清空除登录信息以外的SESSION	      
	        aid="list";
	        
	    }	    
        
	    if(mid!="0")
	    {
	        h_mbrief=GetModuleBrief(mid);
	        try
	        {
	            Convert.ToInt32(h_mbrief);
	            h_mtable="gmis_Mo_"+h_mbrief;
	        }
	        catch
	        {
	            h_mtable="gmis_"+h_mbrief;
	        }
	    }	    
	    if(aid=="delete")//删除
	    {
	        string h_sqlstr="Delete from  "+h_mtable+" where "+GetPK(h_mtable)+"="+id;
	            
	        if(GetModuleName(mid) == "字段管理" && pid!="0")
	        {
    	        h_sqlstr="Update gmis_field set fieldindex=(fieldindex-1) where modulecode=" + pid + " and fieldindex>(select fieldindex from gmis_field where fieldcode="+id+") ;"+h_sqlstr;    	       
                string tablename_t="";
                string h_mbrief_t=GetModuleBrief(pid);               
                try
                {
                    Convert.ToInt32(h_mbrief_t);
                    tablename_t="gmis_Mo_"+h_mbrief_t;
                }
                catch
                {
                    tablename_t="gmis_"+h_mbrief_t;
                }
                db.SqlString="select fieldname from gmis_field where fieldcode="+id;               
                string fieldname="";
                if(db.GetDataTable().Rows.Count>0)
                {
                    fieldname=db.GetDataTable().Rows[0]["fieldname"].ToString();
                }
                
                if (tablename_t != "" && CheckSysObject(tablename_t) && CheckSysColumn(tablename_t, fieldname))//表存在，且字段存在于表
                {                        
                    string exesql = "";
                    db.SqlString= "select b.name from syscolumns a,sysobjects b where a.id=object_id('" + tablename_t + "') and b.id=a.cdefault and a.name='" + fieldname + "' and b.name like 'DF%'";
                    DataTable dt = db.GetDataTable();
                    if (dt.Rows.Count > 0)
                    {
                        h_sqlstr += ";ALTER TABLE " + tablename_t + " DROP CONSTRAINT " + dt.Rows[0]["name"].ToString() + "";
                    }                    
                     h_sqlstr+=";ALTER TABLE " + tablename_t + " DROP COLUMN " + fieldname + "";
                    
                }
	        }
	        
	        SetSESSION("sql",h_sqlstr);
	        
	        Response.Redirect("execommand.aspx?aid=" + StringUtility.StringToBase64(aid) + "&mid=" + mid + "&id="+id+"&cid="+cid+"&tabid="+tabid+"&fid="+fid+"&pid="+pid);
	    }	    
	    else
	    {	 
	        aid=(aid=="add")?"edit":aid;
			string h_filedir=Server.MapPath("\\");
			if(System.Configuration.ConfigurationSettings.AppSettings["FILE_DIRECTORY"]!=null && System.Configuration.ConfigurationSettings.AppSettings["FILE_DIRECTORY"].ToString()!="")
			{
				h_filedir+=System.Configuration.ConfigurationSettings.AppSettings["FILE_DIRECTORY"].TrimStart('\\');
			}
			else
			{
				h_filedir+="\\Admin\\";
			}
						
			
			if(File.Exists(h_filedir+aid+"_"+h_mbrief+".aspx"))
			{
				
					Response.Redirect(aid+"_"+h_mbrief+".aspx?aid="+StringUtility.StringToBase64(aid)+"&mid="+mid+"&id="+id+"&navindex="+navindex+"&cid="+cid+"&fid="+fid+"&pid="+pid);
			}
			else
			{
			    mid=GetFirstRightModule(mid);//获取直属下级第一个有权限的模块编号
			    
				h_mbrief=GetModuleBrief(mid);	
						
				if(File.Exists(h_filedir+aid+"_"+h_mbrief+".aspx"))
				{   
				    
				    Response.Redirect(aid+"_"+h_mbrief+".aspx?aid="+StringUtility.StringToBase64(aid)+"&mid="+mid+"&id="+id+"&navindex="+navindex+"&cid="+cid+"&fid="+fid+"&pid="+pid);
				}
			}
			
	    }
	}
</script>
<div style="color:red;font-size:12px;margin-top:50px;" align=center>正在建设中…… </div>
