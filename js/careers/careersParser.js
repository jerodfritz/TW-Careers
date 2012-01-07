function CareersParser(file) {
	
	var cp = this;
	cp.FILE = null;
	cp.DATA = [];
	cp.XML_DOC = null;
	cp.HTMLSTRING = [];
	cp.DD_NUM = 1;
	cp.CUR_VAL = null;
	cp.SAME_VAL = true;
	cp.CLK_CNTR = 0;
	
	if (file) {
		cp.FILE = file;
		cp.load(file);
	}
	else {
		return;
	}
	
};

CareersParser.prototype.SELECTITEM = '<option value="@VALUE"@SELECTED>@ITEMNAME</option>';

CareersParser.prototype.load = function() {
	
	var cp = this;
	var reqMethod = cp.setXMLHttpRequest();
	var file = cp.FILE;
	reqMethod.open("GET", file, true);
	reqMethod.onreadystatechange = function() {

		if (reqMethod.readyState == 4) {
			if (reqMethod.status == 200) {

				cp.XML_DOC = reqMethod.responseXML.documentElement;
				cp.getXMLData();
				cp.buildDropdown();
				change_dd_selection();
				
			}
		}
		
	};
	reqMethod.send(null);

	
};

CareersParser.prototype.getXMLData = function() {
	
	var cp = this;
	var data = cp.XML_DOC;
	var children = data.childNodes;
	
	for (var x = 0; x < children.length; x++) {
		
		var node = children[x];
		
		if (cp.validNode(node)) {
			
			var nName = node.nodeName;
			
			if (nName.toLowerCase() == 'category') {
				cp.breakdownNode(node);
			}
			
		};
		
	};
	
	
};

CareersParser.prototype.breakdownNode = function(node) {
	
	var cp = this;
	var tmp = [];
	var cntr = 0;
	var type = node.getAttribute("type");
	cp.DATA[type] = [];
	
	var children = node.childNodes;
	
	for (var x = 0; x < children.length; x++) {
		
		var node = children[x];
		
		if (cp.validNode(node)) {
			
			var nName = node.nodeName;
			
			if (nName.toLowerCase() == 'area') {
				
				var nData = node.childNodes;
				tmp[cntr] = [];
				
				for (var s = 0; s < nData.length; s++) {
					
					var cNode = nData[s];
					
					if (cp.validNode(cNode)) {
						
						var cName = cNode.nodeName;
						var cData = cNode.firstChild.nodeValue;
						
						if (cName.toLowerCase() == 'name') {
							tmp[cntr]['NAME'] = cData;
						};
						
						if (cName.toLowerCase() == 'link') {
							tmp[cntr]['LINK'] = cData;
						};
						
					};
					
				};
				
			};
			cntr++;
			
		};
		
	};
	
	cp.DATA[type] = tmp;
	
};

CareersParser.prototype.buildDropdown = function() {
	
	var cp = this;
	var data = cp.DATA;
	var items = "";
	var str = "";
	
	items = cp.subDropdown(data["Division"]);
	str = '<span class="select-box"><select id="sub_1" class="sub_selection" onchange="javascript:sub_selection(this);">' + items + '</select></span>';
	
	cp.HTMLSTRING[1] = str;
	
	document.getElementById("div_selection_1").innerHTML = str;
	document.getElementById("div_selection_1").style.display = 'block';
	
	items = cp.subDropdown(data["Industry"]);
	str = '<span class="select-box"><select id="sub_2" class="sub_selection" onchange="javascript:sub_selection(this);">' + items + '</select></span>';
	cp.HTMLSTRING[2] = str;
	
	document.getElementById("div_selection_2").innerHTML = str;

	document.getElementById("div_selection_2").style.display = 'none';
	
	items = cp.subDropdown(data["Location"]);
	str = '<span class="select-box"><select id="sub_3" class="sub_selection" onchange="javascript:sub_selection(this);">' + items + '</select></span>';
	
	cp.HTMLSTRING[3] = str;
	
	document.getElementById("div_selection_3").innerHTML = str;

	document.getElementById("div_selection_3").style.display = 'none';
	
	items = cp.subDropdown(data["Interest"]);
	str = '<span class="select-box"><select id="sub_4" class="sub_selection" onchange="javascript:sub_selection(this);">' + items + '</select></span>';
		
	cp.HTMLSTRING[4] = str;
		
	document.getElementById("div_selection_4").innerHTML = str;
	
	document.getElementById("div_selection_4").style.display = 'none';
	
	document.getElementById('selectionVar').value = document.getElementById('sub_1').value;
	//console.log(str);
	
	
};

CareersParser.prototype.subDropdown = function(data) {

	var cp = this;
	var items = "";
	var sItem = cp.SELECTITEM;

	for (var x = 0; x < data.length; x++) {
		
		var value = data[x]["LINK"];
		var name = data[x]["NAME"];
		
		if (x == 0) {
			sItem = sItem.replace(/@SELECTED/, " selected");	
		}
		else {
			sItem = sItem.replace(/@SELECTED/, "");	
		}
		
		sItem = sItem.replace(/@VALUE/, value);
		sItem = sItem.replace(/@ITEMNAME/, name);
		
		items = items + sItem;
		sItem = cp.SELECTITEM;
		
	};
	
	return items;
	
};

CareersParser.prototype.validNode = function(node) {
	
	if (node.nodeType == 1 && node.hasChildNodes()) {
		return true;
	}
	
	return false;

};


CareersParser.prototype.setXMLHttpRequest = function() {
		
		var xmlhttp;
		
		if (window.XMLHttpRequest) { // Mozilla, Safari,...
     		xmlhttp = new XMLHttpRequest();
     	
			if (xmlhttp.overrideMimeType) {
				// set type accordingly to anticipated content type
        		xmlhttp.overrideMimeType('text/xml');
			}
		} 
		else if (window.ActiveXObject) { // IE
			
			try {
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			} 
			catch (e) {
				try {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				} 
				catch (e) {}
			}
		
  		}
		
		return xmlhttp;
		
};