<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<%@ Page Language="c#" Inherits="MIS.WebApplication.Controls.CheckLoginPage" %>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
<HTML>
	<HEAD>
		<X:HtmlHead ID="MIS" runat="server"></X:HtmlHead>
		<meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5">
		<link rel="StyleSheet" href="dtree.css" type="text/css" />
	    <script type="text/javascript" src="dtree.js"></script>
	</HEAD>
	<!--#include file="func.aspx"-->
	<script runat="server" language="C#">
    string closed;   
    private void Page_Load(object sender, System.EventArgs e)
    {
        closed = GetQueryString("closed", "0");//左边目录权展开状态 
        mid = GetQueryString("mid", "0");//模块编号(选择中项编号)
        
    }
    private void BindMenu()
    {
        string h_fstr = "";
        DataTable dt = db.GetDataTable(db.ConnStr, "select modulecode,modulename,moduleuppercode,(select top 1 1 from gmis_module where " + mid + " in (select modulecode from gmis_module where moduleindex like '%'+(select moduleindex from gmis_module s1 where s1.modulecode=s2.modulecode)+'%')) as isopen,modulelevel,moduleindex from gmis_module s2 where modulestate=0 order by moduleindex");

        Response.Write("d.root=new Node(0);\n");
        Response.Write("d.add(-2,0,'测试项目一','DeskTop.aspx','测试项目一','main',null,null,null,'');\n");
        
        foreach (DataRow dr in dt.Rows)
        {
            if(Isshowthis(dr["moduleindex"].ToString()) || IsSystemDeveloper() || (IsSystemManager() && dr["modulecode"].ToString()!="2" && dr["modulecode"].ToString()!="3" && dr["modulecode"].ToString()!="7" ) )
		    {
                string isopen = (dr["isopen"].ToString() == "1") ? "true" : "false";
                string uppercode = (dr["moduleuppercode"].ToString() != "0") ? dr["moduleuppercode"].ToString() : "-2";

                if (dr["modulelevel"].ToString() == "1")
                {
                    Response.Write("d.add(" + dr["modulecode"].ToString() + "," + uppercode + ",'" + dr["modulename"].ToString() + "',null,'" + dr["modulename"].ToString() + "',null,null,null," + isopen + ",'');\n");
                }
                else
                {
                    Response.Write("d.add(" + dr["modulecode"].ToString() + "," + uppercode + ",'" + dr["modulename"].ToString() + "','getpage.aspx?mid=" + dr["modulecode"].ToString() + "','" + dr["modulename"].ToString() + "','main',null,null," + isopen + ",'');\n");
            }
            }
        }
        
       

    }   	
</script>
<body style="border:0px;margin:0px;" bgcolor="#ffffff" scroll="no">
<div id="controlarea" style="width:249px; height:200px; overflow:auto; table-layout: fixed;WORD-BREAK: break-all; WORD-WRAP: break-word;float:left; border:solid 1px #999999; border-right:none;">
<script language="javascript" type="text/javascript">
<!--
//id, pid, name, url, title, target, icon, iconOPne, open,

		d = new dTree('d');		
		d.config.target="main";	
		d.config.useCookies = false;
		d.config.useStatusText=true;
		d.config.folderLinks=true;
		d.selectedNode=<%=mid%>;
		<%BindMenu();%>
		
        document.write(d.toString());
//-->
</script>
</div>
<div id="btnDiv" style="float:left;width:7px;">
</div>
<script language="javascript" type="text/javascript">
<!--
var divClosed=<%=closed%>;
var treediv = document.getElementById("controlarea");
var btndiv = document.getElementById("btnDiv");
treediv.style.height= ((document.documentElement.clientHeight-2).toString()+"px") ;
btndiv.style.height= ((document.documentElement.clientHeight-2).toString()+"px") ;
btndiv.innerHTML="<table cellspacing='0' cellpadding='0' style='width: 100%;'><tr class='mid_rtopbg'><td>&nbsp;</td></tr></table><table width='100%' height='100%' cellpadding='0' cellspacing='0' border='0'><tr><td valign='middle'><img id='imgbtn' src='images/0_gray.gif' style='padding-left:1px;'/></td></tr></table>";
var btnimg = document.getElementById("imgbtn");

btndiv.onmouseover = function(){
    this.style.backgroundColor="#ffcc88";
    btnimg.src="images/"+divClosed+"_white.gif";
}
btndiv.onmouseout=function(){
    this.style.backgroundColor="white";
    btnimg.src="images/"+divClosed+"_gray.gif";
}
btndiv.onclick=function(){
    treeFold();
}

treeFold();

function treeFold()
{
    if(divClosed==0)
    {
        divClosed=1;
        btndiv.style.width="6px";
        btndiv.style.borderRight="none";
        btndiv.style.borderLeft="solid 1px #999999";
        treediv.style.display="block";
        window.parent.document.getElementById("mainset").cols = "257,*";
    }
    else
    {
        divClosed=0;
        btndiv.style.width="6px";
        btndiv.style.borderRight="solid 1px #999999";
        btndiv.style.borderLeft="none";
        treediv.style.display="none";
        window.parent.document.getElementById("mainset").cols = "7,*";
    }
    btnimg.src="images/"+divClosed+"_gray.gif";
    
}

//-->
</script>

</BODY></HTML>
