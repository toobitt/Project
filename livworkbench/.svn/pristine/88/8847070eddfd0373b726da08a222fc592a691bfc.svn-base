jQuery(function() {
  var App=$({});
  
 /*图片轮转*/
  (function($){
	var banner=$('.banner'),
        img=banner.find('img'),
        img_hd=$(window).height()-$('.hoge-head-bg').height();
	App.on({
    	setWidth:function(e,obj){
    	    var viewWidth=$(window).width();
    	    obj.css({'width':viewWidth+'px','height':img_hd+'px'});
    	    banner.css({'height':img_hd+'px'});
    	},
    	switchPic:function(e,obj){
    		obj.switchable({
    			putTriggers: 'appendTo',
    			panels: 'li',
    			initIndex:0, // display the last panel
    			effect: 'scrollLeft', // taking effect when autoplay == true
    			easing: 'cubic-bezier(.455, .03, .515, .955)', // equal to 'easeInOutQuad'
    			end2end: true, // if set to true, loop == true
    			loop: false, // not taking effect, because end2end == true
    			autoplay:true,
    			interval:2,
    			api: true 
    		});
    	}
    }).trigger('setWidth',[img]).trigger('switchPic',[banner]);
  })($);
  
  /*job tabs 动画*/
  (function($){
	  /*tab切换*/
	  App.on({
		      'animateTab':function(event,arr){
		    	  arr[3].css({'background-image':arr[0]});
				  arr[4].text(arr[1]);
			      $('.job-con[data-id="'+arr[2]+'"]').show().siblings().hide();
		      }
	  });
	  /*获得对象属性数组*/
	  function getTargets(obj){
		  var targets=[],
		      id=obj.data('id'),
		      name=obj.text(),
		      img_url=obj.css('background-image'),
	          depart_selected=$('.department-list').find('.department-slected'),
	          url_area=depart_selected.find('a'),
	          name_area=depart_selected.find('.name');
		  targets.push(img_url,name,id,url_area,name_area);
		  return targets;
	  }
	  $('.department-list').on('click','.depart-btn',function(e){
	    var self=$(e.currentTarget),
	        targetArray=getTargets(self);
        self.clone().css({'left':self.position().left+'px','z-index':5})
                    .appendTo(self.parent())
                    .animate({'left':'30px'},'slow',function(){
    	                  $(this).remove();
    	                  App.trigger('animateTab',[targetArray]);
                     });       
	   });
  })($);
  
  /*job展开*/
  (function($){
	 App.on({
			 'changeArea':function(event,arr){
				 arr[0].toggleClass('click');
				 arr[1].toggleClass('hover');
		         arr[2].slideToggle('fast');
		     }
	 });
	 /*获得目标对象数组*/
	 function getTargets(obj){
		 var targets=[],
		     obj_parent=obj.parent(),
		     real_obj=obj.hasClass('more')?obj:obj_parent.find('.more');
		 var showArea=obj_parent.find('.job-require');
		 targets.push(real_obj,obj_parent,showArea);
		 return targets;
	 }
	 $('.job-area').on('click','.more,.job-name',function(e){ 
		  var self=$(e.currentTarget),
		      targetArray=getTargets(self);
		  App.trigger('changeArea',[targetArray]);
	  });
  })($);
  
  /*culture */
  (function($){
	  App.on({
		  'showCon':function(event,arr){
			  arr[0].toggleClass('show');
			  arr[1].toggleClass('line'+arr[2]+'-hover');
		  }
	  });
	  /*获得目标对象数组*/
	  function getTargets(obj){
		  var targets=[],
		      obj_parent=obj.parent();
		      con=obj_parent.find('.descr'),
		      line=obj_parent.find('.line'),
		      id=obj.data('id');
		  targets.push(con,line,id);
		  return targets;
	  }
	  $('.culture-idea').on('hover','.title',function(e){
		  var self=$(e.currentTarget);
		      targetArray=getTargets(self);
		  App.trigger('showCon',[targetArray]);
	  });
  })($);
  
  /*news scroll*/
  (function($){
	  $('#newsListScroll').switchable({
		    triggers: '&bull;',
		    effect: 'scrollLeft',
		    steps: 3,
		    panels: 'li',
		    easing: 'ease-in-out',
		    loop: false,
		    prev: '.prev',
		    next: $('.next'),
		    onSwitch: function(event, currentIndex) {
		        var api = this,
		            len=this.length;
		        api.prevBtn.toggleClass('disabled', currentIndex === 0);
		        api.nextBtn.toggleClass('disabled', currentIndex === len - 1);
		    }
		  });

  })($);
})
