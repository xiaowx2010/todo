function WriteFlash(ObjectID, URL, WIDTH, HEIGHT, STYLE)
{
    document.write('<object id="'+ObjectID+'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="player/swflash9.cab#version=9,0,47,0" width="'+WIDTH+'" height="'+HEIGHT+'" style="'+STYLE+'" VIEWASTEXT><param name="movie" value="'+URL+'" /><param name="quality" value="high" /><param name="allowScriptAccess" value="sameDomain" /><param name="loop" value="false" /><param name="menu" value="false"><embed src="'+URL+'" quality="high" loop="false" width="'+WIDTH+'" height="'+HEIGHT+'" name="'+ObjectID+'" align="" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object>');
}

<%--function WriteChartControl(DivID, ObjectID, URL, dataXML,WIDTH, HEIGHT, BGCOLOR)
{
  var d = document.getElementById(DivID);
  d.innerHTML = '<object id="'+ObjectID+'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="player/swflash9.cab#version=9,0,0,0" '+
				'width="'+WIDTH+'" height="'+HEIGHT+'"><param name="movie" value="'+URL+'" />'+
				'<param name="flashvars" value="&chartWidth='+WIDTH+'&chartHeight='+HEIGHT+'&dataXML='+dataXML+'" /><param name="quality" value="high" /><param name="allowScriptAccess" value="sameDomain" />'+
				'<param name="loop" value="false" /><param name="wmode" value="Opaque"><param name="menu" value="true">'+
				'<embed src="'+URL+'" quality="high" loop="false" width="'+WIDTH+'" height="'+HEIGHT+'" name="'+ObjectID+'" flashvars="&chartWidth='+WIDTH+'&chartHeight='+HEIGHT+'&dataXML='+dataXML+'" align="" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object>';
}--%>

function WriteChartControl(ObjectID, URL, dataXML,WIDTH, HEIGHT, BGCOLOR)
{
    document.write('<object id="Object1" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="swflash9.cab#version=9,0,0,0" width="'+WIDTH+'" height="'+HEIGHT+'"><param name="movie" value="'+URL+'" /><param name="flashvars" value="&chartWidth='+WIDTH+'&chartHeight='+HEIGHT+'&dataXML='+dataXML+'" /><param name="quality" value="high" /><param name="allowScriptAccess" value="sameDomain" /><param name="loop" value="false" /><param name="wmode" value="Opaque"><param name="menu" value="true"><embed src="'+URL+'" quality="high" loop="false" width="'+WIDTH+'" height="'+HEIGHT+'" name="'+ObjectID+'" flashvars="&chartWidth='+WIDTH+'&chartHeight='+HEIGHT+'&dataXML='+dataXML+'" align="" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object>');
}


function CreateControl(DivID, ObjectID, URL, WIDTH, HEIGHT, BGCOLOR)
{
    var d = document.getElementById(DivID);
    
    d.innerHTML = '<object id="Object2" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="player/swflash90470.cab#version=9,0,47,0" width="'+WIDTH+'" height="'+HEIGHT+'" style="width:100%;height:100%" VIEWASTEXT><param name="wmode" value="transparent"><param name="movie" value="'+URL+'" /><param name="quality" value="high" /><param name="allowScriptAccess" value="sameDomain" /><param name="loop" value="false" /><param name="menu" value="false"><embed src="'+URL+'" quality="high" loop="false" width="'+WIDTH+'" height="'+HEIGHT+'" name="'+ObjectID+'" align="" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object>';

    
}

function CreateNoTransparentControl(DivID,ObjectID, URL, WIDTH, HEIGHT, BGCOLOR)
{
    var d = document.getElementById(DivID);
    d.innerHTML ='<object id="Object3" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="player/swflash90470.cab#version=9,0,47,0" width="'+WIDTH+'" height="'+HEIGHT+'" style="width:100%;height:100%" VIEWASTEXT><param name="movie" value="'+URL+'" /><param name="quality" value="high" /><param name="allowScriptAccess" value="sameDomain" /><param name="loop" value="false" /><param name="menu" value="false"><embed src="'+URL+'" quality="high" loop="false" width="'+WIDTH+'" height="'+HEIGHT+'" name="'+ObjectID+'" align="" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object>';
}

