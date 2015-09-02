jQuery(function() {
  var App=$({});
  /*预加载*/
  $('img').lazyload({ 
		effect : "fadeIn" //加载图片使用的效果(淡入)
  });
  
  /*logo旋转*/
  (function($){
	  var logo=$('.hoge-head .logo'),
	      circle=logo.find('.logo-circle');
	  logo.hover(function(){
		  circle.addClass('rotate');
	  },function(){
		  circle.removeClass('rotate');
	  });
  })($);
  
  
  /*banner*/
  (function($){
	  var banner_area=$('.hoge-nav-bg'),
	    url=banner_area.attr('src'),
	    index=banner_area.attr('_index');
	$('<img />').on('load',function(){
		var _url=$(this).attr('src');
		banner_area.css('background-image','url('+_url+')').addClass('banner-animate');
		if($('.descr').hasClass('descr-culture')){
			    var cul_obj=$('.descr-culture'),
			        i=0;
			    cul_obj.find('span').eq(i).addClass('culture-location'+i)
				setInterval(function(){
					if(i){
						cul_obj.find('span').eq(i-1).removeClass('culture-location'+(i-1)).addClass('hide');
					}
					if(i>3){
						i=0;
					}
					if(cul_obj.find('span').eq(i).hasClass('culture-location'+i)){
						i++;
						cul_obj.find('span').eq(i-1).removeClass('culture-location'+(i-1)).addClass('hide');
						cul_obj.find('span').eq(i).removeClass('hide').addClass('culture-location'+i);
					}
					cul_obj.find('span').eq(i).removeClass('hide').addClass('culture-location'+i);
					i++;
					},5000);
		}else{
			$('.descr').addClass(index+'-location');
		}
	}).attr('src',url);
  })($);
  
  /*banner图片预加载*/
  (function($){
	  $('body').one('loadBanner',function(){
		  var global='images/';
		  var array=['banner_2.png','banner_3.png','banner_4.png','banner_5.png']
		  for(var i=0;i<array.length;i++){
			  var src=global+array[i];
			  $('<img />').attr('src',src);
		  }
	  });
	  $('body').trigger('loadBanner');
  })($);
  
  /*回到顶部*/
  $('<a class="goTop"></a>').appendTo('body');
  $(window).on('scroll',function(){
	  var top=$(this).scrollTop(),
	      top_link=$('.goTop');
	  if(top > 500){
		  top_link.addClass('show');
	  }else{
		  top_link.removeClass('show');
	  }
  });
  $('body').on('click','.goTop',function(){
	  $('body,html').animate({'scrollTop':0},'slow');
  });
  
  /*second nav*/
  (function($){
	  $.widget('hoge.secondNav',{
			 options:{
				 circle:'.circle',
				 mask:'.mask',
				 title:'.title',
				 active:'mask-active',
				 default_color:'#333'
			 },
			 _init:function(){
				 var root=this.element,
				     circle=root.find(this.options['circle']),
				     color=circle.css('color'),
				     title=root.find(this.options['title']),
				     mask=root.find(this.options['mask']),
				     active=this.options['active'];
				 this._on({
					 'mouseenter .circle':function(){
						 mask.addClass(this.options['active']);
						 title.css('color',color);
						 setTimeout(function(){
							   mask.removeClass(active);
						 },800);
					 },
					 'mouseleave .circle':function(){
						 title.css('color',this.options['default_color']);
					 },
					 'click a':function(event){
						 var self=$(event.currentTarget),
						     _top=$(self.attr('href')).offset().top;
						 event.preventDefault();
						 if(_top>2000){
							 $('body,html').animate({'scrollTop':_top+'px'},2000);	
						 }else{
							 $('body,html').animate({'scrollTop':_top+'px'},'slow');							 
						 }
					 },
					 'mouseover .title':function(){
						 title.css('color',color);
						 circle.trigger('mouseenter');
						 
					 },
					 'mouseout .title':function(){
						 title.css('color',this.options['default_color']);
						 circle.trigger('mouseleave');
					 }
				 });
			 }
		 });
      $('.second-nav li').secondNav();
  })($);
  
  /*new hover*/
  (function($){
	  $('.hoge-newslist .photo').hover(function(){
		  $(this).parent().find('.title a').css({'color':'#e73e33'});	  
	  },function(){
		  $(this).parent().find('.title a').css({'color':'#333'});	  
	  });
  })($);
  
  /*switch*/
  (function($){
	  $.widget('hoge.switchWidget',{
		  options:{
			  prev:'.prev',
			  next:'.next'
		  },
		  _init:function(){
			 var root=this.element,
			     prev=root.find(this.options['prev']),
			     next=root.find(this.options['next']);
			 this._trigger('switch_control',null,[root,prev,next]);
		  }
	  });
	  $('.switch-area').switchWidget({
		  switch_control:function(event,obj,_prev,_next){
			  obj.switchable({
				    triggers:null,
				    effect: 'scrollLeft',
				    steps: 1,
				    panels: 'li',
				    easing: 'ease-in-out',
				    visible: 1, // important
				    autoplay:false,
				    loop:false,
				    interval:3,
				    prev: _prev,
				    next: _next,
				    onSwitch: function(event, currentIndex) {
				        var api = this,
				            len=this.length;
				        api.prevBtn.toggleClass('disabled', currentIndex === 0);
				        api.nextBtn.toggleClass('disabled', currentIndex === len - 1);
				        var self=$('.switch-area').find('li').eq(currentIndex).find('img');
				        self.each(function(){
				        	$(this).trigger('appear');
				        });
				    }
			   });
		  }
	  });
  })($);
  
  /*轮播*/
  (function($){
	  $('#praise-area').switchable({
		  triggers:true,
		  effect:'scrollLeft',
		  panels: 'li',
		  easing: 'ease-in-out',
		  interval:1,
		  visible: 1, // important
		  autoplay:true,
		  end2end:true,
		  loop:true
	  });
  })($);
  
  /*culture hover*/
  (function($){
	  $('.culture-change li').hover(function(){
		  $(this).addClass('hover');
	  },function(){
		  $(this).removeClass('hover');
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
  
  /*depart hover*/
  (function($){
	   $('.job-second-nav').on('hover','li',function(e){
		    $(this).toggleClass('job-hover').siblings().toggleClass('job-gray');
	   });
  })($);

})
