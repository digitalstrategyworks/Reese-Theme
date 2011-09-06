$(document).ready(function(){$('#featured').orbit({'bullets':true,'timer':true,'animation':'fade'});});(function($){$.fn.orbit=function(options){var defaults={animation:'fade',animationSpeed:800,advanceSpeed:8000,startClockOnMouseOut:false,startClockOnMouseOutAfter:3000,directionalNav:false,captions:true,captionAnimationSpeed:800,timer:false,bullets:false};var options=$.extend(defaults,options);return this.each(function(){var activeImage=0;var numberImages=0;var orbitWidth;var orbitHeight;var locked;var orbit=$(this).addClass('orbit')
var images=orbit.find('img, a img');images.each(function(){var _img=$(this);var _imgWidth=_img.width();var _imgHeight=_img.height();orbit.width(940);orbitWidth=orbit.width()
orbit.height(293)
orbitHeight=orbit.height();numberImages++;});images.eq(activeImage).css({"z-index":3});if(options.timer){var timerHTML='<div class="timer"><span class="mask"><span class="rotator"></span></span><span class="pause"></span></div>'
orbit.append(timerHTML);var timer=$('div.timer')
var timerRunning;if(timer.length!=0){var speed=(options.advanceSpeed)/180;var rotator=$('div.timer span.rotator')
var mask=$('div.timer span.mask')
var pause=$('div.timer span.pause')
var degrees=0;var clock;function startClock(){timerRunning=true;pause.removeClass('active')
clock=setInterval(function(e){var degreeCSS="rotate("+degrees+"deg)"
degrees+=2
rotator.css({"-webkit-transform":degreeCSS,"-moz-transform":degreeCSS,"-o-transform":degreeCSS})
if(degrees>180){rotator.addClass('move')
mask.addClass('move')}
if(degrees>360){rotator.removeClass('move')
mask.removeClass('move')
degrees=0;shift("next")}},speed);};function stopClock(){timerRunning=false;clearInterval(clock)
pause.addClass('active')}
startClock();timer.click(function(){if(!timerRunning){startClock();}else{stopClock();}})
if(options.startClockOnMouseOut){var outTimer;orbit.mouseleave(function(){outTimer=setTimeout(function(){if(!timerRunning){startClock();}},options.startClockOnMouseOutAfter)})
orbit.mouseenter(function(){clearTimeout(outTimer);})}}}
function unlock(){locked=false;}
function lock(){locked=true;}
if(options.captions){var captionHTML='<div class="caption"><span class="orbit-caption"></span></div>';orbit.append(captionHTML);var caption=orbit.children('div.caption').children('span').addClass('orbit-caption').show();var numCredits=orbit.find('span.photo-credit').length;function setCaption(){var _captionLocation=images.eq(activeImage).attr('rel');var _captionHTML=$("#"+_captionLocation).html();var _captionHeight=caption.height()+20;caption.attr('id',"#"+_captionLocation).html(_captionHTML);}
var lastCredit;function setCredit(){var myCaption=images.eq(activeImage).attr('rel');var plainNum=myCaption.substring(myCaption.length,myCaption.length-1);orbit.find('span.photo-credit.active').removeClass('active');var thisCredit=$('#credit-'+plainNum);thisCredit.addClass('active');}
setCredit();setCaption();}
if(options.directionalNav){var directionalNavHTML='<div class="slider-nav"><span class="right">Right</span><span class="left">Left</span></div>';orbit.append(directionalNavHTML);var leftBtn=orbit.children('div.slider-nav').children('span.left');var rightBtn=orbit.children('div.slider-nav').children('span.right');leftBtn.click(function(){if(options.timer){stopClock();}
shift("prev");});rightBtn.click(function(){if(options.timer){stopClock();}
shift("next")});}
if(options.bullets){var bulletHTML='<ul class="orbit-bullets"></ul>';orbit.append(bulletHTML);var bullets=$('ul.orbit-bullets');for(i=0;i<numberImages;i++){var liMarkup=$('<li>'+i+'</li>')
$('ul.orbit-bullets').append(liMarkup);liMarkup.data('index',i);liMarkup.click(function(){if(options.timer){stopClock();}
shift($(this).data('index'));});}
function setActiveBullet(){bullets.children('li').removeClass('active').eq(activeImage).addClass('active')}
setActiveBullet();}
function shift(direction){var prevActiveImage=activeImage;var slideDirection=direction;if(prevActiveImage==slideDirection){return false;}
function resetAndUnlock(){images.eq(prevActiveImage).css({"z-index":1});unlock();}
if(!locked){lock();if(direction=="next"){activeImage++
if(activeImage==numberImages){activeImage=0;}}else if(direction=="prev"){activeImage--
if(activeImage<0){activeImage=numberImages-1;}}else{activeImage=direction;if(prevActiveImage<activeImage){slideDirection="next";}else if(prevActiveImage>activeImage){slideDirection="prev"}}
if(options.bullets){setActiveBullet();}
if(options.animation=="fade"){images.eq(prevActiveImage).css({"z-index":2});images.eq(activeImage).css({"opacity":0,"z-index":3}).animate({"opacity":1},options.animationSpeed,resetAndUnlock);if(options.captions){setCaption();setCredit();}}
if(options.animation=="horizontal-slide"){images.eq(prevActiveImage).css({"z-index":2});if(slideDirection=="next"){images.eq(activeImage).css({"left":orbitWidth,"z-index":3}).animate({"left":0},options.animationSpeed,resetAndUnlock);}
if(slideDirection=="prev"){images.eq(activeImage).css({"left":-orbitWidth,"z-index":3}).animate({"left":0},options.animationSpeed,resetAndUnlock);}
if(options.captions){setCaption();setCredit();}}
if(options.animation=="vertical-slide"){images.eq(prevActiveImage).css({"z-index":2});if(slideDirection=="prev"){images.eq(activeImage).css({"top":orbitHeight,"z-index":3}).animate({"top":0},options.animationSpeed,resetAndUnlock);}
if(slideDirection=="next"){images.eq(activeImage).css({"top":-orbitHeight,"z-index":3}).animate({"top":0},options.animationSpeed,resetAndUnlock);}
if(options.captions){setCaption();setCredit();}}}}});}})(jQuery);var $buoop={vs:{i:7,f:2,o:10.01,s:3,n:9}}
$buoop.ol=window.onload;window.onload=function(){if($buoop.ol)$buoop.ol();var e=document.createElement("script");e.setAttribute("type","text/javascript");e.setAttribute("src","http://browser-update.org/update.js");document.body.appendChild(e);}
(function($){$.fn.ticker=function(options){var opts=$.extend({},$.fn.ticker.defaults,options);var newsID='#'+$(this).attr('id');var tagType=$(this).attr('tagName');return this.each(function(){var settings={position:0,time:0,distance:0,newsArr:{},play:true,paused:false,contentLoaded:false,dom:{contentID:'#ticker-content',titleID:'#ticker-title',titleElem:'#ticker-title SPAN',tickerID:'#ticker',wrapperID:'#ticker-wrapper',revealID:'#ticker-swipe',revealElem:'#ticker-swipe SPAN',controlsID:'#ticker-controls',prevID:'#prev',nextID:'#next',playPauseID:'#play-pause'}};if(tagType!='UL'&&opts.htmlFeed===true){debugError('Cannot use <'+tagType.toLowerCase()+'> type of element for this plugin - must of type <ul>');return false;}
initialisePage();function countSize(obj){var size=0,key;for(key in obj){if(obj.hasOwnProperty(key))size++;}
return size;};function debugError(obj){if(opts.debugMode){if(window.console&&window.console.log){window.console.log(obj);}
else{alert(obj);}}}
function initialisePage(){$(settings.dom.wrapperID).append('<div id="'+settings.dom.tickerID.replace('#','')+'"><div id="'+settings.dom.titleID.replace('#','')+'"><span style="display: none;"><!-- --></span></div><p id="'+settings.dom.contentID.replace('#','')+'"></p><div id="'+settings.dom.revealID.replace('#','')+'"><span style="display: none;"><!-- --></span></div></div>');$(settings.dom.wrapperID).removeClass('no-js').addClass('has-js');$(settings.dom.tickerElem+','+settings.dom.titleElem+','+settings.dom.contentID).hide();if(opts.controls){$(settings.dom.controlsID).live('click mouseover mousedown mouseout mouseup',function(e){var button=e.target.id;if(e.type=='click'){switch(button){case settings.dom.prevID.replace('#',''):settings.paused=true;$(settings.dom.playPauseID).addClass('paused');changeContent(button);break;case settings.dom.nextID.replace('#',''):settings.paused=true;$(settings.dom.playPauseID).addClass('paused');changeContent(button);break;case settings.dom.playPauseID.replace('#',''):if(settings.play==true){settings.paused=true;$(settings.dom.playPauseID).addClass('paused');pauseTicker();}
else{settings.paused=false;$(settings.dom.playPauseID).removeClass('paused');restartTicker();}
break;}}
else if(e.type=='mouseover'&&$('#'+button).hasClass('controls')){$('#'+button).addClass('over');}
else if(e.type=='mousedown'&&$('#'+button).hasClass('controls')){$('#'+button).addClass('down');}
else if(e.type=='mouseup'&&$('#'+button).hasClass('controls')){$('#'+button).removeClass('down');}
else if(e.type=='mouseout'&&$('#'+button).hasClass('controls')){$('#'+button).removeClass('over');}});}
$(settings.dom.contentID).mouseover(function(){if(settings.paused==false){pauseTicker();}}).mouseout(function(){if(settings.paused==false){restartTicker();}});updateTicker();}
function updateTicker(){if(settings.contentLoaded==false){if(opts.ajaxFeed){debugError('Code Me!');}
else if(opts.htmlFeed){if($(newsID+' LI').length>0){$(newsID+' LI').each(function(i){settings.newsArr['item-'+i]={type:opts.titleText,content:$(this).html()};});}
else{debugError('Couldn\'t find any content for the ticker to use!');return false;}}
else{debugError('Couldn\'t find any content for the ticker to use!');return false;}
settings.contentLoaded=true;}
$(settings.dom.titleElem).html(settings.newsArr['item-'+settings.position].type);$(settings.dom.contentID).html(settings.newsArr['item-'+settings.position].content);if(settings.position==(countSize(settings.newsArr)-1)){settings.position=0;}
else{settings.position++;}
distance=$(settings.dom.contentID).width();time=distance/opts.speed;$(settings.dom.wrapperID).find(settings.dom.titleID).fadeIn().end().find(settings.dom.titleElem).fadeIn('slow',revealContent);}
function revealContent(){if(settings.play){var offset=$(settings.dom.titleElem).width()+20;$(settings.dom.revealID).css('left',offset+'px');$(settings.dom.revealElem).show(0,function(){$(settings.dom.contentID).css('left',offset+'px').show();$(settings.dom.revealID).css('margin-left','0px').delay(20).animate({marginLeft:distance+'px'},time,'linear',postReveal);});}
else{return false;}};function postReveal(){if(settings.play){$(settings.dom.contentID).delay(opts.pauseOnItems).fadeOut('slow');$(settings.dom.revealID).hide(0,function(){$(settings.dom.tickerID).delay(opts.pauseOnItems).fadeOut(opts.fadeOutSpeed,function(){$(settings.dom.wrapperID).find(settings.dom.titleElem+','+settings.dom.revealElem+','+settings.dom.contentID).hide().end().find(settings.dom.tickerID+','+settings.dom.revealID+','+settings.dom.titleID).show().end().find(settings.dom.tickerID+','+settings.dom.revealID+','+settings.dom.titleID).removeAttr('style');updateTicker();});});}
else{$(settings.dom.revealElem).hide();}}
function pauseTicker(){settings.play=false;$(settings.dom.tickerID+','+settings.dom.revealID+','+settings.dom.titleID+','+settings.dom.titleElem+','+settings.dom.revealElem+','+settings.dom.contentID).stop(true,true);$(settings.dom.revealID+','+settings.dom.revealElem).hide();$(settings.dom.wrapperID).find(settings.dom.titleID+','+settings.dom.titleElem).show().end().find(settings.dom.contentID).show();}
function restartTicker(){settings.play=true;settings.paused=false;postReveal();}
function changeContent(direction){pauseTicker();switch(direction){case'prev':if(settings.position==0){settings.position=countSize(settings.newsArr)-2;}
else if(settings.position==1){settings.position=countSize(settings.newsArr)-1;}
else{settings.position=settings.position-2;}
$(settings.dom.titleElem).html(settings.newsArr['item-'+settings.position].type);$(settings.dom.contentID).html(settings.newsArr['item-'+settings.position].content);break;case'next':$(settings.dom.titleElem).html(settings.newsArr['item-'+settings.position].type);$(settings.dom.contentID).html(settings.newsArr['item-'+settings.position].content);break;}
if(settings.position==(countSize(settings.newsArr)-1)){settings.position=0;}
else{settings.position++;}}});};$.fn.ticker.defaults={speed:0.10,ajaxFeed:false,htmlFeed:true,debugMode:true,controls:true,titleText:'Latest',pauseOnItems:2000,fadeInSpeed:300,fadeOutSpeed:300};})(jQuery);$(function(){$('#js-news').ticker();});$(document).ready(function(){var originalFontSize=$('#leftContent p').css('font-size');$(".resetFont").click(function(){$('#leftContent p').css('font-size',originalFontSize);});$(".increaseFont").click(function(){var currentFontSize=$('#leftContent p').css('font-size');var currentFontSizeNum=parseFloat(currentFontSize,10);var newFontSize=currentFontSizeNum*1.1;$('#leftContent p').css('font-size',newFontSize);return false;});$(".decreaseFont").click(function(){var currentFontSize=$('#leftContent p').css('font-size');var currentFontSizeNum=parseFloat(currentFontSize,10);var newFontSize=currentFontSizeNum*0.9;$('#leftContent p').css('font-size',newFontSize);return false;});});$(document).ready(function(){$('div.imageHolder').hover(function(){$(".cover",this).stop().animate({top:'0px'},{queue:false,duration:160});},function(){$(".cover",this).stop().animate({top:'-100px'},{queue:false,duration:160});});$('.boxgrid.caption').hover(function(){$(".cover",this).stop().animate({top:'160px'},{queue:false,duration:160});},function(){$(".cover",this).stop().animate({top:'220px'},{queue:false,duration:160});});});$(window).scroll(function(){var offset=$(window).scrollTop();if(offset>100){$('#topics #rLogo').fadeIn('slow');}
if(offset<100){$('#topics #rLogo').fadeOut('slow');}});$("div.blogs div.blog-area:last").css("border-bottom","none");$("div.blogs div.blog-area:last").css("padding-bottom","0px");$("div.blogs div.blog-area:last").css("margin-bottom","0px");$("div.text-stories div:last").css("border-bottom","none");$("div.text-stories div:last").css("margin-bottom","0px");$("div.text-stories div:last").css("padding-bottom","0px");