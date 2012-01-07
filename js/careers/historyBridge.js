//---------------------------------------------------------------------------
//
//  historyBridge
//	v1.0
//
//  Created by: Bob Corporaal - reefscape.net on 23-09-2006.
//	Contact: bob@reefscape.net - http://reefscape.net
//
//
//	Description:
//	
//	Functions as a bridge between actionscript and the Unfocus history keeper.
//	This is the Javascript side of things
//
//	For more info about the unfocus history keeper see: 
//	http://www.unfocus.com/Projects/HistoryKeeper/
//
//	Changes:
//
//	v1		11-10-06
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

var HistoryBridge = function() {

	return {
		//
		//	init -  add the listener to unFocus.History
		//
		init : function()
		{
			unFocus.History.addEventListener('historyChange', HistoryBridge.historyListener);
		},
		//
		//	historyListener - create history listener
		//
		historyListener : function(historyHash)
		{
			FlashBridge.onHashChange(historyHash);
		},
		//
		//	getLocation - get the location
		//
		getLocation : function()
		{
		  return window.location.toString();
		},
		//
		//	setHash - function to set hash from Flash
		//
		setHash : function(str)
		{
			unFocus.History.addHistory(str);
		},
		//
		//	getHash - function to get the hash to Flash
		//
		getHash : function()
		{
			return unFocus.History.getCurrent();
		}
	}
}();

HistoryBridge.init();