var tabArr = 0;
var careers_tab_current = 0;
var isOpen = [false, false, false];

/* Moo Tools */
/*	initialize	*/


var clientBrowser = {
	
	browser : navigator.appName,
	version : function() {
		var ver = navigator.appVersion;
		return parseFloat(ver);
	}
	
};

var hostLoc = (window.location.hostname).toLowerCase();

if (hostLoc.indexOf('brassring')<0) {

	window.addEvent('domready', function(){
			initPage.delay(300);
	});	
	
	
	/*	Popup	*/
	var isInited = false;
	
	function initPage(){
		
		isInited = true;
			if ((navigator.appName).indexOf("Opera")>-1) {
				
				if($$('img.popTool').length>0) {
					new Tips($$('img.popTool'), {
						showDelay: 250					
					});
				};
				
				if($$('a.popTool').length>0) {
					new Tips($$('a.popTool'), {
						showDelay: 250					
					});
					
				};
				
			}
			else if ((navigator.appName).indexOf("Microsoft Internet Explorer")>-1) {
				
				if($$('img.popTool').length>0) {
					new Tips($$('img.popTool'), {
						showDelay: 250,
						offsets: {'x': -240, 'y': -55}
					});
				};
				
				if($$('a.popTool').length>0) {
					new Tips($$('a.popTool'), {
						showDelay: 250,
						offsets: {'x': -240, 'y': -55}
					});
					
				};
				
			}
			else {
			
				if($$('img.popTool').length>0) {
					new Tips($$('img.popTool'), {
						showDelay: 250,
						offsets: {'x': 15, 'y': -55}
					});
				};
				
				if($$('a.popTool').length>0) {
					
					new Tips($$('a.popTool'), {
						showDelay: 250,
						offsets: {'x': 15, 'y': -55}
					});
					
				};
				
			}
			
			new SmoothScroll();
	};

};

/** **/
function showPolicy(policy) {
	try {
		document.getElementById('english').style.display = ((policy=='europe')?'block':'none');
		document.getElementById('chinese').style.display = ((policy=='asiapac')?'block':'none');
		document.getElementById('german').style.display = ((policy=='german')?'block':'none');
		document.getElementById('french').style.display = ((policy=='french')?'block':'none');
		document.getElementById('spanish').style.display = ((policy=='spanish')?'block':'none');
		document.getElementById('italian').style.display = ((policy=='italian')?'block':'none');
		document.getElementById('mexico').style.display = ((policy=='mexico')?'block':'none');
		document.getElementById('mexicospanish').style.display = ((policy=='mexicospanish')?'block':'none');
	}
	catch(e) {};
}

function change_dd_selection(isload) {

	var val = document.getElementById('selection_main').value;
	
	for (var x = 1; x <=4; x++) {
		
		var v = (x == val)?'block':'none';
		document.getElementById('div_selection_' + x).style.width = '149px';
		document.getElementById('div_selection_' + x).style.display = v;
		try {
			document.getElementById('div_selection_' + x + '_intro').style.display = v;
		}
		catch(e) { };
	}

	document.getElementById('selectionVar').value = document.getElementById('sub_' + val).value;
	
	cp.DD_NUM = val;
	
}

function sub_selection_2_change(d) {

	alert(d.value);
	/*
	isOpen[cp.DD_NUM] = (isOpen[cp.DD_NUM])?false:true;
	
	//console.log(isOpen[cp.DD_NUM]);
	
	if (cp.CUR_VAL == null) {
		cp.CUR_VAL = d.value;
	}
	else {
		if (cp.CUR_VAL == d.value) {
			if (isOpen[cp.DD_NUM]) {
				document.getElementById('sub_' + cp.DD_NUM).style.width = '';
			}
		}
		else {
			cp.CUR_VAL = d.value;
		}
	}
	*/
	
	//console.log(document.getElementById('sub_' + cp.DD_NUM).value);

}

function sub_selection(d) {
		document.getElementById('selectionVar').value = d.value;
}

function dd_activate(id) {
	
}

function selected_dd() {
	var loc = document.getElementById('selectionVar').value;
	window.location = loc;
}

