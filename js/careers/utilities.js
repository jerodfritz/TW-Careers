function img_inact(imgName) {
	if (document.images) {
		document.images[imgName].src = eval(imgName + "Off.src");
	}
	window.status  = " ";
	return true;
}

function img_act(imgName) {
	if (document.images) {		
		document.images[imgName].src = eval(imgName + "On.src");
 	}
    window.status  = eval(imgName + "Blurb");
    return true;
}

function loadPage(form){
	var sel_idx = form.selectedIndex
	var urlToLoad = form.options[sel_idx].value
	//alert('loading ' + urlToLoad);
	location.href = urlToLoad
}

function loadURLFromSelect(selectElem){
	var sel_idx = selectElem.selectedIndex
	var urlToLoad = selectElem.options[sel_idx].value
	//alert('loading ' + urlToLoad);
	window.location = urlToLoad;
	return false;

}

/************************************/
/* RolloverImage Object 			*/
/* Author: Glen Kruger              */
/* Last Updated : 6/21/2004			*/
/************************************/

 function RolloverImage(obj) {
 	obj.onload = '';
	this.obj = obj;
	this.name = obj.name;
	this.defaultSrc = obj.src;
	this.init(obj);
 }
 
 RolloverImage.prototype.init = function(img) {
	this.type = img.src.substring((img.src.lastIndexOf(".") + 1), img.src.length);
	this.path = this.defaultSrc.substring(0, this.defaultSrc.lastIndexOf("/") + 1);
	this.base = img.src.substring((img.src.lastIndexOf("/") + 1), img.src.lastIndexOf(((img.src.indexOf("_on") >= 0)? "_" : ".")));
	this.on = new Image();
	this.on.src = this.path + this.base + "_on." + this.type;
	this.off = new Image();
	this.off.src = this.path + this.base + "." + this.type;
}

RolloverImage.prototype.setOn = function(){
	this.obj.src = this.on.src;
}

RolloverImage.prototype.setOff = function(){
	this.obj.src = this.off.src;
}

function RolloverImageMap() {
	this.Images = new Array();	
	return this;
}

RolloverImageMap.prototype.add = function(obj) {
	oImage = this.Images[this.Images.length] = new String(obj.name);
	oImage.obj = new RolloverImage(obj);
}

RolloverImageMap.prototype.get = function(key) {
	for (i=0; i < this.Images.length; i++) {
		if (key == this.Images[i]) {
			return this.Images[i].obj;
		}
	}
}

RolloverImageMap.prototype.setOn = function(key) {
	oImage = this.get(key);
	oImage.setOn();
}

RolloverImageMap.prototype.setOff = function(key) {
	oImage = this.get(key);
	oImage.setOff();	
}

var ro = new RolloverImageMap();

function pop(url, width, height) {
	var popup = window.open(url, "window", 'location=no,menubar=no,status=no,scrollbars=no,toolbar=no,width=' + width + ',height=' + height + ',top=30,left=30');
	popup.focus();
}


