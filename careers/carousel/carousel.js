    // $Id
    // 
    // Merge the object 'from' into the object 'to' overwriting any properties found in 'to'
    function mergeObj(to, from) {
        if (from != undefined) for (var k in from)
        if (from.hasOwnProperty(k)) {
            if (isObj(from[k])) {
                to[k] = mergeObj(to[k] || {}, from[k]);
            } else to[k] = from[k];
        }
        return to;
    };
    // Function to check if object is an object
    function isObj(o) {
        return o != undefined && typeof o == 'object' && !(o instanceof Array || o instanceof Date || o instanceof Number || o instanceof String);
    };

    function extend(superType, obj) {
       var intermediateConstructor = function() {};
       intermediateConstructor.prototype = superType.prototype;
       return mergeObj(new intermediateConstructor(),obj);
    };

    // Simple wrapper class for a DOM object.
    // Abstracts away a DOM element into a class,
    // so our objects can be more 'normal' as opposed
    // to jQuery-ish.
    var DomObj = function($obj) {
        this.$obj = $obj;           // Store a ref to the element.
        $obj.data('this',this);     // Store a ref to this in the element.
        // Returns this from the element.
        DomObj.getThis = function($obj){
            return $obj.data('this');
        }
    };
    // Destroy function, removes reference to this from element,
    // and removes element itself.
    DomObj.prototype = {
        destroy: function() {
            this.$obj.removeData('this');
            this.$obj.remove();
        }
    };

    var Caro = function(params) {
        var destT = 0;        
        this.setDest = function(val) {
            destT = (Math.round(val) * 5) / 5;
        }
        this.getDest = function() {return destT;};
        this.setDest = function(val) {destT = val;};
        this.addDest = function(val) {
            destT = (Math.round((destT+val) * 5) / 5);
        };        
        this.setup(params);
    };
    
    // Utility function to init a carousel from XML file.
    Caro.fromXML = function(xmlFile,$wrap) {
        var obj = {slideData:[]};
        $.ajax({
            type: "GET",
            url: xmlFile,
            dataType: "xml",
            success: function(xml) {
                $(xml).find('slide').each(function(){
                    var slide = {
                        image: $(this).find('image').text(),
                        hoverHTML: $(this).find('hoverHTML').text(),
                        mainHTML: $(this).find('mainHTML').text()
                    };
                    obj.slideData.push(slide);
                });
                obj.$wrap = $wrap;
                return new Caro(obj);
            }
        });
    }

    
    Caro.prototype = {
        // Returns the frontmost caro image.
        getFrontItem: function() {
            var frontItem = null;
            this.$caroImages.each(function(){
                var c = DomObj.getThis($(this));
               
                if(c.isFront()) {
                    frontItem = c;
                }
            })
            return frontItem;
        },
        // Setup tiles.
        setup: function(params,$wrap) {
            var self = this;
            this.isTouch = 
                function () {  
                  try {  
                    document.createEvent("TouchEvent");  
                    return true;  
                  } catch (e) {  
                    return false;  
                  }  
                }();
               
            this.interval = null;
            this.mouseX = this.mouseY = 0;
            this.params = params;
            this.$caroImages = null;
            this.hidden = false; // clicks etc. on items should be ignored when true.
            var t=(1/10);
            for(var i=0;i<5;i++) {
                var c  = new CaroImage(params.$wrap,t,params.slideData[i],this);
                
                t+=(1/5);
            }
            this.$caroImages = $('.caro-image',params.$wrap);
            this.stopped();
            this.wheel = new Wheel($('.wheel'),{min:0  ,max:100, caro:this});
           
            var val=0;
            this.t=0;
            this.dur = 1000;
            
            // Remove hover menu if mouse down
            params.$wrap.bind('click mousemove',function(evt) {
            //$(window).bind('click mousemove',function(evt) {    
                //if($(evt.target).attr('id') == "carousel-wrap")
                  //  $('.caro-dim',self.$obj).animate({opacity:0},{queue:false,duration:100});
                if(evt.type == "click") {
                    var cw = DomObj.getThis($('.caro-hover-menu',params.$wrap));
                     if(cw) cw.destroy();
                }else if(evt.type= "mousemove") {
                    self.mouseX = evt.pageX;
                    self.mouseY = evt.pageY;
                }           
            });
                      
            $('.wheel').bind('change',function(evt){
               
                if (self.hidden) return;
              
                var s = DomObj.getThis($(evt.target));
               
                var newVal = Math.floor(s.value.value);
                    if( newVal < val ) {self.addDest(-(1/5));}
                    if (newVal > val) {self.addDest(1/5);}
                    //if(newVal !== val || 1) {
                        //self.$caroImages.removeClass('s   hadow');  
                        //console.log(s.value.change);
                        if(self.interval ||1) {
                            ///self.startT = self.t;            
                            //self.oldTime = new Date().getTime();
                            // Come to rest at the tile to the right if scrolling right,
                            // and vice-versa by adusting the destinate rotation based
                            // on the direction of wheel movement.
                            self.t+=s.value.change/100;
                            self.t = self.t %1;
                            self.setDest(self.t);
                            self.update2();
                            
                        }else {
                            self.hideShadows();
                            //self.startTimeout();
                        }
                    //}
                     val = newVal;
                
               
            });


        },
        setTimes: function(t,$images) {           
            this.$caroImages.each(function(){
                 var ci = DomObj.getThis($(this));
                 ci.update(t);
            });           
        },
        
        update2:function(){
            var self = this;
            self.setTimes(self.t);
        },
        update: function() {
            var self = this, dur = 1000,
                easeOutQuad = function (t, b, c, d) {
                    return -c *(t/=d)*(t-2) + b;
                },
                easeOutCirc = function (t, b, c, d) {
                    return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
                },
                newTime = new Date().getTime();
                
                if( (newTime - self.oldTime) >= dur) {
                    self.stopped();
                   //console.log('stop');
                    return false;
                }
                self.t = easeOutCirc(newTime-self.oldTime, self.startT,self.getDest()-self.startT,dur);
                self.setTimes(self.t);
                //console.log(self.t)
                return true;
            
        },
        
        startTimeout: function() {
            var self = this;
            if (self.interval) {
                return;
            }
            this.startT = this.t;            
            this.oldTime = new Date().getTime();
            var update = function() {
                if (self.update() )                
                    self.interval = setTimeout(update,20);
            }
            update();
        },
        hideShadows: function(){
            $('.caro-shadow').css({opacity:0,display:'none'});
             $('.caro-shadow').removeClass('shadow');
        },
        showShadows: function(){
            $('.caro-shadow').addClass('shadow');
            $('.caro-shadow').css({display:'block'}).animate({opacity:1},{queue:false,duration:1500});
             
        },
        deleteReflections:function() {
            $('.reflection').remove();
        },
        createReflections:function() {
            this.$caroImages.each(
                function(){
                    var c = DomObj.getThis($(this));
                    c.createReflection();
                    //$('.reflection',c.$obj).css('opacity',0.25);
                }
            );
        },
        // Called when carousel stops.
        stopped: function(){
            var self = this;
            //self.$caroImages.addClass('shadow');
            self.showShadows();
            //console.log('sgad');
            
            clearTimeout(this.interval);
            this.interval = null;
            
            // Code below should really by in CaroImage
            self.$caroImages.each(function(){
                var c = DomObj.getThis($(this));
                // If image was clicked near front,
                // then init the click menu immediately, bypassing the hover menu.
                if(c.preClick) {
                    c.preClick = false;
                    self.hidden = true;
                    new CaroClickMenu(c,self);
                    self.$caroImages.animate({opacity:0},500);
                    return;
                }
                if(c.isFront()) {
                    
                    if(c.hasHover)return false;
                   // self.$caroImages.unbind('mouseover mousemove');
                    c.bindEvents();
               
                    // If mouse already over image when it stops,
                    // trigger mouseover event.
                    if (
                        self.mouseX > c.$image.offset().left &&
                        self.mouseX < c.$image.offset().left + c.$image.outerWidth() &&
                        self.mouseY > c.$image.offset().top &&
                        self.mouseY < c.$image.offset().top + c.$image.outerHeight()
                    ){
                        var e = $.Event("mouseover", {target: c.$image, data:c });
                        c.$image.trigger(e);
                    }
                }
            })
        }
    }

    var Wheel = function($obj,props) {
        DomObj.call(this, $obj);    
        this.setup(props);
    };

    Wheel.prototype = extend(DomObj, {
        setup: function(props) {        
            var self = this,
            oldX = 0;
            this.dragging = false;
            this.startX = 0;
            this.value = 0;
            this.animYOffset = 0;   // y background pos for animation.
            this.dx = 0;            // last direction of movement 
            //document.addEventListener("touchstart", function(evt){evt.preventDefault()}, false);
            
            this.$obj.bind('touchstart touchend touchmove',function(evt){
                evt.preventDefault();
                var touch = evt.originalEvent.touches[0] || evt.originalEvent.changedTouches[0];
                //alert(evt.type + ' ' + touch.pageX + " - " + touch.pageY);
                //return;
                switch (evt.type) {  
                    
                    case "touchmove":                       
                        if (!self.dragging) return;
                        var dx = touch.pageX - self.oldX;
                        this.dx = dx;
                        if(dx > 0 ) self.animate(-1);
                        
                        else self.animate(1);
                      
                         self.value = {  value:((dx / (self.$obj.width())  ) * (props.max - props.min)),
                                        change:touch.pageX-self.oldX
                                    };
                          self.oldX = touch.pageX;
                        self.$obj.trigger('change');
                        return false;
                        break;
                    case "touchend":
                        self.dragging = false;
                        var t = props.caro.t;
                        if(this.dx>0) t += 1/5;
                        var dest = Math.floor(t*5)/5;
                        props.caro.addDest(dest - props.caro.t);
                        props.caro.startTimeout();
                        break;
                    case "touchstart":
                        //alert(touch.pageX + " - " + touch.pageY);
                        clearTimeout(props.caro.interval);
                        props.caro.interval = null;
                        self.startX = touch.pageX;
                        self.oldX = touch.pageX;
                        self.dragging = true;
                        props.caro.hideShadows();

                }
            });
          
            this.$obj.bind('mousemove mouseout mousedown mouseup',function(evt){
                switch (evt.type) {

                    case 'mousemove':
                        if (!self.dragging) return;
                        var dx = evt.pageX - self.oldX;
                        this.dx = dx;
                        if(dx > 0 ) self.animate(-1);
                        
                        else self.animate(1);
                      
                         self.value = {  value:((dx / (self.$obj.width())  ) * (props.max - props.min)),
                                        change:evt.pageX-self.oldX
                                    };
                          self.oldX = evt.pageX;
                        self.$obj.trigger('change');
                        //props.caro.update();
                        break;
                    case 'mouseup':
                    case 'mouseout':
                        self.dragging = false;
                        var t = props.caro.t;
                        if(this.dx>0) t += 1/5;
                        var dest = Math.floor(t*5)/5;
                        props.caro.addDest(dest - props.caro.t);
                        props.caro.startTimeout();
                        
                        break;
                    case 'mousedown':
                        clearTimeout(props.caro.interval);
                        props.caro.interval = null;
                        self.startX = evt.pageX;
                        self.oldX = evt.pageX;
                        self.dragging = true;
                        props.caro.hideShadows();
                }
            });
        },
        animate: function(direction) {
            this.animYOffset += direction * 40;
            if( direction > 0 && this.animYOffset >= 160) this.animYOffset = 0;
            else if( direction < 0 && this.animYOffset < 0) this.animYOffset = 120;
            this.$obj.css({backgroundPosition: "0px " + this.animYOffset + 'px'});
            //console.log(this.animYOffset);
        },
        hide: function() {
            //this.$obj.animate({opacity:0},{duration:250,queue:false});
            this.$obj.css('display','none');
        },
        show: function() {
            //this.$obj.animate({opacity:1},{duration:250,queue:false});
            this.$obj.css('display','block');
        }
    });  