function CreateFlashControl(DivID, ObjectID, URL, WIDTH, HEIGHT, STYLE)
{	
    var d = window.document.getElementById(DivID);
    d.innerHTML = '<object id="Object4" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="player/swflash90470.cab#version=9,0,47,0" width="'+WIDTH+'" height="'+HEIGHT+'" style="'+STYLE+'" VIEWASTEXT><param name="movie" value="'+URL+'" /><param name="quality" value="high" /><param name="wmode" value="transparent" /><param name="allowScriptAccess" value="sameDomain" /><param name="loop" value="false" /><param name="menu" value="false"><embed src="'+URL+'" quality="high" loop="false" width="'+WIDTH+'" height="'+HEIGHT+'" name="'+ObjectID+'" align="" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object>';       
}

var yfla={
	control:function(){
		var oF=navigator.plugins["Shockwave Flash"]||new ActiveXObject("ShockwaveFlash.ShockwaveFlash");
		return(oF)?1:0;
	},
	version:function(){
		var oF;
		var v=null;
		if(navigator.plugins&&navigator.mimeTypes.length){
			oF=navigator.plugins["Shockwave Flash"];
			if(oF&&oF.description){
				v=oF.description.replace(/([a-z]|[A-Z]|\s)+/,"").replace(/(\s+r|\s+b[0-9]+)/,".").split(".")[0];
			}
		}else if(window.ActiveXObject){
			for(i=25;i>0;i--){
				v=VBGetSwfVer(i);
			}
			if(v!=0&&v!=null){
				v=v.split(",")[0].split(" ")[1];
			}
		}
		return parseInt(v);
	}
}
//创建日历//
function CreateDate(DivID, ObjectID, BtnAlign, Depth)
{
	var d = document.getElementById(DivID);
	d.innerHTML = '<object id="Object5" name="'+ObjectID+'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="player/8/swflash.cab#version=8,0,0,0" '+
		'width="206" height="215" style="width:18px;height:18px;position:absolute;Z-INDEX:'+Depth+';" onblur="closeChoseDate(this)" VIEWASTEXT>'+
		'<param name="movie" value="images/ChoseDate.swf?btnalign='+BtnAlign+'" /><param name="quality" value="high" />'+
		'<param name="allowScriptAccess" value="sameDomain" /><param name="loop" value="false" /><param name="menu" value="false">'+
		'<embed id="Embed1" src="images/ChoseDate.swf?btnalign='+BtnAlign+'" quality="high" loop="false" width="206" height="215" name="'+ObjectID+'" align="" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object>';			
	d.children(0).mask = new Array();
	return d.children(0);	
}
//关闭日历//
function closeChoseDate(eventTarget) {
	eventTarget.GotoFrame(2);
	eventTarget.style.left = 0;
	eventTarget.style.top = 0;
	eventTarget.style.width = 18;
	eventTarget.style.height = 18;
	if(eventTarget.mask != null && eventTarget.mask.length>0){
		for(i=0;i<eventTarget.mask.length;i++){
			eventTarget.mask[i].style.display = "";
		}
	}
}
<!--显示日历-->
function showChoseDate(eventTarget, btnAlign) {
	eventTarget.style.width = 206;
	eventTarget.style.height = 215;

	if(btnAlign == "right"){
		eventTarget.style.left  = -188;
	}else if(btnAlign == "top"){
		eventTarget.style.left  = -188;
		eventTarget.style.top  = -188;
	}else if(btnAlign == "bottom"){
		eventTarget.style.top  = -188;
	}
	if(eventTarget.mask != null && eventTarget.mask.length>0){
		for(i=0;i<eventTarget.mask.length;i++){
			eventTarget.mask[i].style.display = "none";
		}
	}	
}
function __doOpenMap(arg1,arg2){	
    var pop;
	if(pop == null){
		pop = open("/SpeedMap/SpeedMap.aspx?drawmode="+arg1+"&searchdata="+arg2,"BianJi","height=768,width=1024,status=no,toolbar=no,menubar=no,location=no,resizable=no,directories=no,top=0,left=0",true);
	}else{
		pop.document.SpeedMap.SetVariable("_root.drawmode",arg1);
		pop.document.SpeedMap.SetVariable("_root.searchdata",arg2);
		pop.document.SpeedMap.GotoFrame(3);
		
	}
	pop.focus();
}
function __doOpenWindows(aim){		
	window.open(aim,"","height=260,width=400,status=no,toolbar=no,menubar=no,scrollbars=yes,location=no,resizable=no,directories=no,top=270,left=330","");
	
}
function __doOpen(aim){		
	window.open(aim,"","height=260,width=400,status=no,toolbar=no,menubar=no,scrollbars=yes,location=no,resizable=no,directories=no,top=270,left=330","");
	
}
function __doOpenView(aim,_width,_height,_top,_left){
	if(_width==0 || _width=="") _width=350;
	if(_height==0 || _height=="") _height=300;
	if(_top=="") _top=10;
	if(_left=="") _left=10;
	var m = open(aim,"View","width="+_width+",height="+_height+",status=no,toolbar=no,menubar=no,location=no,resizable=no,directories=no,top="+_top+",left="+_left+"",true);
	m.focus();
}
function __doOpenView2(aim,_width,_height,_top,_left){
	if(_width==0 || _width=="") _width=350;
	if(_height==0 || _height=="") _height=300;
	if(_top=="") _top=10;
	if(_left=="") _left=10;
	var m2 = window.open(aim,"View2","width="+_width+",height="+_height+",status=no,toolbar=no,menubar=no,location=no,resizable=no,directories=no,top="+_top+",left="+_left+"",true);
	m2.focus();
}
function __doOpenSelect(aim,_width,_height,_top,_left){
	if(_width==0 || _width=="") _width=350;
	if(_height==0 || _height=="") _height=300;
	if(_top=="") _top=10;
	if(_left=="") _left=10;
	var s = open(aim,"Select","width="+_width+",height="+_height+",status=no,toolbar=no,menubar=no,location=no,resizable=no,directories=no,top="+_top+",left="+_left+"",true);
	s.focus();
}

