jQuery(function(){
   /*专题右侧页加载*/
  (function($){
	  $.widget('special.special_area',{
			options:{
			   first:'.common-list-data:first',
			   title:'.special-biaoti',
			   Frame:'#childnodeFrame',
			   loading:'.child-top-loading'
			},
			_create:function(){
				
			},
			_init:function(){
				var root=this.element,
                    _el=root.find(this.options['first']),
				    _url=_el.find(this.options['title']).attr('_href');
				_el.addClass('clicked');
				this._setFrame(_url);
				this._on({
					'click .common-list-data':'_loadcolumList',
					'click .special-editor':'_loadForm',
					'click .special-addcontent':'_loadForm',
				    'click .special-delcontent':'_preventD'
				});
			},
			_setFrame:function(url){
				var frame=$(this.options['Frame']);
				$(this.options['loading']).addClass('show');
				frame.attr('src',url);
			},
			_loadcolumList:function(event){
				var target=$(event.currentTarget),
				    url=target.find(this.options['title']).attr('_href');
				target.addClass('clicked').siblings().removeClass('clicked');
				this._setFrame(url);
			},
			_loadForm:function(event){
				var target=$(event.currentTarget),
				    url=target.attr('_href');
				this._setFrame(url);
				event.stopPropagation();
			},
			_preventD:function(event){
				event.stopPropagation();
			}
		});
  })(jQuery);
  $('.special-main').special_area();
});
