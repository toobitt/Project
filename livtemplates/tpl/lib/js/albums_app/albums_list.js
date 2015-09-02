$(function(){
	(function($){
		$.widget('albums.albums_list',{
			options : {
				coverUrl : '',
				delUrl : ''
			},
			
			_create : function(){
			},
			
			_init : function(){
				this._on({
					'click .set-cover' : '_setcover',
					'click .m2o-close' : '_close',
					'click .del' : '_del'
				});
				this._initwidget();
			},
			
			_initwidget : function(){
				this.element.find('.m2o-each').geach();
				this.element.find('.albums-list').glist();
			},
			
			_setcover : function(event){
				var self = $(event.currentTarget),
					parent = self.closest("li"),
					op = this.options,
					url = op.coverUrl,
					data = {
						id : parent.attr("id"),
						albums_id : parent.attr("_id")
					};
				$.getJSON(url,data,function(json){
					var data = json[0];
					if(data.status == 1){
						alert("封面设置成功");
					}else{
						alert('请选择未被设置过的照片');
					}
					
				});
			},
			
			_del : function(event){
				var self = $(event.currentTarget),
					obj = self.closest('li'),
					op = this.options,
					url = op.delUrl,
					id = obj.attr('id');
				jConfirm( '您确定删除该条内容吗？' , '删除提醒' , function( result ){
					if( result ){
						$.getJSON(url,{id : id},function(){
							obj.remove();
						})
					}
				})
			},
			
			_close : function(){
				var obj = top.$( '#mainwin' )[0].contentWindow.$('#nodeFrame'),
				src = obj.attr('_src'); 
				obj.attr('src' , src);
			},
			
		})
	})($);
	$('.m2o-form').albums_list({
		coverUrl : './run.php?mid=' + gMid + '&a=set_surface_pic',
		delUrl : './run.php?mid=' + gMid + '&a=delete',
	});
});