var base64EncodeChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/"; 
var base64DecodeChars = new Array( 
-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 
-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 
-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 62, -1, -1, -1, 63, 
52, 53, 54, 55, 56, 57, 58, 59, 60, 61, -1, -1, -1, -1, -1, -1, 
-1, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 
15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, -1, -1, -1, -1, -1, 
-1, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 
41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, -1, -1, -1, -1, -1); 
<!--utf16to8-->
function utf16to8(str) { 
    var out, i, len, c; 
    out = ""; 
    len = str.length; 
    for(i = 0; i < len; i++) { 
        c = str.charCodeAt(i); 
        if ((c >= 0x0001) && (c <= 0x007F)) 
        { 
            out += str.charAt(i); 
        }       
        else if (c > 0x07FF) 
        { 
            out += String.fromCharCode(0xE0 | ((c >> 12) & 0x0F)); 
            out += String.fromCharCode(0x80 | ((c >> 6) & 0x3F)); 
            out += String.fromCharCode(0x80 | ((c >> 0) & 0x3F)); 
        } 
        else 
        { 
        out += String.fromCharCode(0xC0 | ((c >> 6) & 0x1F)); 
            out += String.fromCharCode(0x80 | ((c >> 0) & 0x3F)); 
        } 
    } 
    return out; 
} 
<!--base64encode-->
function base64encode(str) { 
    var out, i, len; 
    var c1, c2, c3; 
    len = str.length; 
    i = 0; 
    out = ""; 
    while(i < len) { 
        c1 = str.charCodeAt(i++) & 0xff; 
        if(i == len) 
        { 
           out += base64EncodeChars.charAt(c1 >> 2); 
           out += base64EncodeChars.charAt((c1 & 0x3) << 4); 
           out += "=="; 
           break; 
        } 
           c2 = str.charCodeAt(i++); 
        if(i == len) 
        { 
           out += base64EncodeChars.charAt(c1 >> 2); 
           out += base64EncodeChars.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4)); 
           out += base64EncodeChars.charAt((c2 & 0xF) << 2); 
           out += "="; 
           break; 
        } 
        c3 = str.charCodeAt(i++); 
        out += base64EncodeChars.charAt(c1 >> 2); 
        out += base64EncodeChars.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4)); 
        out += base64EncodeChars.charAt(((c2 & 0xF) << 2) | ((c3 & 0xC0) >>6)); 
        out += base64EncodeChars.charAt(c3 & 0x3F); 
    } 
    return out; 
} 
function CreateColorControl(DivID, ObjectID, BtnAlign, Depth,IniValue)
{
    
	var d = document.getElementById(DivID);
	
	d.innerHTML = '<object id="Object6" name="'+ObjectID+'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="229" height="159" style="width:23px;height:20px;top:1px;position:absolute;Z-INDEX:'+Depth+';" VIEWASTEXT>'+'<param name="movie" value="images/colorpicker.swf?btnalign='+BtnAlign+'&inivalue='+IniValue+'" /><param name="quality" value="high" />'+
		'<param name="allowScriptAccess" value="sameDomain" /><param name="loop" value="false" /><param name="menu" value="false">'+'<embed id="Embed2" src="images/colorpicker.swf?btnalign='+BtnAlign+'&inivalue='+IniValue+'" quality="high" loop="false" width="229" height="159" name="'+ObjectID+'" align="" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object>';
				
	return d.children(0);	
}

