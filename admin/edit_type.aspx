<%@ Page Language="c#"  Inherits="MIS.WebApplication.Controls.CheckLoginPage"%>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
<HTML>
<HEAD>	
	<X:HtmlHead ID="MIS" runat="server"></X:HtmlHead>
	<meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5">
</HEAD>
<!--#include file="func.aspx"-->
	<script runat="server" language="C#">
        int _rc;
        string iconid="";
        string _astr="该层可能有数据或二级层，确认要删除吗！",tbname,_typekind;
        string aim = "/SpeedMap/Shell.aspx?com=IconSystem&mode=view&id=";
        string aimlist = "/SpeedMap/Shell.aspx?com=IconSystem&mode=list";	
        private void Page_Load(object sender, System.EventArgs e)
        {
            size = "16";//默认图标大小
            SetSESSION("speedmap", "0");
            SetToolBar();//设置工具条,同时获取固定URL参数  
          

            dtable = "gmis_type";
			filter = " typecode="+id;
			flds = new string[]{"typename","typekind","typebrief","typelevel","typeuppercode","typeindex","typeicon","typestate","typedesc"};
			mflds = new string[]{"数据层名|typename","简称|typebrief"};
			types = "000110100";
           
            SqlDB db = new SqlDB();
            DataTable dt;
            if (!IsPostBack)
            {//先绑定列表	
                if (id != "0")
                {
                    db.SqlString = "select typelevel,typeindex,typeuppercode,typekind from gmis_type where typecode="+id;		
					dt=db.GetDataTable();
					if(dt.Rows.Count>0 && dt.Rows[0]["typelevel"] != DBNull.Value){	
						_typekind=dt.Rows[0]["typekind"].ToString();//图层类别,1为点位，2为图形，3为线路				
						BindListControl(typeuppercode,"typecode","typename","select typecode,typename from gmis_type where typelevel="+Convert.ToString(Convert.ToInt32(dt.Rows[0]["typelevel"])-1));					
						db.SqlString = "select max(typelevel)+1 as typelevel from gmis_type where typeindex not like '"+dt.Rows[0]["typeindex"].ToString()+"%'";
						dt=db.GetDataTable();
						if(dt.Rows.Count>0 && dt.Rows[0]["typelevel"] != DBNull.Value)
							BindNumber(typelevel,0,Convert.ToInt32(dt.Rows[0]["typelevel"]));
						else
							BindNumber(typelevel,0,0);	
				  									
					}	
					
                    //绑定所有值
                    MIS.BindData(dtable, filter, flds);
                    db.SqlString="select Iconsize from gmis_Icon where IconCode="+typeicon.Value;			
					dt=db.GetDataTable();			
					if (dt.Rows.Count>0){						
					   iconsize.Value=dt.Rows[0]["Iconsize"].ToString();
					   size=iconsize.Value;
					}				
                    SetSESSION("_typekind", "");
                }
                else
                {
                    tb_7.Visible=false;		
					db.SqlString = "select max(typelevel)+1 from gmis_type where typecode<>"+id;
					dt=db.GetDataTable();
					if(dt.Rows.Count>0 && dt.Rows[0][0] != DBNull.Value)
						BindNumber(typelevel,0,Convert.ToInt32(dt.Rows[0][0]));
					else
						BindNumber(typelevel,0,0);	
                }

            }
            if (typeicon.Value != "") iconid = typeicon.Value;
            if (_typekind == "数据点")
            {
                tr_inputicon.Visible = true;
            }
            //图标	
            aim += iconid + "&size=" + size;
            aimlist += "&size=" + size;
        }
        //选择层级
        private void Level_Change(object sender, System.EventArgs e)
        {
            if(typelevel.SelectedItem != null){
				if(id != "0")
					BindListControl(typeuppercode,"typecode","typename","select typecode,typename from gmis_type where typeindex not like '"+typeindex.Value+"%' And typelevel="+Convert.ToString(Convert.ToInt32(typelevel.SelectedItem.Value)-1));
				else
					BindListControl(typeuppercode,"typecode","typename","select typecode,typename from gmis_type where typelevel="+Convert.ToString(Convert.ToInt32(typelevel.SelectedItem.Value)-1));
			}
			 
        }
        //绑定层级
        private void BindNumber(ListControl _lc, int start, int max)
        {
            for (int i = 0; i <= max; i++)
            {
                ListItem _li = new ListItem((i + start).ToString());
                _lc.Items.Add(_li);
            }
        }
        public override void BeforeSave()
        {
            typename.Value = typename.Value.Trim().Replace("<", "").Replace(">", "").Replace("'", "");
            typebrief.Value = typebrief.Value.Trim().Replace("<", "").Replace(">", "").Replace("'", "");
            if (typebrief.Value.Trim().Length == 0)
            {
                typebrief.Value = typename.Value;
            }
            if (coverico.Checked)
            {
                SqlDB dbIco = new SqlDB();
                dbIco.SqlString = "select typeindex from gmis_type where typecode=" + id;
                DataTable dtIco = dbIco.GetDataTable();
                if (dtIco.Rows.Count > 0)
                {
                    SetSESSION("covericosql", " UPDATE gmis_type set typeicon = " + typeicon.Value + " where typeindex like '%" + dtIco.Rows[0]["typeindex"].ToString() + "%'");
                }
            }
            else
            {
                SetSESSION("covericosql", "");
            }
            /*
            if(id == "0")
	        {
                if (typeuppercode.SelectedItem != null && typeuppercode.SelectedItem.Value != "0")
                {
                    SqlDB DBsql=new SqlDB();
                    DBsql.SqlString = "select count(*)+1 from gmis_type where typeuppercode=" + typeuppercode.SelectedItem.Value;
                    DataTable DBdt = DBsql.GetDataTable();
                    string P_TypeUpperCodeIndex = GetFieldData("select typeindex from gmis_type where typecode=" + typeuppercode.SelectedItem.Value);
                    if (DBdt.Rows.Count > 0)
                    {
                        typeindex.Value = P_TypeUpperCodeIndex + DBdt.Rows[0][0].ToString().PadLeft(3,'0');
                    }
                    
                }
                else
                {
                    SqlDB DBsql=new SqlDB();
                    DBsql.SqlString = "select count(typelevel) from gmis_type where typelevel=0";
                        typeindex.Value = DBsql.GetDataTable().Rows[0][0].ToString()+".";
                }
	        }
	        */
        }
        //上升位置；
        private void Up_Click(object sender, System.Web.UI.ImageClickEventArgs e)
        {
            SqlDB db = new SqlDB();
            db.SqlString = "select typeuppercode from gmis_type where typecode=" + id;
            string rid = db.GetDataTable().Rows[0]["typeuppercode"].ToString();
            int me = 0;
            if (rid == "") rid = "0";
            db.SqlString = "select typecode,typeposition from gmis_type where typeuppercode=" + rid + " and '" + GetSESSION("listtype") + "' like '%;'+cast(typecode as varchar(10))+';%' order by typeindex desc";
            DataTable dt = db.GetDataTable();
            DataRow[] drs = dt.Select("");
            for (int i = 1; i <= drs.Length; i++)
            {
                drs[i - 1]["typeposition"] = Convert.ToString(drs.Length - i + 1);
                if (drs[i - 1]["typecode"].ToString() == id) { me = i - 1; }
            }
            if ((drs.Length - me) > 1)
            {
                drs[me]["typeposition"] = Convert.ToString(drs.Length - me - 1);
                drs[me + 1]["typeposition"] = Convert.ToString(drs.Length - me);
            }
            StringBuilder sstr = new StringBuilder();
            foreach (DataRow dr in drs)
            {
                sstr.Append(" update gmis_type set typeposition=" + dr["typeposition"].ToString() + " where typecode=" + dr["typecode"].ToString() + "; ");
            }

            if (sstr.Length > 0)
            {
                db.SqlString = "BEGIN " + sstr.ToString() + "END";
                db.TransUpdate();
                
                if(id == "0"){
                    SetSESSION("sql",MIS.GetInsertSql(dtable,flds,flds,types));
                }else{
                    SetSESSION("sql", MIS.GetUpdateSql(dtable,filter,flds,types));
                }			
                Response.Redirect("execommand.aspx?mid="+mid+"&pid="+pid+"&id="+id);
            }


        }
	</script>
