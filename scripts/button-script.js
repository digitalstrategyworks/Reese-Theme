$(function() {  	         	
	$('.button_wrap').hover(
		function () {
			var $this 		= $(this).children('.slidebttn');
			var $slidelem 	= $this.prev();
			
			$slidelem.stop().animate({'width':'225px'},300);
			$this.stop().animate({'left':'135px'},300);
			$slidelem.find('span').stop(true,true).fadeIn();
			$this.addClass('button_c');
		},
		function () {
			var $this 		= $(this).children('.slidebttn');
			var $slidelem 	= $this.prev();
			$slidelem.stop().animate({'width':'93px'},200);
			$this.stop().animate({'left':'3px'},200);
			$slidelem.find('span').stop(true,true).fadeOut();
			$this.removeClass('button_c');
		}
	);
});