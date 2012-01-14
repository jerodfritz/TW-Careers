jQuery(function($) {
	
	$('body').addClass('hasJS'); // Add hasJS class on load for deeper manipulation

        



});

function resize() {
		if($(window).height() <= 600) {
		//alert('small');
		$("#flashcontent").css("height","700px");
		}	
		else {
		//alert('large');
		var windowHeight =  $(window).height();
		height = windowHeight - $("#sticky-footer").height();
		$("#flashcontent, #slider-code, .viewport, .carousel-overview, #home #slider-code .carousel-overview li").css("height", height+"px");
		}
}


// Adding transparent mode to Flash object.
var flashFix = {
    fix:function() {
        $('.module .media object').each(function(){
            $(this).wrapAll('<div class="flash-mod"></div>').attr('wmode','transparent');
            $(this).attr('width', '620');
            $(this).children('embed').attr('width', '620');
        });
    }
};
flashFix.fix();

(function(jQuery) {
	jQuery.fn.clickoutside = function(callback) {
		var outside = 1, self = $(this);
		self.cb = callback;
		this.click(function() { 
			outside = 0; 
		});
		$(document).click(function() { 
			outside && self.cb();
			outside = 1;
		});
		return $(this);
	}
})(jQuery);


/**
* Main Nav functionality across the website
*/
var mainNav = {
	init:function() {
		$('#main-nav .vr-line').each(function(){
			$(this).height($(this).parent().height());
		});
		
		var navLI = $('#main-nav .main-content > li');
		
		navLI.mouseover(function () {
			if (!$(this).children('a.main-link').hasClass('current') && !$(this).children('a.main-link').hasClass('thispage')) {
				$.each($('#main-nav .main-content li > a.current'), function() {
					if (!$(this).hasClass('thispage')) {
						$(this).removeClass('current');
					} else {
						$(this).siblings('.dropdown-holder.current').addClass('hidden');
					}
				});
				
				$(this).children('a.main-link').addClass('current');
				$(this).children('a.main-link').siblings('.dropdown-holder').removeClass('hidden');
				$('#dropdown-holder-bg').removeClass('hidden');
			}
		});

		navLI.mouseout(function (event) {
			if ($(this).children('a.main-link').hasClass('current') && !$(this).children('a.main-link').hasClass('thispage')) {
				$(this).children('a.main-link').removeClass('current');
				$(this).children('a.main-link').siblings('.dropdown-holder').addClass('hidden');
				if (!$('#main-nav .main-content li > a.main-link').hasClass('thispage')) {$('#dropdown-holder-bg').addClass('hidden');}
				$('#main-nav .main-content li > a.thispage').siblings('.dropdown-holder').removeClass('hidden');
			}
		});

        var navLIsec = $('#main-nav ul.main-content .dropdown-holder > ul > li');

		navLIsec.mouseenter(function () {
			if ($(this).children('.dropdown-holder').length>0 && !$(this).hasClass('current')) {
				$(this).addClass('current');
				$(this).children('a').addClass('current');
				$(this).children('.dropdown-holder').removeClass('hidden');
			}
			
		});

		navLIsec.mouseleave(function () {
			if ($(this).children('.dropdown-holder').length>0 && $(this).hasClass('current')) {
				$(this).removeClass('current');
				$(this).children('a').removeClass('current');
				$(this).children('.dropdown-holder').addClass('hidden');
			}
		});

        var navLIsecBlack = $('#secondary-nav ul.secondary-nav li');

		navLIsecBlack.mouseenter(function () {
			if ($(this).children('.ancilary-nav').length>0 /*&& !$(this).hasClass('current')*/) {
/*				$(this).addClass('current');*/
/*				$(this).children('a').addClass('current');*/
				$(this).children('.ancilary-nav').removeClass('hidden');
			}
			
		});

		navLIsecBlack.mouseleave(function () {
			if ($(this).children('.ancilary-nav').length>0/* && $(this).hasClass('current')*/) {
/*				$(this).removeClass('current');*/
/*				$(this).children('a').removeClass('current');*/
				$(this).children('.ancilary-nav').addClass('hidden');
			}
		});

		/*var exploreFooter = $('#explore');// > ul.main-content');
		exploreFooter.toggle(function() {
			$('#explore-arrow').rotateAnimation(180);
			$('#explore').animate({
				top: '-261px'
			}, 400);
		},function() {
			$('#explore-arrow').rotateAnimation(0);
			$('#explore').animate({
				top: '1px'
			}, 400);
		});*/
		
		$('#main-nav .dropdown ul li a').live('click', function () {
			$('#main-nav>ul>li').removeClass('current');
			$('#main-nav .dropdown-holder').addClass('hidden');
		});	
		
	}
};
mainNav.init();

