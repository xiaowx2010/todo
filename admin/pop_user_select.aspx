<%@ Page Language="c#"  Inherits="MIS.WebApplication.Controls.Page"%>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
<HTML>
<HEAD>	
	<X:HtmlHead ID="MIS" runat="server"></X:HtmlHead>
	<meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5">
</HEAD>
<!--#include file="func.aspx"-->
<script language="C#" runat="server">
string p_slist="";
private void Page_Load(object sender, System.EventArgs e)
{
	 p_slist=GetQueryString("slist","0");	 
}
</script>
<body style="margin:0px;padding:0px;text-align:center">
<div id="divselectfold"></div>
<script language=javascript>
<!--
CreateControl("divselectfold","swfselectfold","images/MultiSelect.swf?app=swfselectfold&mode=1&icon=user&dataroot=pop_user_selectdata.aspx&fold=0&sellist=<%=p_slist%>", 390, 260, "");

function swfselectfold_DoFSCommand(command, args){       
	if(command == "resize"){
	}else if(command == "showmenu"){
	}else if (command == "status"){
		window.status = args;
	}else if(command == "data"){
	   var arr_args=args.split("|");
	    opener.form1.usergroupcode.value="";
		opener.form1.usergroupcode_txt.value="";
		//alert(arr_args.length);
	    if(arr_args.length>1)
	    {
	        opener.form1.usergroupcode.value=arr_args[1];
		    opener.form1.usergroupcode_txt.value=arr_args[0];
		}
		//window.status = args;
	}else{
		alert(command+":"+args);
	}
}
//-->
</script>

<SCRIPT language=VBScript>
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
</body>
</html>