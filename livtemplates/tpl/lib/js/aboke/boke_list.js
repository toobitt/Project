$(function(){
	(function($){
		$.widget('boke.boke_list',{
			options : {
			},
			_init : function(){
				this._on({
					'click .item-list li' : '_state',
					'click .play-button' : '_playvideo',
					'click .record-edit-back-close' : '_closeBox'
				})
			},
			
			_state : function(event){
				var self = $(event.currentTarget),
					box = self.closest('.m2o-state'),
					txt = self.text(),
		            cid = self.attr('_id'),                   /*admin_cate_id*/
		            lid = self.closest('.m2o-each').data('id');    /*每列id*/
			   box.find('span').text(txt);
		       var url = './run.php?mid='+gMid+'&a=update&infrm=1';
		       var data = {
		    	       admin_cate_id : cid,
		           	   id : lid
		       };
		       $.globalAjax(box, function(){
			        return $.getJSON(url, data ,function(json){
					});
			    });
			},
			
			_playvideo : function(event){
				var self = $(event.currentTarget);
				var obj = self.closest('.m2o-option');
				var index = self.closest('.m2o-each').index();
				var info ={};
				info.vodurl = data[index].video_detail.video_url;
				info.vodurl_m3u8 = data[index].video_detail.video_url_m3u8;
				$('#video-tpl').tmpl(info).appendTo(obj);
			},
			
			_closeBox : function(event){
				var self = $(event.currentTarget);
				self.closest('.m2o-option').find('.video-box').hide();
			},
		});
	})($);
	$('.common-list-content').boke_list();
})