var exploreFooter = {
	init:function() {
		$('#explore #explore-content > ul > li a').bind('click',function(event){
			//console.log('binding to '+event.currentTarget);
			$('#explore-arrow').rotateAnimation(180);
			$('#explore').animate({
				top: '-261px'
			}, 400);
                        $('#explore-container').animate({'padding-top' : '260px' , 'margin-top' : '0'});
			exploreFooter.clickOutside();
			return false;
		});
		
	},
	
	clickOutside:function() {
		$('#explore #explore-content > ul > li a').unbind("click");
		$('#explore #explore-content > ul > li h3 a').bind("click", function(event){
			//console.log('bound click on this to close the window: '+event.currentTarget);
			$('#explore-arrow').rotateAnimation(0);
			$('#explore').animate({
				top: '0px'
			}, 400);
			exploreFooter.init();
			return false;
		});
		/*$('#explore #explore-content > ul > li h4 a').bind("click", function(event){
			console.log(event.currentTarget);
			//$('#explore-arrow').rotateAnimation(0);
			$('#explore').animate({
				top: '1px'
			}, 400);
			exploreFooter.init();
			return false;
		});*/
		$('#explore #explore-content li#explore-arrow-holder a').bind("click", function(event){
			//console.log(event.currentTarget);
			$('#explore-arrow').rotateAnimation(0);
			$('#explore').animate({
				'top': '0px'
			}, 400);
			exploreFooter.init();
                        $('#explore-container').animate({'padding-top' : '0' , 'margin-top' : '260px'});
			return false;
		});
		$('#explore-content').bind("clickoutside", function(event){
			//console.log(event.currentTarget);
			$('#explore-arrow').rotateAnimation(0);
			$('#explore').animate({
				top: '0px'
			}, 400);
			exploreFooter.init();
			return false;
		});
		
	}
};
exploreFooter.init();



/**
* Main Nav functionality across the website
*/
var listToggle = {
	init:function() {
		
		var listLI = $('#content ul.toggle-list li, #content ol.toggle-list li');
		
		listLI.live('click', function () {
			//$(this).siblings().children('ul').addClass('offscreen'); -- uncomment if on click of li prev open li should close
			if ($(this).children('ul, ol').hasClass('offscreen')) {
				$(this).addClass('current');
				$(this).children('ul, ol').removeClass('offscreen');
			} else {
				$(this).removeClass('current');
				$(this).children('ul, ol').addClass('offscreen');
				$(this).children('ul, ol').children('li').children('ul, ol').addClass('offscreen'); // add class offscreen to level 2 nested lists when parent li is closed
			}
			return false;
		});
		
	}	
};
listToggle.init();



/**
* Pagination - Initializes pagination across the entire website
*/
var pagination = {
	init:function()
	{
            $('ul.pagination li.link-next a:not(.link)').click(function(){pagination.getPage($(this));return false;});
			$('li.page a').click(function(){pagination.getPage($(this));return false;});
            $('ul.pagination li.link-prev a:not(.link)').click(function(){pagination.getPage($(this));return false;});
            $('ul.footer-links li a.link-seeall:not(.link)').click(function(){pagination.getPage($(this));return false;});
    },
    getPage:function(anchor)
	{
	    var resultDiv = anchor.parents(".result-div");
	    // Showing and Hiding Div with ID of a href
	    var myHeight=resultDiv.height();
	    var myWidth=resultDiv.width();
	    var loadDivHTML = '<div class="loading" style="height:'+myHeight+'px; width:'+myWidth+'px;"></div>';

	    resultDiv.html(loadDivHTML);

	    $.ajax({
			url:anchor.attr("href"),
			success:function(result)
			{
			      resultDiv.html(result);
			      pagination.init();
			},
			error:function(XMLHttpRequest,textStatus,error)
			{
			      //handle error
			}
		  });
		return false;
	}
};





// Ajaxize
var ajaxCallSamples = {
	init: function() {
		// Simple ajax call
		$('.ajaxtrigger').click(function(){
			var url = $(this).attr('href');
			var resultDiv = $(this).attr('rel'); // ajax trigger has rel tag with unique 
			$('#target').load(url);
			return false;
		});
		
		// Ajax call with loading image
		var container = $('#target');
		$('.ajaxtrigger').click(function(){
			doAjax($(this).attr('href'));
			return false;
		});
		function doAjax(url){
			$.ajax({
			url: url,
			success: function(data){
				container.html(data);
			},
			beforeSend: function(data){
				container.html('<div class="ajax-loader"><img src="../imgs/icons/ajax-loader.gif" alt="Loading..." /></div>');
			}
			});
		}
		
		// Ajax call with loading image and error handling + hightligh effect on content change
		var container = $('#target');
		$('.ajaxtrigger').click(function(){
			doAjax($(this).attr('href'));
			return false;
		});
		function doAjax(url){
			if(url.match('^http')){
				var errormsg = 'Ajax cannot load external content';
				container.html(errormsg).effect('highlight',{color:'#ff9966'}, 1000); // highlight effect to show error (fades in/out in 1 second)
			} else {
			$.ajax({
				url: url,
				timeout:5000,
				success: function(data){
		 			container.html(data).effect("highlight",{color:'#ffffcc'},1000); // highlight effect to show sucess (fades in/out in 1 second)
				},
				error: function(req,error){
					if(error === 'error'){error = req.statusText;}
		  				var errormsg = 'There was a communication error: '+error;
		  				container.html(errormsg).effect('highlight',{color:'#ff9966'},1000); // highlight effect to show error (fades in/out in 1 second)
				},
				beforeSend: function(data){
		 			container.html('<div class="ajax-loader"><img src="../imgs/icons/ajax-loader.gif" alt="Loading..." /></div>');
				}
			});
			}
		}
		
		
	}
};