// START Reflection object.
// Creates a reflection for underneath an image.
// IE <9 uses an image with IE specific filter properties, other browsers use the Canvas tag.	
function Reflection(img, reflHeight, opacity) {
    var reflection, cntx,
        gradient, $parent,
        $reflection,
        imageWidth = img.width, imageHeight = img.height;
        $parent = $(img).parent();
        $reflection = $("<canvas class='reflection' style='position:absolute' />");       
        reflection = $reflection[0];
        //this.element = reflection = parent.append().find(':last')[0];
    if (!reflection.getContext && $.browser.msie) {
       
        $reflection = $("<img class='reflection' style='position:absolute;'/>");
        reflection = $reflection[0];
        $reflection.attr('src',img.src);
        //opacity=1;
        reflection.style.filter = "flipv progid:DXImageTransform.Microsoft.Alpha(opacity=" + 
            (opacity * 100) + ", style=1, finishOpacity=0, startx=0, starty=0, finishx=0, finishy=" +
            (8) + ")";
                
        $parent.append($reflection);
        $reflection.css({
                width: imageWidth //,
        //        //height: reflHeight
        });


    } else {
        
        
        cntx = reflection.getContext("2d");
        try {
            $parent.append($reflection);
            $reflection.attr({
                width: imageWidth,
                height: reflHeight
            });
            cntx.save();
            cntx.translate(0, imageHeight - 1);
            cntx.scale(1, -1);
            cntx.drawImage(img, 0, 0, imageWidth, imageHeight);
            cntx.restore();
            cntx.globalCompositeOperation = "destination-out";
            gradient = cntx.createLinearGradient(0, 0, 0, reflHeight);
            gradient.addColorStop(0, "rgba(255, 255, 255, " + (1 - opacity) + ")");
            gradient.addColorStop(1, "rgba(255, 255, 255, 1.0)");
            cntx.fillStyle = gradient;
            cntx.fillRect(0, 0, imageWidth, reflHeight);
            
        } catch (e) {
             alert('IE');
            return null;
        }
    }
    $reflection.css({top:imageHeight});
    return $reflection;
} //END Reflection object    


