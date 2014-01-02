<%@ Page Language="c#" Inherits="MIS.WebApplication.Controls.CheckLoginPage" %>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
<HTML>
	<HEAD>
		<X:HtmlHead ID="MIS" runat="server"></X:HtmlHead>
		<meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5">
	</HEAD>
	<!--#include file="func.aspx"-->
	<script runat="server" language="C#">
    string closed;   
    private void Page_Load(object sender, System.EventArgs e)
    {
        closed = GetQueryString("closed", "0");//左边目录权展开状态 
        pid = GetQueryString("pid", "0");//左边目录树选项卡模块编号
        mid = GetQueryString("mid", "0");//模块编号(选择中项编号)
        
        
    }	
</script>
<body style="border:0px;margin:0px;" bgcolor="#ffffff" scroll="no">
<div id="controlarea"></div>
<script language=javascript>
<!--
CreateControl("controlarea","Nav","images/Nav.swf?pid=<%=pid%>&mid=<%=mid%>&closed=<%=closed %>", 550, 400, "#FFFFFF");
function Nav_DoFSCommand(command, args){
    //alert(command+":"+args);
	if(command=="data")
	{
	    window.parent.frames(3).location="getpage.aspx?mid="+args;	
	}
	else if(command == "resize"){
		
	}else if (command == "status"){
		window.status = args;
	}else if(command == "hide"){
		if(args == "0"){
			window.parent.document.getElementById("mainset").cols = "7,*";
		}else{
			window.parent.document.getElementById("mainset").cols = "257,*";
		}
	}else if(command != "showmenu"){
		//alert(command+":"+args);
	}
}
//-->
</script>

<SCRIPT language=VBScript>
Sub Nav_FSCommand(ByVal cmd, ByVal args)
	call Nav_DoFSCommand(cmd, args)
end Sub

Function VBGetSwfVer(i)
	on error resume next
	Dim swControl, swVersion
	swVersion = 0

	set swControl = CreateObject("ShockwaveFlash.ShockwaveFlash." + CStr(i))
	if (IsObject(swControl)) then
    		swVersion = swControl.GetVariable("$version")
	end if
    	VBGetSwfVer = swVersion
End Function
</SCRIPT>
</BODY></HTML>
