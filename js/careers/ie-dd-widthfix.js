YAHOO.namespace( 'YAHOO.Hack' ).FixIESelectWidth = new function()
{
	var oSelf = this; 
	var YUE = YAHOO.util.Event;
	var YUD = YAHOO.util.Dom;
	var oTimer = {};
	var oAnim = {};
	var nTimerId =  0 ;
	var dLastFocalItem;
	
	var fTrue = true;
	var isOpen = false;
	
	var ie7 = !!(document.uniqueID  &&   typeof(XMLHttpRequest)!='undefined' )
	function init(el)
	{
		
		
		el = el || this;
		
		

		if( el.tagName.toLowerCase() != 'select')
		{
			throw Error('element [' + el.id + '] is not <select>');
			return;
		};	
		
		if(!YUD.hasClass( el.parentNode, 'select-box'))
		{
			throw Error('className select-box is not included for element [' + el.id + ']');
			return;
		};	
		
		var oRs = el.runtimeStyle;
		var oPRs = el.parentNode.runtimeStyle;
		
		
		oPRs.fonSize = 0;
		
		
		var sDisplay = el.parentNode.currentStyle.display.toLowerCase() ;
		if(  sDisplay=='' ||  sDisplay=='inline' ||  sDisplay=='inline-block' )
		{
			oPRs.display = 'block';
			oPRs.width = el.offsetWidth + 'px';
			oPRs.height =el.offsetHeight + 'px';
			oPRs.position = 'relative';
			oRs.position = 'absolute';
			oRs.top = 0;
			oRs.left = 0;
		};
		
		
		
		el._timerId = ( nTimerId+=1 );

		el.selectedIndex = Math.max( 0 , el.selectedIndex );
		
		oTimer[ '_' + el._timerId ] = setTimeout('void(0)',0);
		oAnim [ 'A' + el._timerId ] = setTimeout('void(0)',0);
		
		//YUE.on( el, 'mouseup' , onMouseUp);
		YUE.on( el, 'mousedown' , onMouseOver);
		YUE.on( document, 'mousedown' ,onMouseDown , el, true);
		YUE.on( el, 'change' ,collapseSelect , el, true);

		
	}
	
	function collapseSelect(e)
	{
		//status++;
		this.runtimeStyle.width = '';			
	}

	function onMouseOver(e )
	{
		var el = this;	
		
		if(dLastFocalItem && dLastFocalItem !=el)
		{
			 onMouseDown.call( dLastFocalItem , e );
		};

		var sTimerId ='_' +  el._timerId ;
		var sAniId = 'A' + el._timerId ;
		clearTimeout( oTimer[ sTimerId ] );

		var nStartWidth =  el.offsetWidth ;
		el.runtimeStyle.width = 'auto';
		var nEndWidth  = el.offsetWidth;
		

		clearTimeout( oAnim [  sAniId  ] );
		//onTween();

		el.focus();
		dLastFocalItem = el;
		
		
				
	}

	function onMouseDown(e , el )
	{
		el = ( e.srcElement || e.target );
		
		
		/* hax this!!!! */
		if (el == this) {
			isOpen = (isOpen)?false:true;
			fTrue = (fTrue)?isOpen:false;
		}
		
		if( el == this && e.type!='mouseover' )
		{
			//alert(w);
			try {
				w = '';
				for (var i in el)
					w += i + '=' + el[i] + '<br />';
					
			 	//document.getElementById('console').innerHTML = w;
				//window.status = el.selectedIndex;
			}
			catch(e)
			{
				//nothing
			}
			//status++;
			
			/* hax this!!!! */
			if (!fTrue) {
				
				if (!ie7) {
					
					
					//el.runtimeStyle.width = (!isOpen)?'':el.runtimeStyle.width;
					
					var doEet = function() { el.runtimeStyle.width = (!isOpen)?'':el.runtimeStyle.width; }
					
					setTimeout(doEet, 500);
					
					
				}
				else {
					var doEet = function() { el.runtimeStyle.width = (!isOpen)?'':el.runtimeStyle.width; }
					
					setTimeout(doEet, 500);
					
					
					
				}
				
			}
			
			YUE.stopEvent(e);
			return false;
			
		};
		
		
		
		el = this;
		
		clearTimeout( oAnim [ 'A' + el._timerId ] );
		
		
		var sTimerId ='_' +  el._timerId ;
		var doItLater = function()
		{
			el.runtimeStyle.width = '';			
		};
		
		if( e.type=='mouseover')
		{ 
			doItLater();
		}
		else{
			
			oTimer[ sTimerId ] = setTimeout(doItLater,500);
		}
		
	}

	

	function constructor(sId)
	{
		sId = [ sId , ''].join('');
		//Only fix for IE55 ~ IE7
		
		if(document.uniqueID && window.createPopup )
		{			
			YUE.onAvailable(sId ,init );
			return true;

		}else{return false};
	};

	return  constructor;
}
