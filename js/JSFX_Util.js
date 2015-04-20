/******************************************************************* 
* 
* File    : JSFX_Util.js © JavaScript-FX.com
* 
* Created : 2001/03/16 
* 
* Author  : Roy Whittle www.Roy.Whittle.com 
* 
* Purpose : Various utilities for the site.
*
* History 
* Date         Version        Description 
* 
* 2000-06-08	1.0		Initial version 
***********************************************************************/ 

if(!window.JSFX)
	JSFX=new Object();
JSFX.nw = null;

JSFX.popupDemo = function(url, scrollBars)
{
	var extra = scrollBars ? ",scrollbars" : ""

	if(JSFX.nw)
		if(!JSFX.nw.closed && scrollBars)
			JSFX.nw.close();

	JSFX.nw = window.open(url,"Demo","width=600,height=400,status"+extra);
	JSFX.nw.moveTo(0,0);
	JSFX.nw.focus();
}
JSFX.popupDownload = function(url)
{
	var nw = window.open(url,"Download","width=300,height=200,status");
	nw.moveTo(300,100);
	nw.focus();
}
JSFX.blurLinks = function()
{
	if(document.all)
		for(i=0 ; i<document.links.length ; i++)
			document.links[i].onfocus=function(){this.blur();}
}
