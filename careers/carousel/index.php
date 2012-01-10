<!doctype html>
<html>
    <head>
        <title>Centogram Carousel</title>
        <style type="text/css">
            body {
                padding:0px;
                margin:0px;
                font-family: Tahoma, sans-serif;
                color: #fff;
                font-size:12px;
                background-color: #000;
            }
            
            #carousel-bg {
                width:100%;
                height:388px;
                background-image:url('images/hero_brands_bg.jpg');
                background-position: center;
                background-color:#000;
                position:relative;
                -webkit-user-select: none;
                -khtml-user-select: none;
                -moz-user-select: none;
                -o-user-select: none;
                user-select: none;
            }
            #carousel-wrap {
               
                width:720px;
                margin-left:auto;
                margin-right:auto;
                position:absolute;
                height:388px;
               
            }
            .wheel {
                width:273px;
                height:40px;
                background-image:url('images/wheel-anim.png');
                position:absolute;
                bottom:1px;
                left: 223px; /* (carousel wrap width / 2) - (wheel width/2) */
                cursor:pointer;
                z-index:2000;
            }
            .slider {
                width:512px;
                height:3px;
                background-color:#888;
                position:absolute;
                top:365px;
                left:64px;
            }
            
            .slider .handle {
                width:135px;
                height:15px;
                background-image:url('images/slider.png');
                position:absolute;
                left:0px;
                top:-7px;
                cursor:pointer;
            }
            #slider2 {
                
                top:300px;
                left:64px;
            }
            
            .caro-image {
                position:absolute;
                cursor:pointer;
               
              /*  filter: blur(strength=75);*/
            }
            
            .shadow {
                 box-shadow: 0px 0px 24px rgba(0,0,0,0.75);
                -webkit-box-shadow: 0px 0px 24px rgba(0,0,0,0.75);
            }
            .caro-hover-menu {
                cursor:pointer;
                z-index:1000;
                position:absolute;
                overflow:hidden;
                background-repeat:no-repeat;
               /* background-image:url(images/Blank.gif);*/ /* This is need otherwise IE doesn't pick up events. */
            }
            
/*            .caro-hover-text {
                font-weight:bold;
                position:absolute;
                width:auto;
                top:70%;
                padding:10px;
                background-color:#444;
                white-space:nowrap;
                border:1px solid #888
            }
 */           
            .caro-hover-text {
                font-weight:bold;
                position:absolute;
                width: 180px;
                top:70%;
                padding:15px 40px 15px 10px;
                background:url(./images/hover-arrow.jpg) no-repeat scroll 190px #005fb9;
                color: #fff;
                font-size: 10px;
                text-transform: uppercase;
                xwhite-space:nowrap;                
            }
            
/*            .click-menu-text {
                font-family: tahoma, sans-serif;
                font-size:11px;
                font-weight:bold;
                z-index:-2;
                position:absolute;
                top:32px;
                background-color:#444;
                padding:10px;
                border:1px solid #888;
                color:#bbf;
                max-width: 360px;
            }
 */           
            .click-menu-text {
                font-family: tahoma, sans-serif;
                font-size:11px;
                font-weight:normal;
                z-index:-2;
                position:absolute;
                top:32px;
                background-color:#1b1b1b;
                padding:14px 20px 20px 20px;
                border:1px solid #666;
                color:#c0dcf7;
                width: 496px;
            }
            
            .click-menu-text h2 {
                font-size:14px;
                margin-top:5px;
                color:#fff;
            }
            
            
            .button a {               
                padding:5px 10px 0px 10px;
                height:19px;
                text-align:center;
                vertical-align: middle;
                background-image: url(images/button.png?x=1);               
                font-size:11px;
                min-width:64px;
                font-weight:bold;
                color:#fff;
                cursor:pointer;
                display:inline;float:left;
                margin:0px;           
            }
            .with-separator a {
                background-image: url(images/inline-button.png?x=1);
                padding-right:12px;
                background-position:right top;
               
            }
            
            .button a  {
                color:#fff;
                text-decoration: none; 
/*                    width:100%;*/
            }
            
            div.button a:hover {
                background-position: bottom;                   
            }
            div.button.with-separator a:hover {
                background-position: right bottom;                   
            }
            
        </style>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
        <script type="text/javascript" src="carousel.js"></script>
        <script type="text/javascript">
            // http://timewarner.com/our-content/
            $(
                function(){
                    Caro.fromXML("slides.xml",$('#carousel-wrap'));
                }
            );
        </script>
        
        
    </head>
    <body>
        <div id="carousel-bg">
            <div id="carousel-wrap">
                <div class="wheel"></div>
                </div>
        </div>
    </body>
</html>