function careers_tab(n, arrow, inc, url) {
	
	if (careers_tab_current != n) {
		tabArr = 0;
	}
	
	var len = 0;
	var data = careers_tab_data;
	var tab = "";
	
	switch (n) {
		
		case 1: {
			tab = "Industry"; break;
		};
		
		case 2: {
			tab = "Interest"; break;
		};
		
		case 3: {
			tab = "Location"; break;
		};
		
		default: {
			tab = "Industry"; break;
			n = 1;
		};
		
	};
	
	careers_tab_current = n;
	
	len = data[tab].length;
	
	var rand;
	
	if (arrow) {
		
		if (tabArr + inc < 0) {
			tabArr = len-1;
		}
		else {
			tabArr = ((tabArr + inc) > (len-1))?0:tabArr + inc;
		}
		
		rand = tabArr;
		
	}
	else {
		tabArr = 0;
		rand = Math.floor((len-1)*Math.random());
	}
	
	tabArr = rand;
	
	var name = data[tab][rand]["name"];
	var img = data[tab][rand]["img"];
	var bsearch = data[tab][rand]["search"];
	
	bsearch = (bsearch == "")?"http://www.timewarner.com/corp/careers/jobtools_us/index.html":bsearch;
	
	
	//var str = '<a class="tab_title_text" href="' + bsearch + '">' + name + '</a><br />'
	//		+ '<div class="careers-hp_tabs_img"><img src="' + url + '/careers/hp_tabs_nav_' + n + '.png" border="0" /></div>';
	
	document.getElementById('content_' + n + '_link').innerHTML = '<a class="tab_title_text" href="' + bsearch + '">' + name + '</a>';
	document.getElementById('content_' + n + '_img').innerHTML = '<div class="careers-hp_tabs_img"><img width="239" height="185" src="' + url + '/careers/hp_tabs_nav_' + n + '.png" border="0" /></div>';
	
	document.getElementById('careers-hp_tab_nav_img').src = url + '/careers/hp_tabs_nav_' + n + '.jpg';
	//document.getElementById("content_" + n + "_text").innerHTML = str;
	
	for (var x = 1; x <=3; x++) {
		var disp = (x==n)?'block':'none';
		document.getElementById('careers-hp_tabs_content_' + x).style.display = disp;
	}
}

function brSearch(s) {
	
	var link = 'https://careers.timewarner.com/1033/asp/tg/cim_advsearch.asp?SID=' + s;
	document.getElementById("brassring").src = link;
	
}

function prevTabData() {
	
}

function AHL(c, ar) {
	var id = "arrow_" + ar + "_img";
	
	if (c) {
		document.getElementById(id).style.marginTop = "-60px";
	}
	else {
		document.getElementById(id).style.marginTop = "0px";
	}
	
	
	
}

function FeaturedRoles(c) {
	var fr = this;
	if (c == undefined)
		return;
		
	fr.roleArr = [];
	fr.count = c;
	
	fr.load();
	
};

FeaturedRoles.prototype.BUTTON_TEXT = '<a class="role_content_button" href="javascript:roleShowMore(@NUMBER);">@TEXT</a>';

FeaturedRoles.prototype.load = function() {
	var fr = this;
	
	var count = fr.count;
	
	for (var x = 1; x <= count; x++) {

		/*
		var disp = (x == 1)?'inline':'none';
		var dots = (x == 1)?'none':'inline';
		*/
		
		var disp = 'none';
		var dots = 'inline';
		
		/* reset dots */
		document.getElementById('role_content_' + x + '_dots').style.display = dots;
		/* more content */
		document.getElementById('role_content_' + x + '_more').style.display = disp;
		

		
		/* more/less */
		//fr.roleArr[x] = (x == 1)?"more":"less";
		fr.roleArr[x] = "less";
		
		var str = fr.BUTTON_TEXT;
		//var bText = (x == 1)?'Less':'More';
		var bText = 'More';
		
		str = str.replace(/@NUMBER/, x);
		str = str.replace(/@TEXT/, bText);
		
		document.getElementById('role_content_' + x + '_button').innerHTML = str;
		
	};
	
};

FeaturedRoles.prototype.showHideMode = function(x) {
	
	var fr = this;
	
	var d = fr.roleArr[x];
	
	var disp = (d == 'more')?'none':'inline';
	var dots = (d == 'more')?'inline':'none';
	
	/* reset dots */
	document.getElementById('role_content_' + x + '_dots').style.display = dots;
	/* more content */
	document.getElementById('role_content_' + x + '_more').style.display = disp;
	

	
	/* more/less */
	var str = fr.BUTTON_TEXT;
	var bText = (d == 'more')?'More':'Less';
	
	str = str.replace(/@NUMBER/, x);
	str = str.replace(/@TEXT/, bText);
	
	document.getElementById('role_content_' + x + '_button').innerHTML = str;
	
	fr.roleArr[x] = (d == 'more')?'less':'more';
};

