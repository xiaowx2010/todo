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
 * File Name: fck_flash.js
 * 	Scripts related to the Flash dialog window (see fck_flash.html).
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

if ( FCKConfig.FlashUpload )
	window.parent.AddTab( 'Upload', FCKLang.DlgLnkUpload ) ;

if ( !FCKConfig.FlashDlgHideAdvanced )
	window.parent.AddTab( 'Advanced', oEditor.FCKLang.DlgAdvancedTag ) ;

// Function called when a dialog tag is selected.
function OnDialogTabChange( tabCode )
{
	ShowE('divInfo'		, ( tabCode == 'Info' ) ) ;
	ShowE('divUpload'	, ( tabCode == 'Upload' ) ) ;
	ShowE('divAdvanced'	, ( tabCode == 'Advanced' ) ) ;
}

// Get the selected flash embed (if available).
var oFlash = FCK.Selection.GetSelectedElement() ;

if ( oFlash && oFlash.tagName != 'EMBED' )
	oFlash = null ;

window.onload = function()
{
	// Translate the dialog box texts.
	oEditor.FCKLanguageManager.TranslatePage(document) ;

	// Load the selected element information (if any).
	LoadSelection() ;

	// Show/Hide the "Browse Server" button.
	GetE('tdBrowse').style.display = FCKConfig.FlashBrowser	? '' : 'none' ;

	// Set the actual uploader URL.
	if ( FCKConfig.FlashUpload )
		GetE('frmUpload').action = FCKConfig.FlashUploadURL ;

	window.parent.SetAutoSize( true ) ;

	// Activate the "OK" button.
	window.parent.SetOkButton( true ) ;
}

function LoadSelection()
{
	if ( ! oFlash ) return ;

	var sUrl = GetAttribute( oFlash, 'src', '' ) ;

	GetE('txtUrl').value    = GetAttribute( oFlash, 'src', '' ) ;
	GetE('txtWidth').value  = GetAttribute( oFlash, 'width', '' ) ;
	GetE('txtHeight').value = GetAttribute( oFlash, 'height', '' ) ;

	// Get Advances Attributes
	GetE('txtAttId').value		= oFlash.id ;
	GetE('chkAutoPlay').checked	= GetAttribute( oFlash, 'play', 'true' ) == 'true' ;
	GetE('chkLoop').checked		= GetAttribute( oFlash, 'loop', 'true' ) == 'true' ;
	GetE('chkMenu').checked		= GetAttribute( oFlash, 'menu', 'true' ) == 'true' ;
	GetE('cmbScale').value		= GetAttribute( oFlash, 'scale', 'showall' ).toLowerCase() ;
	
	GetE('txtAttTitle').value		= oFlash.title ;
	GetE('txtAttClasses').value		= oFlash.getAttribute('class',2) || '' ;

	if ( oEditor.FCKBrowserInfo.IsIE )
		GetE('txtAttStyle').value	= oFlash.style.cssText ;
	else
		GetE('txtAttStyle').value	= oFlash.getAttribute('style',2) ;

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
	
	if ( !oFlash )
	{
		oFlash	= FCK.EditorDocument.createElement( 'EMBED' ) ;
		oFlash	= FCK.InsertElementAndGetIt( oFlash ) ;
	}
	else
		oEditor.FCKUndo.SaveUndoStep() ;
	
	UpdateEmbed( oFlash ) ;

	FCK.SetHTML( FCK.GetHTML(), true ) ;
	return true ;
}

function UpdateEmbed( e )
{
	//alert("wwwwwwwwwww");
	SetAttribute( e, 'type'			, 'application/x-shockwave-flash' ) ;
	SetAttribute( e, 'pluginspage'	, 'http://www.macromedia.com/go/getflashplayer' ) ;

	e.src = GetE('txtUrl').value ;
	SetAttribute( e, "width" , GetE('txtWidth').value ) ;
	SetAttribute( e, "height", GetE('txtHeight').value ) ;
	
	// Advances Attributes

	SetAttribute( e, 'id'	, GetE('txtAttId').value ) ;
	SetAttribute( e, 'scale', GetE('cmbScale').value ) ;

	if (!(GetE('chkAutoPlay').checked))	SetAttribute( e, 'play', 'false' ) ;
	if ( !GetE('chkLoop').checked )		SetAttribute( e, 'loop', 'false' ) ;
	if ( !GetE('chkMenu').checked )		SetAttribute( e, 'menu', 'false' ) ;

	SetAttribute( e, 'title'	, GetE('txtAttTitle').value ) ;
	SetAttribute( e, 'class'	, GetE('txtAttClasses').value ) ;

	if ( oEditor.FCKBrowserInfo.IsIE )
		e.style.cssText = GetE('txtAttStyle').value ;
	else
		SetAttribute( e, 'style', GetE('txtAttStyle').value ) ;/**/
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
		//var e = document.createElement( 'EMBED' ) ;
		
		//e.src		= GetE('txtUrl').value ;
		//e.type		= 'application/x-shockwave-flash' ;
		//e.width		= '100%' ;
		//e.height	= '100%' ;
		
		//oCell.appendChild( e ) ;
		oCell.innerHTML = '<embed id=ePreview src='+GetE('txtUrl').value+' width=100% height=100% type=application/x-shockwave-flash pluginspage=http://www.macromedia.com/go/getflashplayer>';
	}
}

function BrowseServer()
{
	OpenServerBrowser(
		'Flash',
		FCKConfig.FlashBrowserURL,
		FCKConfig.FlashBrowserWindowWidth,
		FCKConfig.FlashBrowserWindowHeight ) ;
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

var oUploadAllowedExtRegex	= new RegExp( FCKConfig.FlashUploadAllowedExtensions, 'i' ) ;
var oUploadDeniedExtRegex	= new RegExp( FCKConfig.FlashUploadDeniedExtensions, 'i' ) ;

function CheckUpload()
{
	GetE('loadpic').style.display = "";
	var sFile = GetE('txtUploadFile').value ;
	
	if ( sFile.length == 0 )
	{
		alert( '请选择要上传的文件！' ) ;
		return false ;
	}

	if ( ( FCKConfig.FlashUploadAllowedExtensions.length > 0 && !oUploadAllowedExtRegex.test( sFile ) ) || ( FCKConfig.FlashUploadDeniedExtensions.length > 0 && oUploadDeniedExtRegex.test( sFile ) ) )
	{
		OnUploadCompleted( 202 ) ;
		return false ;
	}

	return true ;
}
