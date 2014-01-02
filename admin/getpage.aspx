<%@ Page Language="c#" Inherits="MIS.WebApplication.Controls.Page"%>
<%@ Import Namespace="System.IO" %>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
<!--#include file="func.aspx"-->
<script runat="server" language="C#">
	private void Page_Load(object sender, System.EventArgs e)
	{	
	    string h_mbrief="",h_mtable="";
	    mid = GetQueryString("mid", "0");//ģ����
        aid = GetQueryString("aid", "");//������(add,edit,delete�ȵ�)
        id = GetQueryString("id", "0");
        pid=GetQueryString("pid", "0");
        tid=GetQueryString("tid", "0");//ͼ�������
        fid=GetQueryString("fid", "0");//�ĵ�Ŀ¼���
        cid=GetQueryString("cid", "0");//��Ϣ��Ŀ���
        navindex = GetQueryString("navindex", "0");//��ҳҳ��
        tabid=GetQueryString("tabid", "0");//ѡ����
       
	    if(aid=="")
	    {
	        ClearSESSION();//��ճ���¼��Ϣ�����SESSION	      
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
	    if(aid=="delete")//ɾ��
	    {
	        string h_sqlstr="Delete from  "+h_mtable+" where "+GetPK(h_mtable)+"="+id;
	            
	        if(GetModuleName(mid) == "�ֶι���" && pid!="0")
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
                
                if (tablename_t != "" && CheckSysObject(tablename_t) && CheckSysColumn(tablename_t, fieldname))//����ڣ����ֶδ����ڱ�
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
			    mid=GetFirstRightModule(mid);//��ȡֱ���¼���һ����Ȩ�޵�ģ����
			    
				h_mbrief=GetModuleBrief(mid);	
						
				if(File.Exists(h_filedir+aid+"_"+h_mbrief+".aspx"))
				{   
				    
				    Response.Redirect(aid+"_"+h_mbrief+".aspx?aid="+StringUtility.StringToBase64(aid)+"&mid="+mid+"&id="+id+"&navindex="+navindex+"&cid="+cid+"&fid="+fid+"&pid="+pid);
				}
			}
			
	    }
	}
</script>
<div style="color:red;font-size:12px;margin-top:50px;" align=center>���ڽ����С��� </div>