function roleShowMore(n) {
	fr.showHideMode(n);
};

var careers_tab_data = {
	
	"Props" : {
		
		"defaultImg" : "hp_tabs_default.jpg"
		
	},
	
	"Industry" : [
		
		{
			"name" : "Advertising",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5373591",
			"img" : ""
		},
		
		{
			"name" : "Cable/Broadcast Television Networks",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5373596",
			"img" : ""
		},

		{
			"name" : "Corporate Media",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5373600",
			"img" : ""
		},
		
		{
			"name" : "Film Production and Distribution",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5373601",
			"img" : ""
		},
		
		{
			"name" : "Online Content/Services",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5373605",
			"img" : ""
		},
		
		{
			"name" : "Publishing",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5373606",
			"img" : ""
		},
	
		{
			"name" : "Television Program Production and Distribution",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5373607",
			"img" : ""
		},
	
		{
			"name" : "Search across all Industries",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_advsearch.asp?partnerid=391&siteid=36",
			"img" : ""
		}
	
	],
	
	"Interest" : [
		
		{
			"name" : "Administrative",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5373944",
			"img" : ""
		},
		
		{
			"name" : "Advertising/Public Relations",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5373947",
			"img" : ""
		},
		
		{
			"name" : "Business Affairs/Development/Operations",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5373950",
			"img" : ""
		},
		
		{
			"name" : "Communications",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5373953",
			"img" : ""
		},
		
		{
			"name" : "Construction/Facilities/Security",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5373955",
			"img" : ""
		},
		
		{
			"name" : "Creative",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5373957",
			"img" : ""
		},
		
		{
			"name" : "Customer Service",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5373959",
			"img" : ""
		},
		
		{
			"name" : "Digital",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5373962",
			"img" : ""
		},
		
		{
			"name" : "Editorial",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5373968",
			"img" : ""
		},
		
		{
			"name" : "Finance",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5373969",
			"img" : ""
		},
		
		{
			"name" : "Human Resources",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5374054",
			"img" : ""
		},
		
		{
			"name" : "International",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5374057",
			"img" : ""
		},
		
		{
			"name" : "Internship/Trainee",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5374059",
			"img" : ""
		},
		
		{
			"name" : "IT/Technology",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5374063",
			"img" : ""
		},
		
		{
			"name" : "Legal",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5374064",
			"img" : ""
		},
		
		{
			"name" : "Management",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5374067",
			"img" : ""
		},
		
		{
			"name" : "Marketing/Sales",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5374070",
			"img" : ""
		},
		
		{
			"name" : "Other",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5374072",
			"img" : ""
		},
		
		{
			"name" : "Programming/Production",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5374074",
			"img" : ""
		},
		
		{
			"name" : "Project/Program Management",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5374078",
			"img" : ""
		},
		
		{
			"name" : "Publishing",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5374127",
			"img" : ""
		},
		
		{
			"name" : "Purchasing/Retail",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5374129",
			"img" : ""
		},
		
		{
			"name" : "Research",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5374132",
			"img" : ""
		},
		
		{
			"name" : "Search across all areas of Interest",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_advsearch.asp?partnerid=391&siteid=36",
			"img" : ""
		}
		
	],
	
	"Location" : [
		
		{
			"name" : "Atlanta Area",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5374135",
			"img" : ""
		},
		
		{
			"name" : "London (UK) Area",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5374141",
			"img" : ""
		},
		
		{
			"name" : "Los Angeles Area",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5374143",
			"img" : ""
		},
		
		{
			"name" : "New York Metro Area",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5374146",
			"img" : ""
		},
		
		{
			"name" : "Washington, DC Area",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_searchresults.asp?partnerid=391&amp;siteid=36&amp;function=runquery&amp;agentid=5374147",
			"img" : ""
		},
		
		{
			"name" : "Search across all Cities",
			"search" : "https://careers.timewarner.com/1033/asp/tg/cim_advsearch.asp?partnerid=391&siteid=36",
			"img" : ""
		}
		
	]				 
	
};