// Function to execute a callback when an image has been loaded,
// either from the network, or from the browser cache.

var loadImage = function ($image, src, callback) {
    
    // Bind the load event BEFORE setting the src.
    $image.bind("load", function (evt) {
        // If no valid width, image hasn't actually loaded.
        if ($image.width() === 0) {
            return;
        }
        // Image has loaded, so unbind event and call callback.
        $image.unbind("load");
        callback($image);

    }).each(function () {
        // For Gecko based browsers, check the complete property,
        // and trigger the event manually if image loaded.
        if ($image[0].complete) {
            $image.trigger("load");
        }
    });
    // For Webkit browsers, the following line ensures load event fires if
    // image src is the same as last image src. This is done by setting
    // the src to an empty string initially.
    if ($.browser.webkit) {
        $image.attr('src', '');               
    }
    $image.attr('src', src );
};
     


    var CaroImage = function($par, t,slideData,caro) {
    // '<img class="caro-shadow" style="position:absolute;" src="images/shadow.png" />' +
        var $obj = $('<div class="caro-image" style="position:absolute;">'+ 
                        '<div class="caro-shadow" style="position:absolute"  />' +
                        '<img class="caro-img" style="position:absolute" src="' + slideData.image + '"/>' +
                        //'<img class="caro-dim" style="position:absolute;" src="images/dim-overlay.png?x=1" />' +
                        '</div>');
        var self = this;
        if(!caro.isTouch) $obj.append('<img class="caro-dim" style="position:absolute;" src="/careers/carousel/images/dim-overlay.png?x=3" />');
        DomObj.call(this, $obj); 
        //c.setScale(1-size);
        this.caro = caro;
        this.slideData = slideData;
        this.orgWidth = this.width = 222;
        this.orgHeight = this.height = 294;
        this.t = this.orgT = t;
        this.hasHover = false;
        this.$image = $('.caro-img',$obj);
        this.$shadow = $('.caro-shadow',$obj);
        this.$dim = $('.caro-dim',$obj).css('opacity',0);
        this.$reflection = null;
        this.preClick = false;
        this.imageLoaded = false;
        $par.append($obj);
         
        loadImage(this.$image,slideData.image,function($image){
           
            self.orgWidth = self.width = $image.width();
            self.orgHeight = self.height = $image.height();
           // console.log(self.orgWidth + " "+self.orgHeight + " " +self.t);
            self.imageLoaded = true;
            self.update(0);
           // self.$reflection = Reflection(self.$image[0],32,0.25);
            self.createReflection();
         });
        // Bring to front? 
        $obj.bind('click',function(){
           if(caro.interval || caro.hidden) return;    // Can only btf if caro stopped and not hidden.
           
           if(self.nearFront()) {
               self.preClick = true;
           }
           caro.hideShadows();
           caro.addDest(0.5 - self.t);
           // Remove dim.
           $('.caro-dim',self.caro.$obj).animate({opacity:0},{queue:false,duration:100});
           caro.startTimeout();
        });
        this.bindEvents(true);
    };
    CaroImage.prototype = extend(DomObj, {
        
        createReflection: function() {
            var self = this;
            self.$reflection = Reflection(self.$image[0],32,0.25);
            self.$reflection.css('top',self.$obj.height());
            
        },
        bindEvents: function() {
            
            var self = this;
            this.$obj.unbind('mouseover mousemove mouseout');
            this.$obj.bind('mouseover mousemove mouseout',{c:this},function(event){  
               if(self.caro.isTouch) return;
               // Only interested if over actual image, not reflection.
               if ($(event.target).hasClass('reflection')) return false; 
               if (self.hasHover) return false;   
               if (event.type=='mouseover' ) {
                    //self.$image.trigger(evt.type);
                    // Fade dim back in on other tiles.
                   $('.caro-dim',self.caro.$obj).not(self.$obj).animate({opacity:0.5},{queue:false,duration:100});
                   // Fade out dim on this tile.
                   self.$dim.animate({opacity:0},{queue:false,duration:100});
                }else
                // If mouse out, make dim dissapear for all item.    
                if(event.type=="mouseout") {
                  $('.caro-dim',self.caro.$obj).animate({opacity:0},{queue:false,duration:100});
                  return;
                }
                // If front item, init hover menu.
                if ( !self.caro.interval && self.isFront() ) {
                    
                   
                    var c=event.data.c;
                    if (c.caro.hidden) return false;
                   
                    
                    //self.$obj.unbind('mouseover mousemove mouseout');
                    new CaroHoverMenu(self,self.caro);
                    return false;
                }
            })
        },
        
        easeInOutSine: function (t, b, c, d) {
            return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;
        },


        update: function(t) {
            t += this.orgT;
            if(t>1) t = 0 + t%1;
            else if(t<0) {
                t = 1 + t%1;
            }
            this.t = t;
             if(this.imageLoaded) {
                var x = this.easeInOutSine(t,0,720,1);
                var size = Math.abs((1/2) - t);
                this.$obj.css('z-index',101-Math.floor(size *100));
                var scale = 1-(size*0.75);
                this.setScale(scale);
                this.setXY(x,185);
                var w = parseInt(this.$obj.css('width'));
                var h = parseInt(this.$obj.css('height'));
                this.$image.css({width:w, height:h});
                if(this.$reflection) {
                    this.$reflection.css({
                        width:w,
                       // height:24,
                        top:parseInt(h)+1
                    });
                }
                var left = -10*scale,
                    top = (10*scale),
                    width = w+(20*scale);
                   
                if(!this.caro.isTouch) this.$dim.css({left:0, top:0, width: w ,height:h});
                this.$shadow.css({left:0, top:(12*scale), width: w ,height:h-(12*scale)});
                //this.$shadow.css({left:left, top:top, width: width ,height:h});
                //this.$shadow.css({left:-10*scale,top: (10*scale),width: w+(20*scale) ,height:h});
            }
        },
        isFront: function() {
            
            return this.t.toFixed(1) == 0.5;
        },
        nearFront: function() {
            return (this.t > 0.4 && this.t < 0.6);
        },

        setXY: function(x,y) {
            x -= this.width/2;
            y -= this.height/2;
            this.$obj.css({'left': x + 'px', 'top': y + 'px'});
            this.x = x;
            this.y = y;
        },
        setScale: function(scale) {
            this.width = this.orgWidth * scale;
            this.height = this.orgHeight * scale;
            this.$obj.css({'width': this.width + 'px', 'height':this.height + 'px'});
        },
        
        showShadow: function() {
            
        },
        hideShadow:function() {
            
        }
    });


    CaroHoverMenu = function(caroImage,caro) {
        //alert('xx');
      //  if($('.caro-hover-menu',caroImage.$obj.parent()).length) return;
        var $obj = 
            $('<div class = "caro-hover-menu" style="background-image:url(images/blank.png);">'+
            '</div>');
        DomObj.call(this, $obj);
        this.setup(caroImage,caro);
    }
    CaroHoverMenu.prototype = extend(DomObj, {
        setup: function(caroImage,caro) {            
            this.caroImage = caroImage;
            this.caro = caro;
            var self = this,$obj;
            caroImage.hasHover = true;
            // Remove existing menu if there is one;

            caroImage.$obj.parent().append(this.$obj);
            var left = parseInt(caroImage.$obj.css('left')) - 32 + 'px';
            var width = parseInt(caroImage.$obj.css('width')) + 32 + 'px';

            this.$obj.css({
                left: left,
                top: caroImage.$obj.css('top'),
                width: width,
                height: caroImage.$obj.css('height')
            });
            // Blue opacity overlay
         /*   $obj =  $('<div style="position:absolute;left:32px;width:0px;height:32px;background-color:#1B5AA9"></div>');
            this.$obj.append($obj);
            $obj.css({height:caroImage.$obj.css('height'), opacity:0.5});
            $obj.animate({
                width: caroImage.$obj.css('width')
            },300);
*/
            $obj =  $('<div class="caro-hover-text shadow">'+
                caroImage.slideData.hoverHTML +
                '</div>');
            this.$obj.append($obj);

            $obj.css( {'z-index':1010,'left':caroImage.$obj.css('width')});
            $obj.animate({left: "0px"},500);
            this.$obj.bind('mouseleave', function(){              
               self.destroy(); 
               caroImage.$obj.trigger('mouseout'); // remove dims.
            });
            
            this.$obj.bind('click', function(){
               self.caro.hidden = true;
               self.caro.deleteReflections();
               new CaroClickMenu(caroImage,self.caro);
               caroImage.$obj.trigger('mouseout'); // remove dims.               
               //console.lof(self.caro.$caroImages.length);
               self.caro.$caroImages.animate({opacity:0},500);               
               // removal of hover menu handled by delegated mousedown event handler in Caro              
               self.destroy();               
               return false;
            });
        },
        destroy: function() {
            this.caroImage.hasHover = false;
           // alert(DomObj.prototype.destroy);
            DomObj.prototype.destroy.call(this);
        }
    });
   
    
    CaroClickMenu = function(caroImage,caro) {
     
        var $obj = 
            $('<div class = "caro-click-menu" style="z-index:1000;height:360px;position:absolute;width:1000px;">'+
               '<div class="text-hider" style="width:1000px;height:360px;overflow:hidden;position:absolute;"></div>'+
               '<div class="shadow" style="position:absolute;top:11px;"></div>'+
               '<img style="position:absolute;z-index:1003" src="' + caroImage.$image.attr('src') + '"/>' +
            '</div>');           
        DomObj.call(this, $obj);
        this.setup(caroImage,caro);
    }
    CaroClickMenu.prototype = extend(DomObj, {
        setup: function(caroImage,caro) {
            caro.wheel.hide();
            this.caroImage = caroImage;  
            this.$shadow = $('.shadow',this.$obj);
            //this.$reflection = Reflection($('img',this.$obj)[0],32,0.25);
            //this.$reflection.css({left:0,top:300});
            this.caro = caro;
            var self = this,
                $obj;
            caroImage.hasHover = false;           
            caroImage.$obj.parent().append(this.$obj);
            var left = parseInt(caroImage.$obj.css('left'));
            var width = parseInt(caroImage.$obj.css('width'));
            this.$shadow.css({width:width,height:parseInt($('img',this.$obj).css('height'))-11});
            this.$reflection = Reflection($('img',this.$obj)[0],32,0.25);
            this.$reflection.css({left:0,top:parseInt($('img',this.$obj).css('height'))});
            
            this.$obj.css({
                left: left,
                top: caroImage.$obj.css('top')
                /*height: caroImage.$obj.css('height')*/
            }).delay(500).animate({left:0},500);   // Initial delay to wait for fadeout to finish.
            
            $obj =  $('<div class="click-menu-text shadow" style="z-index:1001">'+
                caroImage.slideData.mainHTML +
                '</div>');
            $('.text-hider',this.$obj).append($obj);
            this.$button = $('<div class="button" style="position:absolute;top:58px;left:-100px;"><a href="#"><  Go Back</a></div>');
            $obj.css({left: width-$obj.outerWidth()}).delay(1000).animate({left:width},500,
                 function(){
                    self.$obj.parent().append(self.$button);
                }
            );   
            
            this.$button.bind('click',function() {
                
                self.$button.remove();
                
                $('img,',self.$obj).removeClass('shadow');
                
                $('.click-menu-text',self.$obj).animate({left:width-$obj.outerWidth()},500);    // hide text.
                self.$obj.animate({left:left},500,function(){           // Move to centre again.
                    var called = false;
                    caro.$caroImages.animate({opacity:1},500,function()
                    {    // fade carousel back in.
                        $(this).css('filter','');
                        if(!called) {
                            
                            self.caro.hidden = false;
                            self.caro.stopped();
                            self.caro.wheel.show();
                            self.caro.createReflections();
                            self.destroy();
                            called = true;
                        }
                    });
                    self.caro.stopped();
                });
                return false;
            })
        }
    });
    
    
/*
    var Slider = function($obj,props) {
        DomObj.call(this, $obj);    
        this.setup(props);
    };
    
    Slider.prototype = extend(DomObj, {
        setup: function(props) {        
            var self = this;
            this.$handle = $('.handle',this.$obj);  
            this.dragging = false;
            this.diffX = 0;
            this.value = 0;

            this.$obj.parent().bind('mousemove mouseup',function(evt){
                if(evt.type == 'mousemove') {
                    if (!self.dragging) return;
                    var x = 1*(self.$handle.css('left').replace('px',''));
                    //x = evt.pageX - self.diffX;
                    x += evt.pageX - self.diffX;
                    if (x<0) {
                        x=0;
                    }else if(x>self.$obj.width()-self.$handle.width()) {
                        x = self.$obj.width()-self.$handle.width();
                    }
                    self.value = ((x / (self.$obj.width()-self.$handle.width())  ) *
                        (props.max - props.min)) + props.min;

                    self.diffX = evt.pageX;

                    self.$handle.css('left',x+'px');
                    self.$obj.trigger('change');
                }else {
                    self.dragging = false;
                }
            });

            this.$handle.bind('mousedown',function(evt){
                self.diffX = evt.pageX;           
                self.dragging = true;
            });
        }
    });  
    */