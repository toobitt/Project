$(function(){
	var search = $.globalSearch;
	var print = {
		edit : $('.edit'),
		source : $('#source'),
		url : './run.php?mid=' + search.mid + '&a=save_preview&id=' + search.fid
	};
	
	$('.nav-btn').on('click', '.btn', function(){
		var $this = $( event.target );
		if( $this.hasClass('btn-set') ){
			var is_edit = $this.hasClass('btn-edit');
			if( !is_edit ){
				$this.html('保存');
				print.edit.removeClass('fold');
				$this.addClass('btn-edit');
			}else{
				var html = print.source.val();
				$.post( print.url, { html : html}, function( data ){
					if( data['callback'] ){
						eval( data['callback'] );
						return;
					}
					data = typeof data == 'string' ? JSON.parse( data ) : data;
					console.log( typeof data );
					if( $.isArray( data ) && data[0] && data[0] == 'success'){
						location.reload();
					}else{
						$this.html( '编辑' );
						print.edit.addClass('fold');
						$this.removeClass('btn-edit');
					}
				} );
			}
		}else if( $this.hasClass('btn-print') ){
			window.print();
		}
	});
});