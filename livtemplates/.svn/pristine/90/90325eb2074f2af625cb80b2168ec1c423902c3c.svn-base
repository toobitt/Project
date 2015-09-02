jQuery(function($){
	 var transmit_dialog=$('.transmit-dialog'),
	     transmit_link=$('.transmit-link'),
	     pic_dialog=$('.pic-dialog'),
	     pic_link=$('.btn_img');
	 function openDialog(obj,_width){
           obj.dialog({
			autoOpen:true,
			width:_width});
	 }
	 transmit_link.click(function(event) {
	 	    openDialog(transmit_dialog,500);
			event.preventDefault();
     });
     pic_link.click(function(event) {
	 	    openDialog(pic_dialog,380);
			event.preventDefault();
     });
})
jQuery(function($){
     var transmit_area=$('.transmit-area');
         transmit_area.val(transmit_area.attr('_default'));
         transmit_area.focus(function(){
             transmit_area.val('');
         })
         transmit_area.blur(function(){
            var _value=$.trim(transmit_area.val());
            if(!_value){
               transmit_area.val(transmit_area.attr('_default'));
            }
         })
         $('.down','.W_arrow').click(function(){
               $(this).parent().prev().addClass('all').end().remove();
            });
})
