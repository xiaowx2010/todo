/*
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003-2005 Frederico Caldeira Knabben
 * 
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 * 
 * For further information visit:
 * 		http://www.fckeditor.net/
 * 
 * File Name: fck_media.js
 * 	Scripts related to the Media dialog window (see fck_media.html).
 * 
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 */

var oEditor		= window.parent.InnerDialogLoaded() ;
var FCK			= oEditor.FCK ;
var FCKLang		= oEditor.FCKLang ;
var FCKConfig	= oEditor.FCKConfig ;

//#### Dialog Tabs

// Set the dialog tabs.
window.parent.AddTab( 'Info', oEditor.FCKLang.DlgInfoTab ) ;

if ( FCKConfig.MediaUpload )
	window.parent.AddTab( 'Upload', FCKLang.DlgLnkUpload ) ;

if ( !FCKConfig.MediaDlgHideAdvanced )
	window.parent.AddTab( 'Advanced', oEditor.FCKLang.DlgAdvancedTag ) ;

// Function called when a dialog tag is selected.
function OnDialogTabChange( tabCode )
{
	ShowE('divInfo'		, ( tabCode == 'Info' ) ) ;
	ShowE('divUpload'	, ( tabCode == 'Upload' ) ) ;
	ShowE('divAdvanced'	, ( tabCode == 'Advanced' ) ) ;
}

// Get the selected media embed (if available).
var oMedia = FCK.Selection.GetSelectedElement() ;

if ( oMedia && oMedia.tagName != 'EMBED' )
	oMedia = null ;

window.onload = function()
{
	// Translate the dialog box texts.
	oEditor.FCKLanguageManager.TranslatePage(document) ;

	// Load the selected element information (if any).
	LoadSelection() ;

	// Show/Hide the "Browse Server" button.
	GetE('tdBrowse').style.display = FCKConfig.MediaBrowser	? '' : 'none' ;

	// Set the actual uploader URL.
	if ( FCKConfig.MediaUpload )
		GetE('frmUpload').action = FCKConfig.MediaUploadURL ;

	window.parent.SetAutoSize( true ) ;

	// Activate the "OK" button.
	window.parent.SetOkButton( true ) ;
}

function LoadSelection()
{
	if ( ! oMedia ) return ;

	var sUrl = GetAttribute( oMedia, 'src', '' ) ;

	GetE('txtUrl').value    = GetAttribute( oMedia, 'src', '' ) ;
	if(GetE('txtUrl').value == null || GetE('txtUrl').value == ''){
		GetE('txtUrl').value = GetAttribute( oMedia, 'filename', '' ) ;
	}
	GetE('FileExtName').value  = GetAttribute( oMedia, 'title', '' ) ;
	GetE('txtWidth').value  = GetAttribute( oMedia, 'width', '' ) ;
	GetE('txtHeight').value = GetAttribute( oMedia, 'height', '' ) ;

	UpdatePreview() ;
}

//#### The OK button was hit.
function Ok()
{
	if ( GetE('txtUrl').value.length == 0 )
	{
		window.parent.SetSelectedTab( 'Info' ) ;
		GetE('txtUrl').focus() ;

		alert( oEditor.FCKLang.DlgAlertUrl ) ;

		return false ;
	}
	
	if ( !oMedia )
	{
		oMedia	= FCK.EditorDocument.createElement( 'EMBED' ) ;
		oMedia	= FCK.InsertElementAndGetIt( oMedia ) ;
	}
	else
		oEditor.FCKUndo.SaveUndoStep() ;
	
	UpdateEmbed( oMedia ) ;

	FCK.SetHTML( FCK.GetHTML(), true ) ;
	return true ;
}

function UpdateEmbed( e )
{
	/*SetAttribute( e, 'type'			, 'application/x-shockwave-flash' ) ;
	SetAttribute( e, 'pluginspage'	, 'http://www.macromedia.com/go/getflashplayer' ) ;

	e.src = GetE('txtUrl').value ;
	SetAttribute( e, "width" , GetE('txtWidth').value ) ;
	SetAttribute( e, "height", GetE('txtHeight').value ) ;
	
	// Advances Attributes

	SetAttribute( e, 'id'	, GetE('txtAttId').value ) ;
	//SetAttribute( e, 'scale', GetE('cmbScale').value ) ;

	//if (!(GetE('chkAutoPlay').checked))	SetAttribute( e, 'play', 'false' ) ;
	//if ( !GetE('chkLoop').checked )		SetAttribute( e, 'loop', 'false' ) ;
	//if ( !GetE('chkMenu').checked )		SetAttribute( e, 'menu', 'false' ) ;

	SetAttribute( e, 'title'	, GetE('txtAttTitle').value ) ;
	SetAttribute( e, 'class'	, GetE('txtAttClasses').value ) ;

	if ( oEditor.FCKBrowserInfo.IsIE )
		e.style.cssText = GetE('txtAttStyle').value ;
	else
		SetAttribute( e, 'style', GetE('txtAttStyle').value ) ;*/
	e.outerHTML = GetE('ePreviewCell').innerHTML;
}

