//---------------------------------------------------------------------------
//
//  flashBridge
//	v1.4
//
//  Created by: Bob Corporaal - reefscape.net on 23-09-2006.
//	Contact: bob@reefscape.net - http://reefscape.net
//
//
//	Description:
//	
//	Functions as a bridge between actionscript and the javascript.
//	Provides some general functionality
//
//	Changes:
//
//	v1.4	17-10-06
//	- changed registerFlashMovie. It was not working properly 
//	  with Internet Explorer
//
//	v1.3	4-10-06 by Steve Webster (www.dynamicflash.com)
//	- refactored code into a single global object.
//	- added ability to handle multiple Flash movies at once using the new
//	  registerFlashMovie method. This integrates with the new elementId property
//	  of the TextResizeBridge ActionScript class to allow Flash movies to 
//	  automatically register themselves.
//
//	v1.1	2-10-06
//	- added MIT license
//
//	v1		27-9-06
//	- initial release
//
//
//	MIT / X11 License
//
//	Copyright (c) 2006 Bob Corporaal - reefscape.net
//	
//	Permission is hereby granted, free of charge, to any person obtaining a copy of 
//	this software and associated documentation files (the "Software"), to deal in 
//	the Software without restriction, including without limitation the rights to 
//	use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
//	of the Software, and to permit persons to whom the Software is furnished to do 
//	so, subject to the following conditions:
//	
//	The above copyright notice and this permission notice shall be included in all 
//	copies or substantial portions of the Software.
//	
//	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
//	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
//	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
//	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
//	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
//	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE 
//	SOFTWARE.
//---------------------------------------------------------------------------
//
//
var FlashBridge = function() {
	var flashElements = [];
	
	return {
	
		registerFlashMovie : function(swfId) {

			if (!swfId) return;
			
			var flashElement = null;
			
			if (navigator.appName.indexOf("Microsoft") > -1) {
				flashElement = window[swfId];
			} else {
				flashElement = document[swfId];
			}

			flashElements[flashElements.length] = flashElement;
			
			return flashElement != null;
		},
		
		onFontResizeInit : function(baseSize)
		{
			for (var i = 0; i < flashElements.length; i++) {
				if (flashElements[i].onFontResizeInit) {
					flashElements[i].onFontResizeInit(baseSize);
				}
			}
		},
		
		onFontResize : function(fontSizes)
		{
			for (var i = 0; i < flashElements.length; i++) {
				if (flashElements[i].onFontResize) {
					flashElements[i].onFontResize(fontSizes);
				}
			}
		},
		
		onHashChange : function(newHash)
		{
			for (var i = 0; i < flashElements.length; i++) {
				if (flashElements[i].onHashChange) {
					flashElements[i].onHashChange(newHash);
				}
			}
		},
		
		setElementWidth : function(elementId, width)
		{
			return FlashBridge.setElementSize(elementId, width, null);
		},
		
		setElementHeight : function(elementId, height)
		{
			return FlashBridge.setElementSize(elementId, null, height);
		},
		
		setElementSize : function(elementId, width, height)
		{
			if (!document.getElementById) return false;
			
			var element = document.getElementById(elementId);
			
			if (!element) return false;
			
			if (!isNaN(+width)) {
				element.style.width = width + "px";
			}
			
			if (!isNaN(+height)) {
				element.style.height = height + "px";
			}
			
			return true;
		},
		//
		//	setDocumentTitle - to set document title
		//
		setDocumentTitle : function(str)
		{
			document.title = str;
		}
	}
}();