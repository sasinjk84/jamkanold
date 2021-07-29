;(function($){
	$.fn.gmallTab=function(options){
		var opt={
			itemId:'',
			interval:0,
			dir:'right',
			listTag:'img',
			activeClass:'active',
			isStop:false
			
		}
		$.extend(opt,options);
		return this.each(function(){
			var $this=$(this);
			opt.listTag.toLowerCase();			
			var $menuList=($.trim(opt.itemId))?$this.find(opt.listTag):false;
			var intG;
			if($menuList) var $itemList = $('#'+opt.itemId+'>div');
			var menuNum=$menuList.length;
			opt.interval = parseInt(opt.interval);
			var currentMenu=0;
			init();

			function init(){
				$menuList.each(function(idx){
					$(this).data('idx',idx);
					$(this).css('cursor','pointer');
					if($(this).attr('asrc')) $(this).data('osrc',$(this).attr('src'));
				});
				$menuList.bind('mouseover',function(){
					setMenu($(this).data('idx'));
				});
				
				$itemList.bind('mouseover',function(){
					opt.isStop = true;
				});
				$itemList.bind('mouseleave',function(){
					opt.isStop = false;
				});
				setMenu(0);
			}

			function setMenu(idx){
				idx = parseInt(idx);
				if(isNaN(idx) || idx < 0 || idx > menuNum) return;
				
				clearTimeout($this.intG);
				$menuList.each(function(sidx){
					if(idx == sidx){
						if(opt.listTag == 'img'){
							if($(this).attr('asrc')) $(this).attr('src',$(this).attr('asrc'));
						}else if(opt.activeClass){
							$(this).addClass(opt.activeClass);
						}
						
						$itemList.eq(sidx).css('display','block');
						currentMenu=idx;
					}else{
						if(opt.listTag == 'img'){
							if($(this).data('osrc')) $(this).attr('src',$j(this).data('osrc'));
						}else if(opt.activeClass){
							$(this).removeClass(opt.activeClass);
						}
						$itemList.eq(sidx).css('display','none');
					}
				});
				if(!isNaN(opt.interval) && opt.interval > 0) $this.intG = setTimeout(rollTab,opt.interval);
			}

			function rollTab(){
				if(opt.isStop) return;
				var nextidx;
				if(opt.dir == 'right'){
					nextidx  = currentMenu+1;
				}else{
					nextidx  = currentMenu-1;
				}
				if(nextidx > menuNum -1) nextidx = 0;
				else if(nextidx < 0 ) nextidx = menuNum -1;
				setMenu(nextidx);
				//$this.intG = setTimeout(rollTab,opt.interval);
			}
		});
	}
})(jQuery)