<%@ Page Language="c#" ValidateRequest="false" %>
<%@ Import Namespace="System.IO" %>
<%@ Import Namespace="System.Text" %>
<%@ Import Namespace="System.Data" %>
<%@ Import Namespace="System.Xml" %>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" > 
<html>
  <head>
    <title id="hostname" runat="server">RA</title>
    <meta name=vs_defaultClientScript content="JavaScript">
    <meta name=vs_targetSchema content="http://schemas.microsoft.com/intellisense/ie5">
    <style>
		BODY,TD,A,INPUT,TextArea
		{
			font-size: 12px;
			font-family: ËÎÌו, Verdana, Arial, Helvetica;
			text-decoration: none;
			color:#000000;
		}
		A:hover
		{
			color:#3366cc;
		}
		.Tdir
		{
			background-color:#cccccc; 
		}
		.BTN
		{
			border: #999999 1px solid;
			background-color:#dcdcdc; 
			padding-top:1px;
			height:17px;
		}
    </style>
  </head>
	<script language=javascript>
	<!--
			function KeyIn(){
				if(event.keyCode == "13"){__doPostBack('login','');}
			}
			function KeyTo(){
				if(event.keyCode == "13"){__doPostBack('go','');}
			}
	//-->
	</script>
	<script runat=server language=C#>
		private StringBuilder sb = new StringBuilder();
		string baseroot,thisroot,froot,troot;
		string baseroot_b,thisroot_b;
		string SortField,myname="ra.aspx";
		long StartPos = 0, FileSize;
		private void Page_Load(object sender, System.EventArgs e)
		{
			Get_PRODUCT_NAME();
		}	
		byte[] StrToByteArray(string str)
		{
			//try{return System.Text.Encoding.GetEncoding("gb2312").GetBytes(str);}
			try{return System.Text.Encoding.Default.GetBytes(str);}
			catch{return null;}
		}
	
		string StringToBase64(string iStr)
		{
			try{return System.Convert.ToBase64String(StrToByteArray(iStr)).Replace('+','|');}
			catch{return "";}
		}		
		private void Get_PRODUCT_NAME(){
			string temp = StringToBase64(Request.ServerVariables["SERVER_NAME"].ToUpper()+Request.ServerVariables["LOCAL_ADDR"].ToUpper()+Server.MachineName.ToUpper());
			if(temp.Length>6){temp = temp.Substring(0,5)+"="+temp.Substring(5);}
			alert.InnerText = temp.Replace('|','+');
		}
		
	</script>
<body MS_POSITIONING="GridLayout" style="margin:0px;padding:0px;" scroll="no">
<form id="Form1" method="post" runat="server">
	<div id="alert" style="color:red" runat="server"></div>
</form>
</body>
</html>