//显示统计分析图
function CreateChartControl(DivID, ObjectID, URL, dataXML,WIDTH, HEIGHT, BGCOLOR)
{
  var d = document.getElementById(DivID);
  d.innerHTML = '<object id="Object7" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="swflash9.cab#version=9,0,0,0" '+
				'width="'+WIDTH+'" height="'+HEIGHT+'"><param name="movie" value="'+URL+'" />'+
				'<param name="flashvars" value="&chartWidth='+WIDTH+'&chartHeight='+HEIGHT+'&dataXML='+dataXML+'" /><param name="quality" value="high" /><param name="allowScriptAccess" value="sameDomain" />'+
				'<param name="loop" value="false" /><param name="wmode" value="Opaque"><param name="menu" value="true">'+
				'<embed src="'+URL+'" quality="high" loop="false" width="'+WIDTH+'" height="'+HEIGHT+'" name="'+ObjectID+'" flashvars="&chartWidth='+WIDTH+'&chartHeight='+HEIGHT+'&dataXML='+dataXML+'" align="" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object>';
}

//关闭颜色控件//
function closeColorControl(eventTarget) {
	eventTarget.style.left = 0;
	eventTarget.style.top = 1;
	eventTarget.style.width = 23;
	eventTarget.style.height = 20;
}
//显示颜色控件//
function showColorControl(eventTarget, btnAlign) {
	eventTarget.style.width = 229;
	eventTarget.style.height = 159;	
	if(btnAlign == "1"){
		eventTarget.style.left  = -206;
	}else if(btnAlign == "2"){
		eventTarget.style.left  = -206;
		eventTarget.style.top  = -139;
	}else if(btnAlign == "3"){
		eventTarget.style.top  = -139;
	}/**/
}