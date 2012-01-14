   var showFlash = false;

   var plugin = (navigator.mimeTypes && navigator.mimeTypes["application/x-shockwave-flash"]) ? navigator.mimeTypes["application/x-shockwave-flash"].enabledPlugin : 0;



   // Non-IE Test for Flash

   if (plugin) {

      whichPlugin = parseInt(plugin.description.substring(plugin.description.indexOf(".")-1));

      showFlash = ( whichPlugin >= 8 ) ? true : false;

   }



   // Netscape Resize Fix   

   window.onload = resizefix

   function resizefix() {

	if( document.layers ) {

		winW = window.innerWidth

		winH = window.innerHeight

		window.onresize = restore

	}

    }

    function restore() {

	if( winW != window.innerWidth || winH != window.innerHeight ) {

		document.location.href = document.location.href

	}

    }

    function viewNonFlash( visible ) {

	var visibility = (visible)?"visible":"hidden";

	var display = (visible)?"block":"none";

	var oDiv = new getObj("noFlash")

	if( document.getElementById ) { // DOM3 = IE5, NS6 

		oDiv.style.visibility = visibility;

		oDiv.style.display= display;

	}

	else if( document.layers ) { // netscape 4 

		visibility = (visibility=="visible")?"show" : "hide";

		oDiv.obj.visibility = visibility;

		oDiv.obj.display= display;

	} 

	else if (document.all) { // IE 4 

		oDiv.style.visibility = visibility; 

		oDiv.style.display= display;

	} 

    }



    document.writeln('<script language="VBscript">');

    document.writeln('\n on error resume next');

    document.writeln('showFlash = IsObject(CreateObject("ShockwaveFlash.ShockwaveFlash.8"))');

    document.writeln('</scr' + 'ipt>');







function getObj(name){

 if (document.getElementById) {

   this.obj = document.getElementById(name);

   if ( this.obj == null ) {

	return;

   }

   this.style = this.obj.style;

  }

  else if (document.all){

   this.obj = document.all[name];

   this.style = this.obj.style;

  }

  else if (document.layers){

   this.obj = getObjNN4(document,name);

  this.style = this.obj;

 }

}



function getObjNN4(obj,name){

  var x = obj.layers;

  var foundLayer;

  for (var i=0;i<x.length;i++) {

  if (x[i].id == name)

     foundLayer = x[i];

   else if (x[i].layers.length)

     var tmp = getObjNN4(x[i],name);

   if (tmp) foundLayer = tmp;

  }

 return foundLayer;

}



function writeFlashEmbed(flashURL, flashWidth, flashHeight, flashBG, flashVersion, flashVars, objectID, wmode) { 

 if(!flashVersion) {

  flashVersion = '7,0,0,0';

 }



 if(!objectID) {

  objectID="";

 }



 strFlashEmbed = "";

 strFlashEmbed += "<OBJECT CLASSID=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=" + flashVersion + "\" WIDTH=\"" + flashWidth + "\"  HEIGHT=\"" + flashHeight + "\" id=\"" + objectID + "\">";

 strFlashEmbed += "<PARAM NAME=\"movie\" VALUE=\"" + flashURL + "\">";

 strFlashEmbed += "<PARAM NAME=\"quality\" VALUE=\"high\">";

 strFlashEmbed += "<PARAM NAME=\"bgcolor\" VALUE=\"#" + flashBG + "\">";

 strFlashEmbed += "<PARAM NAME=\"allowScriptAccess\" value=\"always\" />";

 if(wmode) {

   strFlashEmbed += "<PARAM NAME=\"wmode\" VALUE=\"" + wmode + "\">";

 }  

 strFlashEmbed += "<PARAM NAME=\"FlashVars\" value=\"" + flashVars + "\" >";

 strFlashEmbed += "<PARAM NAME=\"version\" VALUE=\"" + flashVersion + "\">";

 strFlashEmbed += "<EMBED SRC=\"" + flashURL + "\" QUALITY=\"high\" BGCOLOR=\"#" + flashBG + "\" WMODE=\"" + wmode + "\" WIDTH=\"" + flashWidth +

 				  "\" HEIGHT=\"" +  flashHeight + "\" allowScriptAccess=\"always\"" + " FlashVars=\"" + flashVars + "\" VERSION=\"" + flashVersion + "\"NAME=\"" + objectID +

 				  "\" TYPE=\"application/x-shockwave-flash\" PLUGINSPAGE=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\"></EMBED>";

 strFlashEmbed += "</OBJECT>";



 document.write(strFlashEmbed);

}