<body style="padding:10px;text-align:center">
<form id="form1" runat="server">
<!--选项卡-->
<!--选项卡-->
<!--操作工具条-->
    <!--#include file="toolbarleft.aspx"--> 
			<td style="padding-left:5px;">
				<table  border="0" cellspacing="0" cellpadding="0" ID="Table1" align="left">
				<tr>
					<td >
						<input id="tb_7" type="image" src="images/up.gif" border="0" title="提升" onserverclick="Up_Click" style="padding-left:5px" runat="server">
					</td>
				</tr>
				</table>
			</td>
			<!--右边固定按钮-->
			<!--#include file="toolbar.aspx"-->
			<!--右边固定按钮-->
	   <!--#include file="toolbarright.aspx"--> 
<!--操作工具条-->
<!--内容-->
<table width="100%"  border="0" cellpadding="3" cellspacing="1" class="table_graybgcolor">
    <tr> 
        <td align="right"  class="td_viewcontent_title">数据层名：</td>
        <td class="td_viewcontent_content" colspan="3">
        <input id="typename" type="text" style="width:95%" maxlength="200" runat="server"></td>
    </tr>
    <tr> 
        <td align="right"  class="td_viewcontent_title">类别：</td>
        <td class="td_viewcontent_content"><select id="typekind" style="width:250px" runat="server">
            <option>数据点</option>
            <option>图形</option>
            <option>线路</option>
        </select></td>
        <td align="right"  class="td_viewcontent_title">简称：</td>
        <td class="td_viewcontent_content">
        <input id="typebrief" type="text" style="width:250px" title="地图上显示简称" maxlength="12" runat="server"></td>
    </tr>
    <tr> 
        <td align="right"  class="td_viewcontent_title">层级：</td>
        <td class="td_viewcontent_content">
		<asp:DropDownList id="typelevel" style="width:250px" autopostback="true" OnSelectedIndexChanged="Level_Change" runat="server"/></td>
        <td align="right"  class="td_viewcontent_title">上级层：</td>
        <td class="td_viewcontent_content">
		<asp:DropDownList id="typeuppercode" style="width:250px" runat="server"/>
		<input id="typeindex" type="hidden" runat="server">
		</td>
    </tr>
    <tr> 
        <td align="right"  class="td_viewcontent_title">状态：</td>
        <td class="td_viewcontent_content" colspan="3">
        <select id="typestate" style="width:250px" runat="server">
            <option>启用</option>
            <option>禁用</option>
            <option>隐藏</option>
        </select>       
        </td>
    </tr>
	<!-- new tr -->
	<tr id="tr_inputicon" visible="false" runat="server">
		<td align="right"  class="td_viewcontent_title">图标：</td>
        <td class="td_viewcontent_content" colspan="3">      
			<table border=0 width="96%" cellspacing="1" cellpadding="0" height="35">
				<tr>
					<td>
						<input id="typeicon" name="typeicon" runat="server" type="hidden"  style="width:30px;" />
						<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" style="WIDTH:16px;HEIGHT:16px;" codebase="Player/swflash.cab#version=7,0,0,0" WIDTH="16" HEIGHT="16" id="IconView" ALIGN="" VIEWASTEXT>
								<PARAM NAME=movie VALUE="<%=aim%>"> 
								<PARAM NAME=quality VALUE=high>
								<PARAM NAME=bgcolor VALUE=#EEEEEE> 
								<PARAM NAME=wmode VALUE=transparent> 
								<EMBED src="<%=aim%>" WIDTH="16" HEIGHT="16" NAME="IconView" bgcolor=#EEEEEE TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer"></EMBED>
						</OBJECT>    
					</td>  
					<td style="width:80px;"> 
						<img src="images/select.gif" style="cursor:hand;paddinX:2px" onmouseout="HideSubMenu('ListIcon');" onmouseover="ShowSubMenu('ListIcon');" border=0 >
						<div id="ListIcon" onmouseout="HideMeMenu(this);" onmouseover="ShowMeMenu(this);" style="display:none;position: absolute;left:350px; top:395px; width: 220; height:80px;cursor:hand;overflow:auto;background-color:#ffffff;border-style:solid; border-width:1px;border-color:#8C9DBE; " >
						<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" style="WIDTH:200px;HEIGHT:60px;position:absolute;LEFT:0px;TOP:0px;Z-INDEX:1;" codebase="Player/swflash.cab#version=7,0,0,0" WIDTH="200" HEIGHT="60" id="IconList" ALIGN="" VIEWASTEXT>
							<PARAM NAME=movie VALUE="<%=aimlist%>"> 
							<PARAM NAME=quality VALUE=high> 
							<PARAM NAME=bgcolor VALUE=#FFFFFF> 
							<EMBED src="<%=aimlist%>"  WIDTH="200" HEIGHT="60" NAME="IconList" TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer"></EMBED>
						</OBJECT>
						<input id="icondata" type="hidden" runat="server">
						</div>
					</td>
					<td width="200"><asp:CheckBox id="coverico" text="同时覆盖子图层图标"  runat="server"/></td>
					<td style="width:50px;">
						<select id="iconsize" style="width:40px" onchange="Updatesize(typeicon.value,iconsize.value)"  runat="server">
							<option>16</option>
							<option>32</option>     
						</select>
					</td>
				</tr>
			</table>
		
		</td>
	</tr>
	<!-- end tr -->
    <tr> 
        <td align="right"  class="td_viewcontent_title">备注：</td>
        <td class="td_viewcontent_content" colspan="3">
        <textarea id="typedesc" rows="4" style="width:95%" runat="server"></textarea></td>
    </tr>