function GetOuterHtml(){
	var str = '<embed id=ePreview title='+GetE('FileExtName').value+' src='+GetE('txtUrl').value+' ';
	//var nw = GetE('txtWidth').value;
	//var nh = GetE('txtHeight').value;	
	//if(nw != null && nw != ''){str += 'width='+nw+' ';}
	//if(nh != null && nh != ''){str += 'height='+nh+' ';}
	switch ( GetE('FileExtName').value )
	{
		case '.asf' :
		str += 'showcontrols=0 showstatusbar=0 autostart=true type=application/x-mplayer2 pluginspage=http://www.microsoft.com/isapi/redir.dll?prd=windows&sbp=mediaplayer&ar=media&sba=plugin>';
		break;
		case '.avi' :
		str += 'showcontrols=0 showstatusbar=0 autostart=true type=application/x-mplayer2 pluginspage=http://www.microsoft.com/isapi/redir.dll?prd=windows&sbp=mediaplayer&ar=media&sba=plugin>';
		break;
		case '.wmv' :
		str += 'showcontrols=0 showstatusbar=0 autostart=true type=application/x-mplayer2 pluginspage=http://www.microsoft.com/isapi/redir.dll?prd=windows&sbp=mediaplayer&ar=media&sba=plugin>';
		break;
		case '.mpeg' :
		str += 'showcontrols=0 showstatusbar=0 autostart=true type=application/x-mplayer2 pluginspage=http://www.microsoft.com/isapi/redir.dll?prd=windows&sbp=mediaplayer&ar=media&sba=plugin>';
		break;
		case '.swf' :
		str += 'type=application/x-shockwave-flash pluginspage=http://www.macromedia.com/go/getflashplayer>';
		break;
		case '.dwf' :
		str += 'type=application/x-dwf pluginspage=http://www.autodesk.com/products/whip>';	//type=drawing/x-dwf
		break;
		case '.rm' :
		str += 'controls=controlpanel autostart=true type=audio/x-pn-realaudio-plugin>';
		break;
		case '.mov' :
		str += 'type=video/quicktime pluginspage=http://www.apple.com/quicktime/download/>';
		break;
		//default:
		//alert('不支持的媒体文件格式！');
		//break;
	}
	//alert(str);
	return str;
}

function UpdatePreview()
{
	var oCell = GetE('ePreviewCell') ;
	
	while ( oCell.firstChild )
		oCell.removeChild( oCell.firstChild ) ;

	if ( GetE('txtUrl').value.length == 0 )
		oCell.innerHTML = '&nbsp;' ;
	else
	{
		oCell.innerHTML = GetOuterHtml();
	}
}

function BrowseServer()
{
	OpenServerBrowser(
		'Media',
		FCKConfig.MediaBrowserURL,
		FCKConfig.MediaBrowserWindowWidth,
		FCKConfig.MediaBrowserWindowHeight ) ;
}

function OpenServerBrowser( type, url, width, height )
{
	var iLeft = (screen.width  - width) / 2 ;
	var iTop  = (screen.height - height) / 2 ;

	var sOptions = "toolbar=no,status=no,resizable=yes,dependent=yes" ;
	sOptions += ",width=" + width ;
	sOptions += ",height=" + height ;
	sOptions += ",left=" + iLeft ;
	sOptions += ",top=" + iTop ;

	var oWindow = window.open( url, "FCKBrowseWindow", sOptions ) ;
}

function SetUrl( url )
{
	GetE('txtUrl').value = url ;
	UpdatePreview() ;
	GetE('loadpic').style.display = "none";
	window.parent.SetSelectedTab( 'Info' ) ;
	
}

function OnUploadCompleted( errorNumber, fileUrl, fileName, customMsg )
{
	GetE('loadpic').style.display = "none";
	switch ( errorNumber )
	{
		case 0 :	// No errors
			alert( '上传成功！' ) ;
			break ;
		case 1 :	// Custom error
			alert( customMsg ) ;
			return ;
		case 101 :	// Custom warning
			alert( customMsg ) ;
			break ;
		case 201 :
			alert( 'A file with the same name is already available. The uploaded file has been renamed to "' + fileName + '"' ) ;
			break ;
		case 202 :
			alert( '不允许的格式！' ) ;
			return ;
		case 203 :
			alert( "Security error. You probably don't have enough permissions to upload. Please check your server." ) ;
			return ;
		default :
			alert( 'Error on file upload. Error number: ' + errorNumber ) ;
			return ;
	}
	SetUrl( fileUrl ) ;
	GetE('frmUpload').reset() ;
}

var oUploadAllowedExtRegex	= new RegExp( FCKConfig.MediaUploadAllowedExtensions, 'i' ) ;
var oUploadDeniedExtRegex	= new RegExp( FCKConfig.MediaUploadDeniedExtensions, 'i' ) ;

function CheckUpload()
{
	GetE('loadpic').style.display = "";
	var sFile = GetE('txtUploadFile').value ;
	
	if ( sFile.length == 0 )
	{
		alert( '请选择要上传的文件！' ) ;
		return false ;
	}
	GetE('FileExtName').value = sFile.substr(sFile.lastIndexOf('.'));
	if ( ( FCKConfig.MediaUploadAllowedExtensions.length > 0 && !oUploadAllowedExtRegex.test( sFile ) ) || ( FCKConfig.MediaUploadDeniedExtensions.length > 0 && oUploadDeniedExtRegex.test( sFile ) ) )
	{
		OnUploadCompleted( 202 ) ;
		return false ;
	}

	return true ;
}
