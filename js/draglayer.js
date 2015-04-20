/**************************************************************
                     Dragable layer creator
                By Mark Wilton-Jones 14/10/2002
v1.0.2 updated 21/07/2005 for mouseup-over-chrome click-release
***************************************************************

Please see http://www.howtocreate.co.uk/jslibs/ for details and a demo of this script
Please see http://www.howtocreate.co.uk/jslibs/termsOfUse.html for terms of use

To use:

Inbetween the <head> tags, put:

	<script src="PATH TO SCRIPT/draglayer.js" type="text/javascript" language="javascript1.2"></script>

To create a dragable layer put:

	<script type="text/javascript" language="javascript1.2"><!--
		createDragableLayer(
			'This layer is dragable', //contents of dragable layer (can contain HTML etc.)
			10,                       //left coordinate of dragable layer
			100,                      //top coordinate of dragable layer
			150,                      //width of dragable layer
			30,                       //optional: height of dragable layer (use null for default)
			'#ff0000'                 //optional: background colour of dragable layer (use null for default)
		);
	//--></script>
___________________________________________________________________________________________*/

function createDragableLayer(layerContent,leftPos,topPos,layerWidth,layerHeight,layerBG) {
	if( document.layers ) {
		document.write( '<layer left="'+leftPos+'" top="'+topPos+'" width="'+layerWidth+'" '+(layerHeight?('height="'+layerHeight+'" '):'')+(layerBG?('bgcolor="'+layerBG+'" '):'')+'onmouseover="this.captureEvents(Event.MOUSEDOWN);this.onmousedown=dragIsDown;">'+layerContent+'</layer>' );
	} else {
		document.write( '<div style="position:absolute;left:'+leftPos+'px;top:'+topPos+'px;width:'+layerWidth+'px;'+(layerHeight?('height:'+layerHeight+'px;'):'')+(layerBG?('background-color:'+layerBG+';'):'')+'" onmouseover="this.onmousedown=dragIsDown;" ondragstart="return false;" onselectstart="return false;">'+layerContent+'</div>' );
	}
}

function dragMousePos(e) {
	//get the position of the mouse
	if( !e ) { e = window.event; } if( !e || ( typeof( e.pageX ) != 'number' && typeof( e.clientX ) != 'number' ) ) { return [0,0]; }
	if( typeof( e.pageX ) == 'number' ) { var xcoord = e.pageX; var ycoord = e.pageY; } else {
		var xcoord = e.clientX; var ycoord = e.clientY;
		if( !( ( window.navigator.userAgent.indexOf( 'Opera' ) + 1 ) || ( window.ScriptEngine && ScriptEngine().indexOf( 'InScript' ) + 1 ) || window.navigator.vendor == 'KDE' ) ) {
			if( document.documentElement && ( document.documentElement.scrollTop || document.documentElement.scrollLeft ) ) {
				xcoord += document.documentElement.scrollLeft; ycoord += document.documentElement.scrollTop;
			} else if( document.body && ( document.body.scrollTop || document.body.scrollLeft ) ) {
				xcoord += document.body.scrollLeft; ycoord += document.body.scrollTop; } } }
	return [xcoord,ycoord];
}

function dragIsDown(e) {
	//make note of starting positions and detect mouse movements
	if( ( e && ( e.which > 1 || e.button > 1 ) ) || ( window.event && ( window.event.which > 1 || window.event.button > 1 ) ) ) { return false; }
	if( document.onmouseup == dragIsMove ) { document.onmousemove = storeMOUSEMOVE; document.onmouseup = window.storeMOUSEUP; } //mouseup was over chrome
	window.msStartCoord = dragMousePos(e); window.lyStartCoord = this.style?[parseInt(this.style.left),parseInt(this.style.top)]:[parseInt(this.left),parseInt(this.top)];
	if( document.captureEvents && Event.MOUSEMOVE ) { document.captureEvents(Event.MOUSEMOVE); document.captureEvents(Event.MOUSEUP); }
	window.storeMOUSEMOVE = document.onmousemove; window.storeMOUSEUP = document.onmouseup; window.storeLayer = this;
	document.onmousemove = dragIsMove; document.onmouseup = dragIsMove; return false;
}

function dragIsMove(e) {
	//move the layer to its newest position
	var msMvCo = dragMousePos(e); if( !e ) { e = window.event ? window.event : ( new Object() ); }
	var newX = window.lyStartCoord[0] + ( msMvCo[0] - window.msStartCoord[0] );
	var newY = window.lyStartCoord[1] + ( msMvCo[1] - window.msStartCoord[1] );
	//reset the mouse monitoring as before - delay needed by Gecko to stop jerky response (hence two functions instead of one)
	//as long as the Gecko user does not release one layer then click on another within 1ms (!) this will cause no problems
	if( e.type && e.type.toLowerCase() == 'mouseup' ) { document.onmousemove = storeMOUSEMOVE; document.onmouseup = window.storeMOUSEUP; }
	if( navigator.product == 'Gecko' ) { window.setTimeout('dragIsMove2('+newX+','+newY+');',1); } else { dragIsMove2(newX,newY); }
}

function dragIsMove2(x,y) { var oPix = ( document.childNodes ? 'px' : 0 ), theLayer = ( window.storeLayer.style ? window.storeLayer.style : window.storeLayer ); theLayer.left = x + oPix; theLayer.top = y + oPix; }