// Image Gallery

// Content Slider

// Carousel

// Accordion

// Table Sorter

// Global Popup

// Global Alert

// Datepicker


/** 
* Custom Tooltip - Initialization/Extension of custom-tooltip.js
*/
var customTooltip = {
	init: function() {
		var tooltipTrigger = $('a.tooltip, span.tooltip, p.tooltip');
		
		tooltipTrigger.mouseover(function () {
			var title = $(this).attr('title');
			tooltip.show(title);
			$(this).attr('title' , '');
		});
		
		tooltipTrigger.mouseout(function() {
			var title = $('#tt #ttcont').html();
			tooltip.hide();
			$(this).attr('title', title);
		})
	}
};
customTooltip.init();


(function($){
 $.fn.extend({
 
 	customStyle : function(options) {
	  if(!$.browser.msie || ($.browser.msie&&$.browser.version>6)){
	  return this.each(function() {
	  
			var currentSelected = $(this).find(':selected');
			$(this).after('<span class="customStyleSelectBox"><span class="customStyleSelectBoxInner">'+currentSelected.text()+'</span></span>').css({position:'absolute', opacity:0,fontSize:$(this).next().css('font-size')});
			var selectBoxSpan = $(this).next();
			var selectBoxWidth = parseInt($(this).width()) - parseInt(selectBoxSpan.css('padding-left')) -parseInt(selectBoxSpan.css('padding-right')) + 6;
			var selectBoxSpanInner = selectBoxSpan.find(':first-child');
			if ($(this).attr('id') == 'company-dropdown') {
				selectBoxWidth = 222 - (parseInt(selectBoxSpan.css('padding-left')) +parseInt(selectBoxSpan.css('padding-right'))) + 6;
			}
			$(this).css({width:(selectBoxWidth+5)});
			selectBoxSpan.css({display:'inline-block'});
			selectBoxSpanInner.css({width:selectBoxWidth, display:'inline-block'});
			var selectBoxHeight = parseInt(selectBoxSpan.height()) + parseInt(selectBoxSpan.css('padding-top')) + parseInt(selectBoxSpan.css('padding-bottom'));
			$(this).height(selectBoxHeight).change(function(){
				selectBoxSpanInner.text($(this).children(':selected').text()).parent().addClass('changed');
			});
			
	  });
	  }
	}
 });
})(jQuery);

$(document).ready(function(){
	$('select.custom-select').customStyle();
	//$('#breadcrumb a:last').css('color','#0079CD');
	/*$('.content-tools a.share').mouseover(function() {
		if (!$('div.share-dropdown').hasClass('mousedover')) {
			$(this).css('background-position','0 -21px');
			$('div.share-dropdown').slideDown('fast', function() {
				$('.content-tools a.share').addClass('mousedover');
			});
		}
	});
	$('.content-tools a.share').mouseout(function() {
		$('.content-tools a.share').removeClass('mousedover');
		var timeoutID = window.setTimeout(offShareMenu, 500);
	});
	$('div.share-dropdown').mouseenter(function() {
		$('div.share-dropdown').addClass('mousedover');
	});
	$('div.share-dropdown').mouseleave(function() {
		$('div.share-dropdown').removeClass('mousedover');
		var timeoutID2 = window.setTimeout(offShareDropdown, 500);
	});
	function offShareMenu() {
		if (!$('div.share-dropdown').hasClass('mousedover')) {
			$('div.share-dropdown').slideUp('fast', function() {
				$(this).removeClass('mousedover');
				$('.content-tools a.share').css('background-position','0 0');
			});
		}
	}
	function offShareDropdown() {
		if (!$('.content-tools a.share').hasClass('mousedover')) {
			$('div.share-dropdown').slideUp('fast', function() {
				$(this).removeClass('mousedover');
				$('.content-tools a.share').css('background-position','0 0');
			});
		}
	}*/
});
// End JS