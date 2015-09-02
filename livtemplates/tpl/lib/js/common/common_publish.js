jQuery(function($){
	var pub = $('.common-form-pop');
	
    $('body').on('click', '.common-publish-button', function(event){
        event.stopPropagation();
        event.preventDefault();
      var self = $(event.currentTarget),
      	 type = self.attr('_type'),
      	 pop = $('#form_publish');
      $('.common-publish-button').data( 'lock', false );
      self.data( 'lock', true );
	    if ( self.data('show') ) {
	    	self.data('show', false);
	   		pop.css({top: -450})
	    } else {
	    	self.data('show', true);
	    	pop.css({top: 100});	
	    }
    });
    pub.on('click', '.publish-box-close', function ( event ) { 
    	var pop = $(event.currentTarget).closest('.common-form-pop');
    	var common_button = $('.common-publish-button').filter( function(){
			return $(this).data('lock');
		} );
    	pop.css({top: -450});
    	common_button.data('show',false);
    });
    pub.each( function(){
    	var _this = this,
    		method = 'hg_publish';
    	var hidden_name = '.publish-name-hidden',
    		hidden_id = '.publish-hidden';
		$(this).find('.publish-box')[method]({
	    	change: function () {
	    		var common_button = $('.common-publish-button').filter( function(){
	    			return $(this).data('lock');
	    		} );
	    		var setting_box = common_button.closest( '.setting-box' ),
	    			id = setting_box.attr( '_id' );
	    		var module = $('#group_' + id ).val();
        		var hidden = $(_this).find( hidden_name ).val(),
    				column_ids = $(_this).find( hidden_id ).val();
	    		common_button.html(function(){
	       			return hidden ? ($(this).attr('_prev') + '<span style="color:#000;">' + hidden + '</span>') : $(this).attr('_default');
	    		 });
	    		setting_box.find( '.column-area' ).remove();
	    		$('#column-tmpl').tmpl( { module: module,id:id } ).appendTo( setting_box );
	    		setting_box.find( '.column-id' ).val( column_ids );
	    		setting_box.find( '.column-name' ).val( hidden );
	    	},
	    	maxColumn: 3
	    });
    } );
});