</table>							
</form>
<script language="javascript">
<!--
function IconView_DoFSCommand(command, args){
	if(command == "resize"){		
		var _s = args.split("|");
		//alert(_s);
		if(_s[0] != "" && _s[0] != "0"){			
			window.document.IconView.style.width = parseInt(_s[0]);
		}
		if(_s[1] != "" && _s[1] != "0"){			
			window.document.IconView.style.height = parseInt(_s[1]);
		}
	}else{
		//alert(command+":"+args);
	}
}

function IconList_DoFSCommand(command, args){
	if(command == "getid"){
		window.document.form1.typeicon.value=args;	
		var _size=window.document.form1.iconsize.value;	
		this.document.IconView.LoadMovie(0,"/SpeedMap/Shell.aspx?com=IconSystem&mode=view&id="+args+"&size="+_size+"");			
	}else if(command == "resize"){
		var _s = args.split("|");				
		if(_s[0] != "" && _s[0] != "0"){
			document.all.item("ListIcon").style.width = parseInt(_s[0])+10;
			window.document.IconList.style.width = parseInt(_s[0]);
		}
		if(_s[1] != "" && _s[1] != "0"){
			document.all.item("ListIcon").style.height = parseInt(_s[1])+10;
			window.document.IconList.style.height = parseInt(_s[1]);
		}
	}else{
		//alert(command+":"+args);
	}
}

function Updatesize(iconid,size){
window.document.IconList.LoadMovie(0,"/SpeedMap/Shell.aspx?com=IconSystem&mode=list&size="+size+"");
if(iconid!="0" ){
	window.document.IconView.style.width=size;
	window.document.IconView.style.height=size;
}
}
//-->
</script>
<SCRIPT LANGUAGE="VBScript">
Sub IconView_FSCommand(ByVal cmd, ByVal args)
	call IconView_DoFSCommand(cmd, args)
end sub

Sub IconList_FSCommand(ByVal cmd, ByVal args)
	call IconList_DoFSCommand(cmd, args)
end sub
</SCRIPT>
</body>
<script>
function ShowSubMenu(eventTarget)
{
	
	var mn = document.all.item(eventTarget);
	mn.style.display = "";	
}
function HideSubMenu(eventTarget)
{
	
	var mn = document.all.item(eventTarget);
	mn.style.display = "none";	
}
function ShowMeMenu(eventTarget)
{
	
	var mn = eventTarget;
	mn.style.display = "";	
}
function HideMeMenu(eventTarget)
{
	
	var mn = eventTarget;
	mn.style.display = "none";	
}

</script>
</html>
