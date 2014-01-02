<%@ Page Language="c#" Inherits="MIS.WebApplication.Controls.CheckLoginPage" %>
<%@ Register TagPrefix="X" Namespace="MIS.WebApplication.Controls" Assembly="MIS.WebApplication.Controls" %>
<HTML>
	<HEAD>
		<X:HtmlHead ID="MIS" runat="server"></X:HtmlHead>
		<meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5">
	</HEAD>
	<!--#include file="func.aspx"-->
	<script runat="server" language="C#">
    private void Page_Load(object sender, System.EventArgs e)
    {
      
        //if((";"+GetModuleActions("10")+";").IndexOf(";2;")>-1)
        //{
        //    main.Attributes.Add("src", "edit_10.aspx?mid=10&aid="+StringUtility.StringToBase64("add")+"");
        //}else
        //{
        //    main.Attributes.Add("src", "list_13.aspx?mid=13&aid="+StringUtility.StringToBase64("list")+"");
        //}
        
        pid = GetQueryString("pid", "0");//左边目录树选项卡模块编号
        mid = GetQueryString("mid", "0");//模块编号
        /**/
        string cid = GetQueryString("cid", "0");//栏目编号
        string closed = GetQueryString("closed", "1");//左边目录权展开状态

        if (pid != "0")
        {
            nav.Attributes.Add("src", "nav.aspx?pid=" + pid + "&mid=" + mid + "&cid=" + cid + "&closed=" + closed);
        }
        else
        {
            nav.Attributes.Add("src", "nav.aspx?closed=" + closed);
        }
        if (GetSESSION("MainUrl") != "")
        {
            main.Attributes.Add("src", GetSESSION("MainUrl"));
        }
        else
        {
            main.Attributes.Add("src", "getpage.aspx?mid=0&aid=" + StringUtility.StringToBase64("list") + "");
        }
       
        //SetSESSION("MainUrl", "");       
    }	
</script>
<frameset id="allset" rows="44,*" border="0">
    <frame name="banner" scrolling="no" src="Banner.aspx" frameborder="0" noresize>
    <frameset id="mainset" cols="257,*" border="0">
        <frame name="nav" id="nav" src="Nav.aspx" scrolling="no" frameborder="0" runat="server"/>
        <frameset id="conset" rows="5,*" border="0">
            <frame name="bar" src="Shadow.html" scrolling="no" frameborder="0" noresize>
            <frame id="main" name="main" src="" frameborder="0" runat="server" />
        </frameset>
    </frameset>
    <noframes>
        <p>
            正常运行本项目要求浏览器支持框架，请使用IE6或以上版本的浏览器。</p>
    </noframes>
</frameset>
</html>
<%--	<frameset id="allset" rows="44,*" border="0">
		<frame name="banner" scrolling="no" src="Banner.aspx" frameborder="0" noresize>
		<frameset id="mainset" cols="257,*" border="0">
			<frame name="nav" src="Nav.aspx" scrolling="no" frameborder="0" runat="server" />
			<frameset id="conset" rows="5,*" border="0">
				<frame name="bar" src="Shadow.html" scrolling="no" frameborder="0" noresize>
				<frame id="main" name="main" src="" frameborder="0" runat="server"/>
			</frameset>
		</frameset>
		<noframes>
			<p>正常运行本项目要求浏览器支持框架，请使用IE6或以上版本的浏览器。</p>
		</noframes>
	</frameset>
</html>--%>
