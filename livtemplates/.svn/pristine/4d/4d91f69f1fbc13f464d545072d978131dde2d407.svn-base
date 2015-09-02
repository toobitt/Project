!(function($){

})(jQuery);

jQuery(function($){
    var box = $('#file-box').file({
        'cat-ajax-url' : 'run.php?mid=' + gMid + '&a=get_vod_node&fid={{fid}}',
        'list-ajax-url' : 'run.php?mid=' + gMid + '&a=get_vod_info&page={{pp}}&counts=10&vod_sort_id={{cat}}&title={{title}}&date_search={{date}}'
    });
    var sendYuJian = $('#send-yujian');
    
    box.on( 'click','#send-yujian', function( event ){
    	var self = $( event.currentTarget ),
			item = $(this).closest('li'),
	    	id = item.attr('_id'),
	    	data = $('#file-box').file('getData', id),
	    	name = data['title'];
	    if( item.hasClass( 'on' ) ){
	        return;
	    }
	    item.siblings().removeClass('on').end().addClass( 'on' );
	    $.post(
	        'run.php?mid=' + gMid + '&a=set_backup',
	        {
	            vod_id : id,
	            channel_id : $('#channel_id').val(),
	            type : 1
	        },
	        function(json){
	            json = json[0];
	            if(json['file_url']){
	                $('.play-left').triggerHandler('seturl', [{
	                    url : json['file_url'],
	                    rtmp : json['file_url'],
	                    id : id,
	                    streamid : json['backup_id'],
	                    toff : json['toff'],
	                    type : 'file',
	                    name : name
	                }, 'string']);
	            }
	        },
	        'json'
	    );
    } );



    box.on({
        mouseenter : function(){
            sendYuJian.appendTo(this).show();
        },

        mouseleave : function(){
            sendYuJian.hide();
        }
    }, '.file-list-li');

    $('.file-cat').hover(function(){
        $(this).addClass('on');
    }, function(){
        $(this).removeClass('on');
    });
    
    var interval = setInterval( function(){
    	var channel_id = $('#channel_id').val(),
    		url = './run.php?mid=' + gMid + '&a=keep_alive&channel_id=' + channel_id;
    	$.getJSON( url, function( data ){
    		
    	} );
    }, 5000 );
});