function CreateControl(DivID, ObjectID, URL, WIDTH, HEIGHT, BGCOLOR)
{
  var d = document.getElementById(DivID);
  d.innerHTML = '<object id="'+ObjectID+'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="images/swflash.cab#version=8,0,22,0" '+
				'width="'+WIDTH+'" height="'+HEIGHT+'" style="WIDTH:100%;HEIGHT:100%;" VIEWASTEXT>'+
				'<param name="movie" value="'+URL+'" /><param name="quality" value="high" /><param name="allowScriptAccess" value="sameDomain" />'+
				'<param name="loop" value="false" /><param name="menu" value="false">'+
				'<embed src="'+URL+'" quality="high" loop="false" width="'+WIDTH+'" height="'+HEIGHT+'" name="'+ObjectID+'" align="" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object>';			
}
