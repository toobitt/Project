jQuery(function() {
  var App=$({});
  
  /*animate*/
  (function($){
    	 var appArr=['#media-apply','#picText-apply','#action-apply','#extend-apply','#user-center','#publish-apply','#base-appy','#system-apply'];
    	 $(window).scroll(function(){
	    	 var scrollT=$(window).scrollTop(),
	    	     el=null;
	    	 for(element in appArr){
		    	 el=appArr[element];
		    	 var el_h=$(el).height(),
		    	     el_offset=$(el).offset().top-200,
		    	     el_range=el_offset + el_h/2,
		    	     flag=$(el).data('flag') || 1;
		    	 if(scrollT >= el_offset && scrollT < el_range){
		    		 if(flag){
		    			 $(el).addClass('animate');
		    			 $(el).data('flag',0);
		    		 }else{
		    			 return;
		    		 } 
		    	 }
	    	 }	 
	     });	     
  })($);
})
